<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Catatan: Pastikan Anda melindungi semua rute Settings 
    // di file routes/web.php menggunakan middleware role:owner.

    /**
     * Tampilkan halaman settings (formulir utama).
     */
    public function index()
    {
        // Mengasumsikan data settings sudah di-load secara global melalui View Composer 
        // atau shared data, atau kita bisa memanggilnya di sini jika diperlukan.
        return view('settings.index');
    }
    
    /**
     * Tampilkan formulir untuk inisialisasi settings (hanya jika tabel kosong).
     */
    public function create()
    {
        // Karena settings hanya ada 1 row, jika data sudah ada, redirect ke halaman utama.
        if (Setting::count() > 0) {
            return redirect()->route('settings.index');
        }
        return view('settings.create');
    }

    /**
     * Simpan record settings baru (hanya untuk inisialisasi pertama kali).
     */
    public function store(Request $request)
    {
        // Jika sudah ada record, kita panggil update saja
        if (Setting::count() > 0) {
            return $this->update($request);
        }
        
        // Logic store: Validasi dan buat record baru.
        $request->validate([
            'shop_name' => 'required|string',
            'tax_rate' => 'nullable|integer|min:0|max:100',
        ]);
        
        Setting::create([
            'shop_name' => $request->shop_name,
            'shop_address' => $request->shop_address,
            'shop_phone' => $request->shop_phone,
            // Default nilai boolean jika tidak ada di form
            'enable_table_number' => $request->has('enable_table_number') ? 1 : 0,
            'enable_tax' => $request->has('enable_tax') ? 1 : 0,
            'tax_rate' => $request->tax_rate ?? 0,
            'enable_stock_badge' => $request->has('enable_stock_badge') ? 1 : 0,
            'enable_supplier' => $request->has('enable_supplier') ? 1 : 0,
            'enable_inventory' => $request->has('enable_inventory') ? 1 : 0,
            'enable_finance' => $request->has('enable_finance') ? 1 : 0,
        ]);
        
        return back()->with('success', 'Konfigurasi Sistem Awal Disimpan!');
    }
    
    /**
     * Metode edit mengarahkan kembali ke index karena ini adalah single-row settings.
     */
    public function edit()
    {
        return redirect()->route('settings.index');
    }

    /**
     * Update settings yang sudah ada.
     */
    public function update(Request $request)
    {
        $setting = Setting::first();
        
        // Cek jika setting belum ada (meskipun harusnya tidak terjadi jika ada validasi di create)
        if (!$setting) {
             return $this->store($request);
        }
        
        $request->validate([
            'shop_name' => 'required|string',
            'tax_rate' => 'nullable|integer|min:0|max:100',
        ]);

        $setting->update([
            // Identitas
            'shop_name' => $request->shop_name,
            'shop_address' => $request->shop_address,
            'shop_phone' => $request->shop_phone,
            
            // Fitur POS
            // Gunakan ternary operator (? 1 : 0) agar data yang masuk ke DB pasti angka
            'enable_table_number' => $request->has('enable_table_number') ? 1 : 0,
            'enable_tax' => $request->has('enable_tax') ? 1 : 0,
            'tax_rate' => $request->tax_rate ?? 0,
            'enable_stock_badge' => $request->has('enable_stock_badge') ? 1 : 0,

            // --- FITUR MODUL OFFICE ---
            'enable_supplier' => $request->has('enable_supplier') ? 1 : 0,
            'enable_inventory' => $request->has('enable_inventory') ? 1 : 0,
            'enable_finance' => $request->has('enable_finance') ? 1 : 0,
        ]);

        return back()->with('success', 'Konfigurasi Sistem Diperbarui!');
    }
}