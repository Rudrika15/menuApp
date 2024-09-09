<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{

    public function getTables(Request $request)
    {
        $tokenData = $request->header('token');
        $search = $request->search;
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        try {
            $tables = Table::where('restaurantId', $restaurantId)->where('status', '!=', 'Deleted');
            if ($search) {
                $tables = $tables->where('tableNumber', 'like', '%' . $search . '%');
            }

            $tables = $tables->get();
            return Util::getResponse($tables);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function getTableById(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $search = $request->search;
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        try {
            $table = Table::where('id', $id)
                ->where('restaurantId', $restaurantId)
                ->where('status', '!=', 'Deleted')->first();
            return Util::getResponse($table);
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
                $errors = $validator->errors();
                $firstError = $errors->first();

                return response()->json(['status' => false, 'message' => $firstError], 200);
            }

            $table = new Table();
            $table->restaurantId = $restaurantId;
            $table->tableNumber = $request->tableNumber;
            $table->capacity = $request->capacity;
            $table->save();


            return Util::postResponse($table, 'Table added successfully');
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function editTable(Request $request, $id)
    {
        try {

            $rules = [
                'tableNumber' => 'required',
                'capacity' => 'required|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $firstError = $errors->first();

                return response()->json(['status' => false, 'message' => $firstError], 200);
            }


            $table = Table::find($id);
            if (!$table) {
                return response()->json(['status' => false, 'message' => 'Table not found'], 200);
            }
            $table->tableNumber = $request->tableNumber;
            $table->capacity = $request->capacity;
            $table->save();
            return Util::postResponse($table, 'Table updated successfully');
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function deleteTable($id)
    {
        try {
            $table = Table::find($id);
            if (!$table) {
                return response()->json(['status' => false, 'message' => 'Table not found'], 200);
            }
            $table->status = 'Deleted';
            $table->save();
            return Util::getResponse($table, 'Table deleted successfully');
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function getTrashTable(Request $request)
    {
        try {
            $tokenData = $request->header('token');
            $restaurant = Restaurant::where('token', $tokenData)->first();
            $restaurantId = $restaurant->id;
            $category = Table::where('restaurantId', $restaurantId)->where('status', '=', 'Deleted')->get();
            return Util::getResponse($category);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function restoreDeletedTable(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;

        try {
            $table = Table::where('id', $id)->where('restaurantId', $restaurantId)
                ->where('status', '=', 'Deleted')->first();
            if (!$table) {
                return Util::getErrorResponse("table not found");
            }
            $table->status = 'Available';
            $table->save();
            return Util::getResponse($table);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }
    public function permanentDeleteTable(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;

        try {
            $table = Table::where('id', $id)->where('restaurantId', $restaurantId)
                ->where('status', '=', 'Deleted')->first();
            if (!$table) {
                return Util::getErrorResponse("table not found");
            }
            $table->delete();
            return Util::getResponse($table);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }
}
