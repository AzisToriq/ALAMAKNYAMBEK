<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->json('modifiers_data')->nullable()->after('notes');
        });
    }

    public function down()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('modifiers_data');
        });
    }
};