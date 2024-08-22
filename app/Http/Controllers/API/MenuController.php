<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function __construct()
    {
// 
    }

    public function getMenuList(Request $request)
    {
        $tokenData = $request->header('token');
          $restaurant = Restaurant::where('token', $tokenData)->first();
          $restaurantId = $restaurant->id;
        $menu = Menu::where('restaurantId', $restaurantId)->get();
        return Util::getResponse($menu);
    }
}
