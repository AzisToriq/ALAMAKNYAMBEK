<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Relasi ke kategori dan unit (jika dihapus master-nya, produk jadi null kategory-nya)
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('set null');

            // Data Produk
            $table->string('code')->unique(); // SKU (Kode manual/auto)
            $table->string('barcode')->nullable()->unique(); // Scan barcode pabrik
            $table->string('name');
            
            // Keuangan (Decimal agar akurat)
            $table->decimal('buy_price', 15, 2)->default(0); // Harga Modal
            $table->decimal('sell_price', 15, 2)->default(0); // Harga Jual
            
            // Stok (Hanya angka cache, history asli ada di inventory_logs)
            $table->integer('stock')->default(0); 
            $table->integer('min_stock')->default(5); // Alert stok menipis
            
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true); // Agar tidak hapus permanen data lama
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};