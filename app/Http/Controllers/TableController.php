<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurantId = Session::get('id');   
        $table = Table::where('restaurantId',$restaurantId)->paginate(5);
        return view('tables.tableindex',compact('table'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $restaurantId = Session::get('id');
        $restaurant = Restaurant::find($restaurantId);
        return view('tables.tablecreate',compact('restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{ $validatedData = $request->validate([
                'tableNumber' => 'required|numeric',
                'capacity' => 'required|numeric',
            ]);
            $restaurantId = Session::get('id');
            $data = new Table();
            $data->restaurantid = $restaurantId;
            $data->tableNumber = $request->tableNumber;
            $data->capacity = $request->capacity;
            // $data->status = $request->status;
            $data->save();

            return response()->json([
                'status' => true,
                'message' => 'Table Successfully Saved!...',
                'data' => $data,
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ]);
        }

    }

    public function trashtable(){

        $restaurantId = Session::get('id');
        $table = Table::where('restaurantId', $restaurantId)
            ->onlyTrashed()
            ->where('status', 'Inactive')
            ->paginate(5);
    
        return view('tables.trashtable', compact('table'))
            ->with('i', (request()->input('page', 1) - 1) * 5);

    }
    public function restore(string $id){
        $table = Table::onlyTrashed()->find($id);

        if ($table) {
            $table->restore();

            $table->status = "Active";
            $table->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Table restored successfully!',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Table not found or already restored.',
            ]);
        }
    }

    public function forcedelete(string $id){
        $table = Table::withTrashed()->findOrFail($id);
        $table->forceDelete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Table permanently deleted!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $table = Table::all()->find($id);
        return view('tables.tableview',compact('table'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $table = Table::with('restaurant')->find($id);
        return view('tables.tableedit',compact('table'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{ $validatedData = $request->validate([
            'tableNumber' => 'required|numeric',
            'capacity' => 'required|numeric',
           ]);

            $data = Table::find($id);
            $data->tableNumber = $request->tableNumber;
            $data->capacity = $request->capacity;
            // $data->status = $request->status;
            $data->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Updated successfully!',
                'data' => $data,
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Table::find($id);
        $data->status = "Inactive";
        $data->save();
        $data->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Deleted Successfully!',
            'data' => $data,
        ]);

    }
}
