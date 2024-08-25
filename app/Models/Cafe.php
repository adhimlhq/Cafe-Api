<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone_number'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
