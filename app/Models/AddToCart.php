<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCart extends Model
{
    use HasFactory;

    public function table(){
        return $this->belongsTo(Table::class,'tableId','id');
    }
    public function menu(){
        return $this->belongsTo(Menu::class,'menuId');
    }


}
