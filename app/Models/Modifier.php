<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Opsi (Level 1, Level 2, Keju, Coklat)
    public function options()
    {
        return $this->hasMany(ModifierOption::class);
    }

    // Relasi balik ke Produk (Opsional, tapi bagus untuk kelengkapan)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_modifier');
    }
}