<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{

    public function login()
    {
        // Validation rules for the request
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        // Validating the request against the rules
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['status' => false, 'message' => $firstError], 200);
        }

        // Finding the restaurant by email
        $restaurant = Restaurant::where('email', request('email'))->first();
        if (!$restaurant) {
            return response()->json(['status' => false, 'message' => 'User not found'], 200);
        }

        // Checking if the password is correct
        if (!Hash::check(request('password'), $restaurant->password)) {
            return response()->json(['status' => false, 'message' => 'Incorrect password'], 200);
        }

        // Generating a new token and saving it to the restaurant
        $restaurant->token = Util::generateToken();
        $restaurant->save();

        // Returning the login response with the necessary fields
        return Util::loginResponse($restaurant->only([
            'id',
            'name',
            'email',
            'gstNumber',
            'upi',
            'logo',
            'color1',
            'color2',
            'address',
            'token'
        ]));
    }

    public function restaurantRegistration(Request $request)
    {

        try {
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:restaurants,email',
                'password' => 'required|min:6',
                'gstNumber' => 'required',
                'upi' => 'required',
                'logo' => 'required',
                'color1' => 'required',
                'color2' => 'required',
                'address' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $firstError = $errors->first();

                return response()->json(['status' => false, 'message' => $firstError], 200);
            }


            $restaurant = new Restaurant();
            $restaurant->name = $request->name;
            $restaurant->email = $request->email;
            $restaurant->gstNumber = $request->gstNumber;
            $restaurant->upi = $request->upi;
            $restaurant->logo = \time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('restaurantLogo'), $restaurant->logo);
            $restaurant->color1 = $request->color1;
            $restaurant->color2 = $request->color2;
            $restaurant->address = $request->address;
            $restaurant->status = 'Active';
            $restaurant->save();


            $member  = new Member();
            $member->restaurantId = $restaurant->id;
            $member->name = $request->name;
            $member->password = Hash::make($request->password);
            $member->contactNumber = $request->contactNumber;
            $member->email = $request->email;
            $member->token = Util::generateToken();
            $member->staffType = 'Admin';
            $member->save();


            return Util::postResponse($restaurant, "restaurantLogo/" . $restaurant->logo);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function getRestaurants(Request $request)
    {

        try {
            $restaurants = Restaurant::all();
            return Util::getResponse($restaurants);
        } catch (\Throwable $th) {
            return Util::getErrorResponse($th);
        }
    }

    public function getRestaurantById($id)
    {
        try {
            $restaurant = Restaurant::find($id);
            return Util::getResponse($restaurant);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }
}
