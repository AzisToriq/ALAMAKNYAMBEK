<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi: 1 Transaksi punya banyak Detail Item
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Relasi: Transaksi dilakukan oleh User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}