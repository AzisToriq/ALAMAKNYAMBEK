<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use Illuminate\Support\Facades\Schema;
use App\Models\Setting; 
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk pengecekan DB yang lebih aman

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Metode boot ini akan dijalankan setelah semua service provider terdaftar.
     */
    public function boot(): void
    {
        // Pengecekan pertama: Pastikan tabel 'settings' sudah ada di database.
        if (Schema::hasTable('settings')) {
            try {
                // Cek apakah ada record setting
                $setting = Setting::first();
                
                // Jika data settings belum ada (tabel ada tapi kosong), buat data default otomatis
                if (!$setting) {
                    $setting = Setting::create([
                        // Set nilai default yang sesuai dengan struktur tabel settings Anda
                        'shop_name' => 'POS System',
                        'shop_address' => 'Alamat Toko',
                        'shop_phone' => '081234567890',
                        'shop_email' => 'toko@example.com',
                        
                        // Default modul yang harus ada (penting untuk menghindari error null di view)
                        'enable_supplier' => false,
                        'enable_inventory' => false,
                        'enable_finance' => false,
                        
                        // Default fitur POS
                        'enable_customer' => false,
                        'enable_discount' => true,
                        'enable_tax' => true,
                        'enable_stock_badge' => true,
                        'enable_low_stock_alert' => true,
                        'tax_rate' => 10.00,
                        'currency' => 'IDR',
                        'currency_symbol' => 'Rp',
                        'receipt_footer' => 'Terima kasih atas kunjungan Anda!',
                        'auto_print_receipt' => false,
                        'enable_email_notification' => false,
                        'enable_whatsapp_notification' => false,
                    ]);
                }
                
                // Share variable $setting ke seluruh view blade
                View::share('setting', $setting);
                
            } catch (\Exception $e) {
                // Jika terjadi error koneksi DB atau model tidak ditemukan, share null
                // Ini penting untuk mencegah crash saat debugging atau instalasi
                View::share('setting', null);
            }
        } else {
            // Jika tabel belum ada (saat migration atau instalasi awal), share null
            View::share('setting', null);
        }
    }
}