<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Modifiers (GROUP)
        // Ini hanya menampung Judul Group, misal: "Level Pedas", "Pilihan Toping"
        Schema::create('modifiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Group
            $table->string('type')->default('optional'); // 'required' (wajib pilih) atau 'optional'
            $table->boolean('is_multiple')->default(false); // false = radio button (pilih 1), true = checkbox (pilih banyak)
            $table->timestamps();
        });

        // 2. Tabel Modifier Options (ITEM / ANAKNYA) -> INI YANG TADI ERROR (MISSING TABLE)
        // Ini menampung isi pilihannya, misal: "Level 1", "Level 2", "Keju", "Coklat"
        Schema::create('modifier_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modifier_id')->constrained('modifiers')->onDelete('cascade'); // Terhubung ke tabel modifiers di atas
            $table->string('name'); // Nama Pilihan
            $table->decimal('price', 10, 2)->default(0); // Harga ada di sini!
            $table->timestamps();
        });

       // 3. Tabel Pivot: Relasi Produk <-> Modifier Group
        Schema::create('product_modifier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('modifier_id')->constrained()->onDelete('cascade');
            
            // TAMBAHKAN BARIS INI:
            $table->integer('order')->default(0); // Untuk mengatur urutan modifier di produk
            
            $table->timestamps();
        });
    }

    public function down()
    {
        // Hapus urut dari anak ke induk supaya tidak error foreign key
        Schema::dropIfExists('product_modifier');
        Schema::dropIfExists('modifier_options');
        Schema::dropIfExists('modifiers');
    }
};