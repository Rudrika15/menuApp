<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{

    public function getTables(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        try {
            $tables = Table::where('restaurantId', $restaurantId)->get();
            return Util::getResponse($tables);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function addTables(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;

        try {
            $rules = [
                'tableNumber' => 'required',
                'capacity' => 'required|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            }

            $table = new Table();
            $table->restaurantId = $restaurantId;
            $table->tableNumber = $request->tableNumber;
            $table->capacity = $request->capacity;
            $table->save();


            return Util::postResponse($table, 'Table added successfully');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
