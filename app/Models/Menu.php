<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public function restaurant(){
        return $this->belongsTo(Restaurant::class,'restaurantId','id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'categoryId','id')->withTrashed();
    }

}
