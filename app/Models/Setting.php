<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // SOLUSI: Gunakan $guarded = ['id']
    // Artinya: "Lindungi kolom ID saja, sisanya BOLEH diedit."
    // Ini otomatis mengizinkan kolom-kolom baru yang kita buat tadi.
    protected $guarded = ['id'];
    protected $fillable = [
    'shop_name',
    'shop_address',
    'shop_phone',
    'enable_table_number', // Pastikan ini ada
];
}