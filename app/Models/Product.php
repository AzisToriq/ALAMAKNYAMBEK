<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // --- TAMBAHKAN INI (YANG HILANG) ---
    // Relasi ke Unit (Satuan, misal: Pcs, Kg, Box)
    public function unit()
    {
        // Pastikan kamu punya model Unit (App\Models\Unit)
        return $this->belongsTo(Unit::class);
    }

    // Relasi ke Modifier (yang tadi kita buat)
    public function modifiers()
    {
        return $this->belongsToMany(Modifier::class, 'product_modifier')
                    ->withPivot('order')
                    ->with('options');
    }
}