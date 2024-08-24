<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gstNumber',
        'upi',
        'logo',
        'color1',
        'color2',
        'address',
        'token',
    ];

    protected $hidden = [
        'password',
        'token',
    ];

    public function category(){
        return $this->hasMany(Category::class,'restaurantId','id');
    }

    public function menu(){
        return $this->hasMany(Menu::class,'restaurantId','id');
    }
    
    public function staff(){
        return $this->hasMany(Staff::class,'restaurantId','id');
    }

    public function table(){
        return $this->hasMany(Table::class,'restaurantId','id');
    }

}
