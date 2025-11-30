<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\InventoryLog; // <-- Tambah Import Ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // <-- Tambah Import Ini
use Illuminate\Support\Facades\Auth; // <-- Tambah Import Ini
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil data produk dengan relasinya
        $products = Product::with(['category', 'unit'])->latest()->get();
        
        // Ambil data kategori & unit untuk isi Dropdown
        $categories = Category::all();
        $units = Unit::all();

        return view('products.index', compact('products', 'categories', 'units'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'initial_stock' => 'nullable|integer|min:0', // Validasi Stok Awal
        ]);

        // 2. Generate Kode/SKU jika kosong
        $code = $request->code;
        if (empty($code)) {
            $code = 'PRD-' . strtoupper(Str::random(6));
        }

        // 3. Handle Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // 4. Simpan dengan Database Transaction (Agar Aman)
        DB::transaction(function () use ($request, $code, $imagePath) {
            
            // A. Buat Produk Baru (Stok 0 dulu)
            $product = Product::create([
                'name' => $request->name,
                'code' => $code,
                'barcode' => $request->barcode,
                'category_id' => $request->category_id,
                'unit_id' => $request->unit_id,
                'buy_price' => $request->buy_price,
                'sell_price' => $request->sell_price,
                'min_stock' => $request->min_stock ?? 5,
                'image' => $imagePath,
                'stock' => 0, 
            ]);

            // B. Cek Apakah User Mengisi Stok Awal?
            if ($request->has('initial_stock') && $request->initial_stock > 0) {
                
                // 1. Catat Log Inventory (Otomatis Barang Masuk)
                InventoryLog::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'in', // Tipe Masuk
                    'qty' => $request->initial_stock,
                    'price' => $product->buy_price,
                    'last_stock' => $request->initial_stock,
                    'note' => 'Stok Awal saat Pembuatan Produk',
                    'date' => now(),
                ]);

                // 2. Update Stok di Tabel Produk
                $product->update(['stock' => $request->initial_stock]);
            }
        });

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'stock' => 'required|integer|min:0', // Validasi Stok Fisik
            'min_stock' => 'nullable|integer|min:0', // Validasi Min Stock
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle Update Gambar
        $imagePath = $product->image; 
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // --- HANDLE PERUBAHAN STOK MANUAL (LOG ADJUSTMENT) ---
        if ($request->stock != $product->stock) {
            $diff = $request->stock - $product->stock;
            
            InventoryLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'adjustment',
                'qty' => abs($diff),
                'price' => $product->buy_price,
                'last_stock' => $request->stock,
                'note' => 'Koreksi Manual via Edit Produk (' . ($diff > 0 ? '+' : '') . $diff . ')',
                'date' => now(),
            ]);
        }
        // --------------------------------------------------

        $product->update([
            'name' => $request->name,
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'buy_price' => $request->buy_price,
            'sell_price' => $request->sell_price,
            'stock' => $request->stock, 
            'min_stock' => $request->min_stock ?? 0, // <--- SOLUSI ERROR: Jika kosong, isi 0
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Data produk diperbarui!');
    }    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return back()->with('success', 'Produk dihapus!');
    }
}