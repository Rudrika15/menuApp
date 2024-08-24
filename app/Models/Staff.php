<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    public function restaurant(){
        return $this->belongsTo(Restaurant::class,'restaurantId','id');
    }

}
