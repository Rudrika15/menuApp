<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function restaurant(){
        return $this->belongsTo(Restaurant::class,'restaurantId','id');
    }

    public function menu(){
        return $this->hasMany(Menu::class,'categoryId','id');
    }

}
