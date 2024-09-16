<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCart extends Model
{
    use HasFactory;
    public function getMenu()
    {
        return $this->hasMany(Menu::class, 'id', 'menuId');
    }
}
