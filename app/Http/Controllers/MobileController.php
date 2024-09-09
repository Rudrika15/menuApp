<?php

namespace App\Http\Controllers;

use App\Helpers\Util;
use App\Models\AddToCart;
use App\Models\Category;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MobileController extends Controller
{
    public function tableList(Request $request)
    {
        $tokenData = $request->header('token');
        $search = $request->search;
        $member = Member::where('token', $tokenData)->first();
        $restaurantId = $member->restaurantId;

        try {
            $tables = Table::where('restaurantId', $restaurantId);
            if ($search) {
                $tables = $tables->where('tableNumber', 'like', '%' . $search . '%');
            }

            $tables = $tables->where('status', '!=', 'Deleted')->get();
            return Util::getResponse($tables);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function menuList(Request $request)
    {
        $tokenData = $request->header('token');
        $member = Member::where('token', $tokenData)->first();

        $restaurantId = $member->restaurantId;

        $menu = Menu::where('restaurantId', $restaurantId);
        if ($request->search) {
            $menu = $menu->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->category) {
            $menu = $menu->where('categoryId', $request->category);
        }
        $menu = $menu->where('status', '!=', 'Deleted')->get();
        // return Util::getResponse($menu);

    }

    public function categoryList(Request $request)
    {
        $tokenData = $request->header('token');
        $member = Member::where('token', $tokenData)->first();
        $restaurantId = $member->restaurantId;
        $category = Category::where('restaurantId', $restaurantId)->where('status', '!=', 'Deleted')->get();
        return Util::getResponse($category);
    }

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.p_id' => 'required|exists:menus,id',
            '*.qty' => 'required|integer|min:1',
            '*.table' => 'required|exists:tables,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cartItems = $request->all();
        $addedItems = [];

        foreach ($cartItems as $item) {
            $addToCart = new AddToCart();
            $addToCart->menuId = $item['p_id'];
            $addToCart->tableId = $item['table'];
            $addToCart->quantity = $item['qty'];
            $addToCart->save();

            $addedItems[] = $addToCart; 
        }

        return Util::getResponse($addedItems);
    }
}
