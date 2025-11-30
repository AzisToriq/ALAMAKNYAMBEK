<?php

namespace Database\Seeders;

use App\Models\Modifier;
use App\Models\ModifierOption;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ModifierSeeder extends Seeder
{
    public function run()
    {
        // Ambil produk pertama untuk testing
        $product = Product::first();
        
        if (!$product) {
            $this->command->error('Tidak ada produk ditemukan. Buat produk terlebih dahulu!');
            return;
        }

        // 1. BUAT MODIFIER: Ukuran (Required, Single Choice)
        $sizeModifier = Modifier::create([
            'name' => 'Ukuran',
            'type' => 'required',
            'is_multiple' => false
        ]);

        // Attach ke produk
        $product->modifiers()->attach($sizeModifier->id, ['order' => 1]);

        // Buat options untuk ukuran
        ModifierOption::create([
            'modifier_id' => $sizeModifier->id,
            'name' => 'Small',
            'price' => 0
        ]);

        ModifierOption::create([
            'modifier_id' => $sizeModifier->id,
            'name' => 'Medium',
            'price' => 5000
        ]);

        ModifierOption::create([
            'modifier_id' => $sizeModifier->id,
            'name' => 'Large',
            'price' => 10000
        ]);

        // 2. BUAT MODIFIER: Level Pedas (Optional, Single Choice)
        $spicyModifier = Modifier::create([
            'name' => 'Level Pedas',
            'type' => 'optional',
            'is_multiple' => false
        ]);

        $product->modifiers()->attach($spicyModifier->id, ['order' => 2]);

        ModifierOption::create([
            'modifier_id' => $spicyModifier->id,
            'name' => 'Tidak Pedas',
            'price' => 0
        ]);

        ModifierOption::create([
            'modifier_id' => $spicyModifier->id,
            'name' => 'Pedas Sedang',
            'price' => 0
        ]);

        ModifierOption::create([
            'modifier_id' => $spicyModifier->id,
            'name' => 'Pedas Banget',
            'price' => 2000
        ]);

        // 3. BUAT MODIFIER: Add-ons (Optional, Multiple Choice)
        $addonsModifier = Modifier::create([
            'name' => 'Add-ons',
            'type' => 'optional',
            'is_multiple' => true
        ]);

        $product->modifiers()->attach($addonsModifier->id, ['order' => 3]);

        ModifierOption::create([
            'modifier_id' => $addonsModifier->id,
            'name' => 'Extra Keju',
            'price' => 8000
        ]);

        ModifierOption::create([
            'modifier_id' => $addonsModifier->id,
            'name' => 'Extra Daging',
            'price' => 15000
        ]);

        ModifierOption::create([
            'modifier_id' => $addonsModifier->id,
            'name' => 'Extra Sayur',
            'price' => 5000
        ]);

        ModifierOption::create([
            'modifier_id' => $addonsModifier->id,
            'name' => 'Tambah Telor',
            'price' => 4000
        ]);

        $this->command->info("✅ Modifier berhasil dibuat untuk produk: {$product->name}");
    }
}