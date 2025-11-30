<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Header Transaksi (Nota)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code')->unique(); // Cth: INV-202311-0001
            $table->foreignId('user_id')->constrained(); // Siapa kasirnya
            
            // Keuangan
            $table->decimal('total_price', 15, 2); // Total Belanja
            $table->decimal('cash_amount', 15, 2); // Uang yang diterima
            $table->decimal('change_amount', 15, 2); // Kembalian
            $table->enum('payment_method', ['cash', 'qris', 'transfer'])->default('cash');
            
            $table->enum('status', ['paid', 'canceled'])->default('paid');
            $table->text('note')->nullable(); // Catatan opsional
            $table->timestamps();
        });

        // 2. Tabel Detail Item (Isi Keranjang)
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            
            $table->integer('qty');
            // Kita simpan harga SAAT TRANSAKSI terjadi. 
            // Jadi kalau besok harga produk naik, data laporan lama tidak ikut berubah (PENTING!).
            $table->decimal('base_price', 15, 2); // Harga Modal (HPP)
            $table->decimal('sell_price', 15, 2); // Harga Jual
            $table->decimal('subtotal', 15, 2); // qty * sell_price
            $table->decimal('profit', 15, 2); // (sell_price - base_price) * qty
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
    }
};