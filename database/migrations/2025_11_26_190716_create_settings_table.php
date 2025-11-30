<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // 1. Identitas Usaha
            $table->string('shop_name');
            $table->string('shop_address')->nullable();
            $table->string('shop_phone')->nullable();
            
            // 2. Tampilan Kasir (POS)
            $table->boolean('enable_table_number')->default(true); // Input No Meja
            $table->boolean('enable_tax')->default(false);         // Aktifkan Pajak
            $table->integer('tax_rate')->default(0);               // Persentase Pajak (0-100)
            $table->boolean('enable_stock_badge')->default(true);  // Tampilkan Sisa Stok

            // 3. Kelola Modul (Fitur)
            $table->boolean('enable_supplier')->default(true);     // Modul Supplier
            $table->boolean('enable_inventory')->default(true);    // Modul Stok/Inventory
            $table->boolean('enable_finance')->default(true);      // Modul Keuangan/Laba Rugi
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};