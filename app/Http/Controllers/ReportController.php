<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\Expense;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function profitLoss(Request $request)
    {
        // 1. Tentukan Rentang Tanggal
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        // 2. Ambil Data Detail Penjualan
        $salesData = TransactionDetail::with(['transaction', 'product'])
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate)
                  ->where('status', 'paid');
            })
            ->latest()
            ->get();

        // --- PERBAIKAN LOGIKA HITUNG ---
        
        // A. Total Omzet (Pemasukan Kotor)
        $totalRevenue = $salesData->sum('subtotal'); 

        // B. Total Laba Kotor (Gross Profit)
        // KITA AMBIL LANGSUNG DARI KOLOM 'profit' DI DATABASE
        // Karena kolom ini sudah diperbaiki oleh script /fix-profit
        $grossProfit = $salesData->sum('profit');

        // C. Total HPP (Modal)
        // Rumus balik: Omzet - Profit = HPP
        $totalHpp = $totalRevenue - $grossProfit;

        // -------------------------------

        // 3. Ambil Data Pengeluaran
        $expensesData = Expense::whereDate('date', '>=', $startDate)
                                ->whereDate('date', '<=', $endDate)
                                ->latest('date')
                                ->get();

        $totalExpenses = $expensesData->sum('amount');

        // 4. Hitung Laba Bersih Akhir
        $netProfit = $grossProfit - $totalExpenses;
        
        // 5. Ambil Setting untuk Kop Laporan
        $setting = Setting::first();

        return view('reports.profit_loss', compact(
            'startDate', 'endDate', 
            'totalRevenue', 'totalHpp', 'grossProfit', 
            'totalExpenses', 'netProfit',
            'salesData', 'expensesData', 'setting'
        ));
    }
}