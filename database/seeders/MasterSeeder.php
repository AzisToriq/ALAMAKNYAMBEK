<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // Data Kategori Dummy
        $categories = [
            ['name' => 'Makanan', 'slug' => 'makanan', 'created_at' => now()],
            ['name' => 'Minuman', 'slug' => 'minuman', 'created_at' => now()],
            ['name' => 'Sembako', 'slug' => 'sembako', 'created_at' => now()],
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'created_at' => now()],
        ];
        DB::table('categories')->insert($categories);

        // Data Satuan Dummy
        $units = [
            ['name' => 'Pcs', 'short_name' => 'pcs', 'created_at' => now()],
            ['name' => 'Kilogram', 'short_name' => 'kg', 'created_at' => now()],
            ['name' => 'Pack', 'short_name' => 'pck', 'created_at' => now()],
            ['name' => 'Liter', 'short_name' => 'ltr', 'created_at' => now()],
            ['name' => 'Box', 'short_name' => 'box', 'created_at' => now()],
        ];
        DB::table('units')->insert($units);
    }
}