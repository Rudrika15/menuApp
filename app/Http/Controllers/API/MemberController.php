<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Restaurant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class MemberController extends Controller
{
    public function addStaffs(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        try {
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:members,email',
                'password' => 'required',
                'contactNumber' => 'required',
                'staffType' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $firstError = $errors->first();

                return response()->json(['status' => false, 'message' => $firstError], 200);
            }

            $member = new Member();
            $member->restaurantId = $restaurantId;
            $member->name = $request->name;
            $member->email = $request->email;
            $member->password = Hash::make($request->password);
            $member->contactNumber = $request->contactNumber;
            $member->staffType = $request->staffType;
            $member->token = Util::generateToken();
            $member->save();
            return Util::postResponse($member, 'Staff added successfully');
        } catch (\Throwable $th) {
            return Util::getErrorResponse($th);
        }
    }
    public function getStaffs(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        try {
            $staffs = Member::where('restaurantId', $restaurantId)->where('status', '!=', 'Deleted')->get();
            return Util::getResponse($staffs);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }
    public function staffLogin(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $validator =  Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['status' => false, 'message' => $firstError], 200);
        }

        $member = Member::where('email', $request->email)->first();
        if (!$member) {
            return response()->json(['status' => false, 'message' => 'User not found'], 200);
        }

        if (!Hash::check($request->password, $member->password)) {
            return response()->json(['status' => false, 'message' => 'Incorrect password'], 200);
        }

        $member->token = Util::generateToken();
        $member->save();
        return Util::getResponse($member);
    }

    public function editStaff(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'contactNumber' => 'required',
        ];

        $validator =  Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['status' => false, 'message' => $firstError], 200);
        }

        try {
            $member = Member::where('id', $id)->where('restaurantId', $restaurantId)->first();
            if (!$member) {
                return response()->json(['status' => false, 'message' => 'User not found'], 200);
            }

            $member->name = $request->name;
            $member->email = $request->email;
            if ($request->password) {
                $member->password = Hash::make($request->password);
            }
            $member->contactNumber = $request->contactNumber;
            $member->staffType = $request->staffType;
            $member->save();
            return Util::postResponse($member, 'Staff updated successfully');
        } catch (\Throwable $th) {
            return Util::getErrorResponse($th);
        }
    }

    public function deleteStaff(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        try {
            $member = Member::where('id', $id)->where('restaurantId', $restaurantId)->first();
            if (!$member) {
                return response()->json(['status' => false, 'message' => 'User not found'], 200);
            }
            $member->status = 'Deleted';
            $member->save();
            return Util::getResponse($member, 'Staff deleted successfully');
        } catch (\Throwable $th) {
            return Util::getErrorResponse($th);
        }
    }
}
