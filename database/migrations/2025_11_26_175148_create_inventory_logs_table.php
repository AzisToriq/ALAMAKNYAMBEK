<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            // Relasi wajib (Product & User)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Siapa yang input
            $table->foreignId('supplier_id')->nullable()->constrained(); // Jika barang masuk
            
            // Tipe Mutasi: Masuk, Keluar(Rusak/Exp), Terjual, Penyesuaian, Retur
            $table->enum('type', ['in', 'out', 'sale', 'adjustment', 'return']);
            
            $table->integer('qty'); // Jumlah perubahan (+/-)
            $table->decimal('price', 15, 2)->default(0); // Harga modal saat kejadian
            $table->integer('last_stock'); // Stok akhir setelah perubahan
            
            $table->string('ref_number')->nullable(); // No Nota / No Faktur
            $table->text('note')->nullable(); // Catatan
            
            $table->date('date'); // Tanggal transaksi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};