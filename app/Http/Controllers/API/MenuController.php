<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{


    public function menuList(Request $request)
    {
        $tokenData = $request->header('token');
        $member = Member::where('token', $tokenData)->first();

        $restaurantId = $member->restaurantId;

        $menu = Menu::where('restaurantId', $restaurantId);
        if ($request->search) {
            $menu = $menu->where('title', 'like', '%' . $request->search . '%');
        }
        $menu = $menu->get();
        return Util::getResponse($menu);
    }

    public function getMenus(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();

        $restaurantId = $restaurant->restaurantId;
        $menu = Menu::where('restaurantId', $restaurantId);
        if ($request->search) {
            $menu = $menu->where('title', 'like', '%' . $request->search . '%');
        }
        $menu = $menu->get();
        return Util::getResponse($menu);
    }

    public function addMenu(Request $request)
    {
        $tokenData = $request->header('token');

        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->restaurantId;

        $rules = [
            'categoryId' => 'required',
            'title' => 'required',
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        }

        $menu = new Menu();
        $menu->restaurantId = $restaurantId;
        $menu->categoryId = $request->categoryId;
        $menu->title = $request->title;
        $menu->price = $request->price;
        if ($request->photo) {
            $menu->photo = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('menuPhoto'), $menu->photo);
        }
        $menu->save();
        return Util::getResponse($menu);
    }
}
