<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [

        'restaurantId',
        'name',
        'contactNumber',
        'email',
        'password',
        'staffType',
        'status',
    ];
    public function restaurant(){
        return $this->belongsTo(Restaurant::class,'restaurantId','id');
    }

}
