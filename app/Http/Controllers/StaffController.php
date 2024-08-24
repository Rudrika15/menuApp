<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurantId = Session::get('id');   
        $staff = Staff::where('restaurantId',$restaurantId)->paginate(5);
        return view('staff.staffindex',compact('staff'))
        ->with('i', (request()->input('page', 1) - 1) * 5);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $restaurantId = Session::get('id');
        $restaurant = Restaurant::find($restaurantId);
        return view('staff.staffcreate',compact('restaurant','restaurantId'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{ $validatedData = $request->validate([
                'restaurantid' => 'required',
                'name' => 'required',
                'contactNumber' => 'required|digits:10|numeric|regex:/^[6-9]\d{9}$/',
                'email' => 'required|email|unique:staff,email',
                'password' => 'required|min:6',
                'staffType' => 'required',
            ]);
        
            $data = new Staff();
            $data->restaurantid = $request->restaurantid;
            $data->name = $request->name;
            $data->contactNumber = $request->contactNumber;
            $data->email = $request->email;
            $data->password = bcrypt($request->password);
            $data->staffType = $request->staffType;
            $data->status = $request->status;
            $data->save();
        
            return response()->json([
                'status' => true,
                'message' => 'Staff Successfully Saved!...',
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = Staff::all()->find($id);
        return view('staff.staffview',compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = Staff::with('restaurant')->find($id);
        return view('staff.staffedit',compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try{ $validatedData = $request->validate([
            'name' => 'required',
            'contactNumber' => 'required|digits:10|numeric|regex:/^[6-9]\d{9}$/',
            'email' => 'required|email|unique:staff,email,'.$request->id,
            'staffType' => 'required',
            ]);
        

            $data  = Staff::find($id);
            $data->name = $request->name;
            $data->contactNumber = $request->contactNumber;
            $data->email = $request->email;
            $data->staffType = $request->staffType;
            $data->status = $request->status;
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
        $data = Staff::find($id);        
        $data->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Deleted Successfully!',
            'data' => $data,
        ]);
    }

}