<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMaster extends Model
{
    use HasFactory;

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'orderId', 'id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'tableId', 'id');
    }
}
