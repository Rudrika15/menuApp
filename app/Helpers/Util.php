<?php

namespace App\Helpers;

use App\Models\Restaurant;

class Util
{

    public static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public static function loginResponse($data)
    {
        $token = $data['token'];
        unset($data['token']);

        return response()->json(
            [
                'status' => true,
                'message' => 'Success',
                'data' => $data,
                'token' => $token
            ],
            200
        );
    }

    public static function checkTokenExists($token = null)
    {
        // Retrieve the token from the request header if not passed as an argument
        if (!$token) {
            $token = request()->header('token');
        }

        // Check if the token is provided
        if (!$token) {
            return response()->json(['status' => false, 'message' => 'Token not provided'], 401);
        }

        // Find the restaurant with the matching token
        $restaurant = Restaurant::where('token', $token)->first();

        // If no restaurant is found with the provided token, return false
        if (!$restaurant) {
            return response()->json(['status' => false, 'message' => 'Invalid token'], 401);
        }

        // Return the restaurant instance if found
        return $restaurant;
    }

    public static function authenticate()
    {
        return response()->json(['status' => false, 'message' => 'Unauthenticated'], 401);
    }




    public static function getResponse($data)
    {
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $data
        ], 200);
    }

    public static function postResponse($data, $imageUrl = null)
    {
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'imageUrl' => $imageUrl,
            'data' => $data
        ], 201);
    }

    public static function getErrorResponse($message)
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ], 400);
    }
}
