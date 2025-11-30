<?php

namespace App\Http\Controllers;

use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\Supplier; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        // Ambil riwayat stok, urutkan dari yang terbaru
        $logs = InventoryLog::with(['product', 'user'])
                ->latest()
                ->paginate(10); // Pakai pagination biar gak berat kalau data ribuan

        return view('inventory.index', compact('logs'));
    }

    public function create()
    {
        // Form untuk input stok
        $products = Product::orderBy('name')->get();
        // [PERBAIKAN] Ambil data supplier, TERLEPAS DARI APAKAH MODUL SUPPLIER AKTIF ATAU TIDAK
        $suppliers = Supplier::orderBy('name')->get(); 

        // [PERBAIKAN] KIRIM KEDUA VARIABEL: $products DAN $suppliers
        return view('inventory.create', compact('products', 'suppliers')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment,return',
            'qty' => 'required|integer|min:1',
            'date' => 'required|date',
            'note' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        // Gunakan DB Transaction agar data aman (Atomicity)
        // Jika gagal simpan log, update stok produk juga dibatalkan
        DB::transaction(function () use ($request) {
            
            $product = Product::findOrFail($request->product_id);
            
            // 1. Hitung Stok Baru
            $currentStock = $product->stock;
            $newStock = 0;

            if ($request->type == 'in' || $request->type == 'return') {
                $newStock = $currentStock + $request->qty;
            } else {
                // Cek apakah stok cukup untuk dikurangi
                if ($currentStock < $request->qty) {
                    throw new \Exception("Stok tidak cukup! Sisa stok: " . $currentStock);
                }
                $newStock = $currentStock - $request->qty;
            }

            // 2. Simpan Log ke InventoryLogs
            InventoryLog::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
                'type' => $request->type,
                'qty' => $request->qty,
                'price' => $product->buy_price, // Catat harga modal saat kejadian
                'last_stock' => $newStock, // Snapshot stok akhir
                'date' => $request->date,
                'note' => $request->note,
            ]);

            // 3. Update Stok di Master Produk
            $product->update(['stock' => $newStock]);
        });

        return redirect()->route('inventory.index')->with('success', 'Stok berhasil diperbarui!');
    }
}