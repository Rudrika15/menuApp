<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function __construct()
    {
        // 
    }

    public function menuList(Request $request)
    {
        $tokenData = $request->header('token');
        $member = Member::where('token', $tokenData)->first();

        $restaurantId = $member->restaurantId;

        $menu = Menu::where('restaurantId', $restaurantId);
        if($request->search) {
            $menu = $menu->where('title', 'like', '%'.$request->search.'%');
        }
        $menu = $menu->get();
        return Util::getResponse($menu);
    }
}
