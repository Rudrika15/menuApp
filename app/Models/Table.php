<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    public function getOrderItem()
    {
        return $this->hasMany(AddToCart::class, 'tableId', 'id');
    }

    public function getOrders()
    {
        return $this->hasMany(OrderMaster::class, 'tableId', 'id');
    }
    
}
