<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyRestaurantToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('token');

        if (!$token) {
            return response()->json(['status' => false, 'message' => 'Token not provided'], 200);
        }

        $restaurant = Restaurant::where('token', $token)->first();

        if (!$restaurant) {
            return response()->json(['status' => false, 'message' => 'Invalid token'], 200);
        }
        $request->attributes->set('restaurant', $restaurant);

        return $next($request);
    }
}
