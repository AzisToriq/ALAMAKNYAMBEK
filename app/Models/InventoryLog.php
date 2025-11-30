<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    // SOLUSI ERROR: Izinkan semua kolom diisi (kecuali ID)
    protected $guarded = ['id'];

    // Relasi ke Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke User (Siapa yang input log ini)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}