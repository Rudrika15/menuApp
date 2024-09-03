<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class TableController extends Controller
{

    public function getTables(Request $request)
    {
        $restaurant =  $request->get('restaurant'); 
        try {
        $tables = Table::where('restaurantId',$restaurant->id)->where('status','Active')->get();
        return Util::getResponse($tables);
        }catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }

    }

    public function store(Request $request)
    {
    
        try{ $validatedData = $request->validate([
            'tableNumber' => 'required|numeric',
            'capacity' => 'required|numeric',
        ]);
        $restaurant =  $request->get('restaurant'); 
        $data = new Table();
        $data->restaurantid = $restaurant->id;
        $data->tableNumber = $request->tableNumber;
        $data->capacity = $request->capacity;
        // $data->status = $request->status;
        $data->save();
        return Util::postResponse($data, 'Table added successfully');

        }catch(\Throwable $th){
            Util::getErrorResponse($th);
        }
    }
}
