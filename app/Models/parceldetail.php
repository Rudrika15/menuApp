<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class parceldetail extends Model
{
    use HasFactory;
    //chnage table name
    protected $table = 'parceldetail';

    //get menu form menuId
    public function getMenu()
    {
        return $this->belongsTo(Menu::class, 'menuId');
    }
}
