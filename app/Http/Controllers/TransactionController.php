<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // 1. LIHAT RIWAYAT TRANSAKSI DENGAN FILTER
    public function index(Request $request)
    {
        // Query dasar dengan eager loading
        $query = Transaction::with(['user', 'details.product']);

        // Filter berdasarkan tanggal dari (date_from)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filter berdasarkan tanggal sampai (date_to)
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Ambil data dengan pagination
        $transactions = $query->latest()->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    // 2. HALAMAN CETAK STRUK (THERMAL)
    public function print($id)
    {
        // Ambil detail transaksi beserta item produknya
        $transaction = Transaction::with(['details.product', 'user'])->findOrFail($id);

        return view('transactions.print', compact('transaction'));
    }
}