<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Modifier;
use App\Models\ModifierOption;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ModifierController extends Controller
{
    // 1. DAFTAR MODIFIER
    public function index()
    {
        $modifiers = Modifier::with('options')->latest()->get();
        // Arahkan ke folder view yang benar: admin.modifiers
        return view('admin.modifiers.index', compact('modifiers'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        // PERBAIKAN: Ambil data produk agar view tidak error
        $products = Product::orderBy('name')->get(); 
        
        return view('admin.modifiers.create', compact('products'));
    }

    // 3. SIMPAN MODIFIER BARU (FIXED dengan Relasi Produk)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(['optional', 'required'])],
            'options' => 'required|array|min:1',
            'options.*.name' => 'required|string',
            'options.*.price' => 'required|numeric|min:0',
            // Validasi input produk
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        DB::transaction(function() use ($request) {
            // 1. Simpan Modifier Utama
            $modifier = Modifier::create([
                'name' => $request->name,
                'type' => $request->type, 
                'is_multiple' => $request->has('is_multiple') ? 1 : 0,
            ]);

            // 2. Simpan Opsi (Pilihan)
            foreach ($request->options as $optionData) {
                ModifierOption::create([
                    'modifier_id' => $modifier->id,
                    'name' => $optionData['name'],
                    'price' => $optionData['price'],
                ]);
            }

            // 3. HUBUNGKAN KE PRODUK (INI YANG HILANG SEBELUMNYA)
            if ($request->has('product_ids')) {
                $modifier->products()->sync($request->product_ids);
            }
        });

        return redirect()->route('modifiers.index')->with('success', 'Modifier berhasil ditambahkan dan dihubungkan!');
    }

    // 4. FORM EDIT MODIFIER
    public function edit($id)
    {
        // Kita ambil Modifier, Opsi-nya, DAN Produk yang sudah terhubung (agar checkbox tercentang otomatis)
        $modifier = Modifier::with(['options', 'products'])->findOrFail($id);
        
        // PERBAIKAN: Ambil semua data produk untuk ditampilkan di list pilihan
        $products = Product::orderBy('name')->get();

        // Kirim variabel $modifier dan $products ke view
        return view('admin.modifiers.edit', compact('modifier', 'products'));
    }

    // 5. UPDATE MODIFIER (Logic Perbaikan)
    public function update(Request $request, $id)
    {
        $modifier = Modifier::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(['optional', 'required'])],
            'options' => 'required|array|min:1',
            'options.*.name' => 'required|string',
            'options.*.price' => 'required|numeric|min:0',
            // Validasi produk (array ID produk)
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        DB::transaction(function() use ($request, $modifier) {
            // A. Update Data Utama
            $modifier->update([
                'name' => $request->name,
                'type' => $request->type,
                'is_multiple' => $request->has('is_multiple') ? 1 : 0,
            ]);

            // B. Update Opsi (Hapus lama, buat baru)
            $modifier->options()->delete();
            foreach ($request->options as $optionData) {
                ModifierOption::create([
                    'modifier_id' => $modifier->id,
                    'name' => $optionData['name'],
                    'price' => $optionData['price'],
                ]);
            }

            // C. UPDATE PRODUK TERHUBUNG (INI YANG HILANG TADI)
            // Fungsi sync() otomatis menambah yang dicentang & menghapus yang tidak dicentang
            $modifier->products()->sync($request->product_ids ?? []);
        });

        return redirect()->route('modifiers.index')->with('success', 'Modifier dan hubungan produk berhasil diperbarui!');
    }

    // 6. HAPUS
    public function destroy($id)
    {
        $modifier = Modifier::findOrFail($id);
        $modifier->options()->delete();
        $modifier->delete();

        return back()->with('success', 'Modifier berhasil dihapus!');
    }
}