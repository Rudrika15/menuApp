<?php

namespace App\Http\Controllers;

use App\Helpers\Util;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Http\Request;

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
        $menu = $menu->where('status', '!=', 'Deleted')->get();
        return Util::getResponse($menu);
    }

    public function categoryList(Request $request)
    {
        $tokenData = $request->header('token');
        $member = Member::where('token', $tokenData)->first();
        $restaurantId = $member->restaurantId;
        $category = Menu::where('restaurantId', $restaurantId);
        if ($request->search) {
            $category = $category->where('title', 'like', '%' . $request->search . '%');
            
        }
        $category = $category->where('status', '!=', 'Deleted')->get();
        return Util::getResponse($category);
    }
}
