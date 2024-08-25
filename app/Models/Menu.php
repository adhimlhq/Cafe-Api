<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'is_recommendation', 'cafe_id'];

    // Relasi banyak ke satu dengan cafe
    public function cafe()
    {
        return $this->belongsTo(Cafe::class, 'cafe_id');
    }
}
