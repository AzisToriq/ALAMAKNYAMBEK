<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        // 0. CEK KEAMANAN (REDIRECT KASIR)
        // Jika yang login BUKAN Owner, tendang ke halaman Kasir
        if (Auth::user()->role !== 'owner') {
            return redirect()->route('pos.index');
        }

        // 1. KARTU ATAS (Total Produk & Stok Tipis)
        $totalProducts = Product::count();
        $lowStock = Product::whereColumn('stock', '<=', 'min_stock')->count();

        // 2. PENJUALAN HARI INI (Omzet Kotor / Subtotal)
        // Kita pakai 'total_price' (bukan grand_total), karena grand_total mengandung pajak.
        $todaySales = Transaction::whereDate('created_at', Carbon::today())
                        ->sum('total_price');

        // 3. LABA BERSIH BULAN INI (Net Profit)
        $startMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // A. Hitung Gross Profit (Keuntungan Jual Barang)
        // Ambil detail transaksi bulan ini
        $salesDetails = TransactionDetail::whereHas('transaction', function($q) use ($startMonth, $endMonth) {
            $q->whereBetween('created_at', [$startMonth, $endMonth]);
        })->get();

        // Rumus: Total Jual (Subtotal) - Total Modal (Base Price)
        $grossProfit = $salesDetails->sum('subtotal') - $salesDetails->sum(function($item) {
            return $item->base_price * $item->qty;
        });
        
        // B. Hitung Pengeluaran Operasional (Listrik, Gaji, dll)
        $expenses = Expense::whereBetween('date', [$startMonth, $endMonth])->sum('amount');

        // C. Laba Bersih = Gross Profit - Expenses
        $netProfit = $grossProfit - $expenses;


        // 4. GRAFIK PENJUALAN (7 HARI TERAKHIR)
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::now()->subDays($i)->format('d M'); 
            
            // Grafik menampilkan Omzet (Subtotal)
            $dailySales = Transaction::whereDate('created_at', $date)->sum('total_price');
            $chartData[] = $dailySales;
        }

        // 5. PRODUK TERLARIS (TOP 5)
        $topProducts = TransactionDetail::select('product_id', DB::raw('SUM(qty) as total_qty'))
                        ->with('product')
                        ->groupBy('product_id')
                        ->orderByDesc('total_qty')
                        ->limit(5)
                        ->get();

        return view('dashboard', compact(
            'totalProducts', 'lowStock', 'todaySales', 'netProfit',
            'chartLabels', 'chartData', 'topProducts'
        ));
    }
}