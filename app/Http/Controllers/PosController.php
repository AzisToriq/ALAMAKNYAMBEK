<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; 
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\InventoryLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PosController extends Controller
{
    // 1. TAMPILKAN HALAMAN KASIR
    public function index()
    {
        // Eager Load 'modifiers.options' agar data add-on terbawa ke tampilan POS
        $products = Product::with(['modifiers.options'])
                    ->orderBy('name')
                    ->get();
                    
        $categories = Category::all();
        $setting = Setting::first();

        return view('pos.index', compact('products', 'categories', 'setting'));
    }

    // 2. PROSES CHECKOUT (SIMPAN TRANSAKSI)
    public function store(Request $request)
    {
        // A. Validasi Input
        $validated = $request->validate([
            'cart_data' => 'required|json',
            'total_amount' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'change_amount' => 'required|numeric',
            'table_number' => 'nullable|string|max:20',
            'payment_method' => 'required|string|in:cash,qris',
            'payment_ref' => 'nullable|string|max:100',
        ]);

        // B. Decode Cart Data
        $cartItems = json_decode($validated['cart_data'], true);
        
        if (empty($cartItems)) {
            return back()->with('error', 'Keranjang belanja kosong!');
        }

        // C. Mulai Database Transaction 
        try {
            DB::beginTransaction();
            
            // --- HITUNG TOTAL DI BACKEND (Double Check) ---
            $setting = Setting::first();
            $taxRate = ($setting && $setting->enable_tax) ? $setting->tax_rate : 0;
            
            // Hitung subtotal kasar
            $calculatedSubtotal = 0;
            foreach ($cartItems as $item) {
                $calculatedSubtotal += ($item['sell_price'] * $item['qty']);
            }
            
            $taxAmount = $calculatedSubtotal * ($taxRate / 100);
            $grandTotal = $calculatedSubtotal + $taxAmount;
            $changeAmount = $validated['pay_amount'] - $grandTotal;
            
            // 1. Buat Header Transaksi
            $invoiceCode = 'INV-' . date('ymd') . '-' . strtoupper(Str::random(4));
            
            $transaction = Transaction::create([
                'invoice_code' => $invoiceCode,
                'user_id' => Auth::id(),
                'table_number' => $validated['table_number'] ?? null,
                'total_price' => $calculatedSubtotal,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'cash_amount' => $validated['pay_amount'],
                'change_amount' => $changeAmount,
                'payment_method' => $validated['payment_method'],
                'payment_ref' => $validated['payment_ref'] ?? null,
                'status' => 'paid',
            ]);

            // 2. Loop setiap item di keranjang (DETAIL & PROFIT)
            foreach ($cartItems as $index => $item) {
                
                // Kunci produk untuk update stok (mencegah race condition)
                $product = Product::lockForUpdate()->find($item['product_id']);
                
                if (!$product) {
                    throw new \Exception("Produk dengan ID {$item['product_id']} tidak ditemukan!");
                }

                // Validasi stok
                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok produk {$product->name} tidak cukup! Sisa: {$product->stock}");
                }

                // --- PERBAIKAN LOGIKA LABA (PROFIT) ---
                
                // 1. Ambil Harga Modal ASLI dari Database
                $realBuyPrice = $product->buy_price; 

                // 2. Harga Jual Akhir (Harga Produk + Harga Modifier) dari inputan JS
                $finalSellPrice = $item['sell_price'];
                
                // 3. Hitung Profit yang Benar
                $qty = $item['qty'];
                $subtotalItem = $qty * $finalSellPrice;
                
                // Profit = (Harga Jual - Harga Modal Asli) * Jumlah
                $profitPerUnit = $finalSellPrice - $realBuyPrice;
                $totalProfit = $profitPerUnit * $qty;
                
                // --- END PERBAIKAN ---

                // --- PERBAIKAN MODIFIER DATA (FIXED) ---
                // JANGAN pakai json_encode lagi di sini, karena Model sudah casting ke array
                // Cukup kirim Array mentah, Laravel yang urus sisanya.
                $modifiersData = null;
                if (!empty($item['modifiers']) && is_array($item['modifiers'])) {
                    $modifiersData = $item['modifiers']; // <--- PERUBAHAN UTAMA: Hapus json_encode()
                }
                
                $notes = isset($item['notes']) && !empty($item['notes']) ? $item['notes'] : null;
                
                // Simpan Detail Transaksi
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'base_price' => $realBuyPrice, // PENTING: Menyimpan Modal Asli
                    'sell_price' => $finalSellPrice,
                    'subtotal' => $subtotalItem,
                    'profit' => $totalProfit,     // PENTING: Menyimpan Profit yang Benar
                    'modifiers_data' => $modifiersData, // Sekarang dikirim sebagai Array
                    'notes' => $notes,
                ]);

                // Update Stok Produk
                $product->decrement('stock', $qty);
                $newStock = $product->fresh()->stock;

                // Catat Log Inventory
                InventoryLog::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'sale',
                    'qty' => $qty,
                    'price' => $finalSellPrice,
                    'last_stock' => $newStock,
                    'ref_number' => $invoiceCode,
                    'note' => 'Penjualan Kasir (' . strtoupper($validated['payment_method']) . ')',
                    'date' => now(),
                ]);
            }

            // Commit transaksi jika semua lancar
            DB::commit();
            
            return redirect()
                ->route('pos.index')
                ->with('success', 'Transaksi Berhasil! Kode: ' . $invoiceCode);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error untuk developer
            Log::error('=== POS CHECKOUT FAILED ===');
            Log::error($e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Transaksi Gagal: ' . $e->getMessage())
                ->withInput();
        }
    }

    // 3. TAMPILKAN STRUK/RECEIPT
    public function receipt($id)
    {
        $transaction = Transaction::with(['details.product', 'user'])
            ->findOrFail($id);
        
        $setting = Setting::first();

        return view('pos.receipt', compact('transaction', 'setting'));
    }

    // 4. PRINT STRUK (THERMAL PRINTER FORMAT)
    public function print($id)
    {
        $transaction = Transaction::with(['details.product', 'user'])
            ->findOrFail($id);
        
        $setting = Setting::first();

        return view('pos.print', compact('transaction', 'setting'));

        
    }
}