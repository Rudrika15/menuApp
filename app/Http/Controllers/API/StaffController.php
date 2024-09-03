<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class StaffController extends Controller
{


    public function getStaffs(Request $request)
    {
        $restaurant =  $request->get('restaurant'); 
        try {
        $staffs = Staff::where('restaurantId',$restaurant->id)->get();
        return Util::getResponse($staffs);
        }catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }

    }

    public function store(Request $request)
    {
        try{ $validatedData = $request->validate([
            'name' => 'required',
            'contactNumber' => 'required|digits:10|numeric|regex:/^[6-9]\d{9}$/',
            'email' => 'required|email|unique:staff,email',
            'password' => 'required|min:6',
            'staffType' => 'required',
        ]);
        $restaurant =  $request->get('restaurant'); 
        $data = new Staff();
        $data->restaurantid = $restaurant->id;
        $data->name = $request->name;
        $data->contactNumber = $request->contactNumber;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->staffType = $request->staffType;
        // $data->status = $request->status;
        $data->save();
        return Util::postResponse($data, 'Staff added successfully');

        }catch (\Throwable $th) {
            return Util::getErrorResponse($th);
        }

    }
}
