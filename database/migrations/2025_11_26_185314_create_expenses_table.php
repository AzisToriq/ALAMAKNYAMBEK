<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Siapa yang input
        $table->string('name'); // Cth: Bayar Listrik Bulan Nov
        $table->decimal('amount', 15, 2); // Nominal
        $table->date('date'); // Tanggal pengeluaran
        $table->text('note')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
