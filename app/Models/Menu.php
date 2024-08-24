<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    public function restaurant(){
        return $this->belongsTo(Restaurant::class,'restaurantId','id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'categoryId','id');
    }

}
