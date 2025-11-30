<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->string('table_number')->nullable()->after('user_id'); // No Meja
        $table->decimal('tax_amount', 15, 2)->default(0)->after('total_price'); // Nominal Pajak
        $table->decimal('grand_total', 15, 2)->default(0)->after('tax_amount'); // Total + Pajak
    });
}
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
