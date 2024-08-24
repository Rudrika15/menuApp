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

            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
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
            $staffs = Member::where('restaurantId', $restaurantId)->get();
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
            return $validator->errors();
        }

        $member = Member::where('email', $request->email)->first();
        if (!$member) {
            return response()->json(['status' => 'failed', 'message' => 'User not found'], 404);
        }

        if (!Hash::check($request->password, $member->password)) {
            return response()->json(['status' => 'failed', 'message' => 'Incorrect password'], 404);
        }

        $member->token = Util::generateToken();
        $member->save();
        return Util::getResponse($member);
    }
}
