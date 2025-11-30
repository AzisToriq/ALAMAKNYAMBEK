<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // SOLUSI: Mengizinkan mass assignment untuk semua kolom kecuali 'id'
    protected $guarded = ['id'];
}