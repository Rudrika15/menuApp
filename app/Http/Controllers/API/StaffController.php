<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{


    public function getStaffs(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        try {
            $staffs = Staff::where('restaurantId', $restaurantId)->get();
            return Util::getResponse($staffs);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function addStaffs(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        try {
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:staffs,email',
                'password' => 'required',
                'contactNumber' => 'required',
                'staffType' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            }

            $staff = new Staff();
            $staff->restaurantId = $restaurantId;
            $staff->name = $request->name;
            $staff->email = $request->email;
            $staff->password = Hash::make($request->password);
            $staff->contactNumber = $request->contactNumber;
            $staff->staffType = $request->staffType;
            $staff->save();
            return Util::postResponse($staff, 'Staff added successfully');
        } catch (\Throwable $th) {
            return Util::getErrorResponse($th);
        }
    }
}
