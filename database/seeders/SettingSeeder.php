<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       \App\Models\Setting::create([
        'shop_name' => 'Serumpoen',
        'shop_address' => 'Tembalang',
        'shop_phone' => '081326156310',
        'enable_table_number' => true,
        
        ]);
    }
}
