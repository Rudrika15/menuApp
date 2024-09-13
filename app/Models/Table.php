<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public function restaurant(){
        return $this->belongsTo(Restaurant::class,'restaurantId','id');
    }

    public function oredermaster(){
        return $this->hasMany(OrderMaster::class,'tableId','id');
    }

    public function addtocart(){
        return $this->hasMany(AddToCart::class,'tableId','id');
    }

    // protected $appends = ['order_detail'];

    // public function getOrderDetailAttribute(){
    //     return  OrderMaster::where('tableId',$this->attributes['id'])->with('orderdetail')->get();
    // }
}
