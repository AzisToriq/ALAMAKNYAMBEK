<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterController extends Controller
{
    // 1. TAMPILKAN HALAMAN
    public function index()
    {
        $categories = Category::latest()->get();
        $units = Unit::latest()->get();
        
        return view('master.index', compact('categories', 'units'));
    }

    // --- LOGIKA KATEGORI ---

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // --- LOGIKA SATUAN (UNIT) ---

    public function storeUnit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10'
        ]);

        Unit::create([
            'name' => $request->name,
            'short_name' => $request->short_name
        ]);

        return back()->with('success', 'Satuan berhasil ditambahkan!');
    }

    public function updateUnit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10'
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update([
            'name' => $request->name,
            'short_name' => $request->short_name
        ]);

        return back()->with('success', 'Satuan berhasil diperbarui!');
    }

    public function destroyUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return back()->with('success', 'Satuan berhasil dihapus!');
    }
}