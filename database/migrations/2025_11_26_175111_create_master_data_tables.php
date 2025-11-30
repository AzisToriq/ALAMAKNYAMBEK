<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori Produk (Makanan, Minuman, Elektronik)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable(); 
            $table->timestamps();
        });

        // 2. Satuan (Pcs, Kg, Box, Liter)
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Pcs
            $table->string('short_name'); // pcs
            $table->timestamps();
        });

        // 3. Supplier (Pemasok Barang)
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('units');
        Schema::dropIfExists('categories');
    }
};