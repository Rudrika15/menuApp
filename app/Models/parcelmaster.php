<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class parcelmaster extends Model
{
    use HasFactory;
    public function parceldetail()
    {
        return $this->hasMany(parceldetail::class, 'parcelmasterId');
    }
}