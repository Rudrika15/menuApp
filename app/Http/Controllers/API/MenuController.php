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

    public function getMenus(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();

        $restaurantId = $restaurant->id;
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
        $restaurantId = $restaurant->id;

        $rules = [
            'categoryId' => 'required',
            'title' => 'required',
            'price' => 'required',
            'photo' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['status' => false, 'message' => $firstError], 200);
        }

        $menu = new Menu();
        $menu->restaurantId = $restaurantId;
        $menu->categoryId = $request->categoryId;
        $menu->title = $request->title;
        $menu->price = $request->price;
        $menu->photo = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('menuPhoto'), $menu->photo);
        $menu->save();
        return Util::postResponse($menu, "menuPhoto/" . $menu->photo);
    }
    public function editMenu(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        $rules = [
            'categoryId' => 'required',
            'title' => 'required',
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['status' => false, 'message' => $firstError], 200);
        }
        $menu = Menu::where('id', $id)->where('restaurantId', $restaurantId)->first();
        if (!$menu) {
            return Util::getErrorResponse("Menu not found");
        }
        $menu->categoryId = $request->categoryId;
        $menu->title = $request->title;
        $menu->price = $request->price;
        if ($request->photo) {
            $menu->photo = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('menuPhoto'), $menu->photo);
        }
        $menu->save();
        return Util::postResponse($menu, "menuPhoto" . $menu->photo);
    }
    public function deleteMenu(Request $request, $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return Util::getErrorResponse("Menu not found");
        }
        $menu->status = "Deleted";
        $menu->save();
        return Util::getResponse($menu);
    }
    public function getTrashMenus(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        $menu = Menu::where('status', 'Deleted')->where('restaurantId', $restaurantId);
        if ($request->search) {
            $menu = $menu->where('title', 'like', '%' . $request->search . '%');
        }
        $menu = $menu->get();
        return Util::getResponse($menu);
    }
    public function restoreMenu(Request $request, $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return Util::getErrorResponse("Menu not found");
        }
        $menu->status = "Active";
        $menu->save();
        return Util::getResponse($menu);
    }
    public function showMenu(Request $request, $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return Util::getErrorResponse("Menu not found");
        }
        return Util::getResponse($menu);
    }
}
