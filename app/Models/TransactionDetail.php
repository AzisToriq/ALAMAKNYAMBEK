<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    
    // KUNCI UTAMA: Membuka akses agar kolom profit & base_price bisa diisi/diupdate
    protected $guarded = ['id'];

    // Casting JSON untuk modifiers_data agar otomatis jadi Array saat diambil
    protected $casts = [
        'modifiers_data' => 'array',
    ];

    // Relasi ke Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}