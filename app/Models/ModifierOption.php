<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModifierOption extends Model
{
    // Pastikan fillable-nya ada
    protected $fillable = ['modifier_id', 'name', 'price'];

    public function modifier()
    {
        return $this->belongsTo(Modifier::class);
    }
}