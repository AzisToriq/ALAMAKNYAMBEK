<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user')->latest('date')->paginate(10);
        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        Expense::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Pengeluaran berhasil dicatat!');
    }

    // --- TAMBAHAN: FUNGSI UPDATE ---
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $expense->update([
            'name' => $request->name,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Data pengeluaran diperbarui!');
    }
    // -------------------------------

    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return back()->with('success', 'Data dihapus!');
    }
}