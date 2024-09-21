<?php

namespace App\Http\Controllers;

use App\Events\ItemAddedToCart;
use App\Helpers\Util;
use App\Models\AddToCart;
use App\Models\Category;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
    public function changeTableStatus(Request $request)
    {
        $request->validate([
            'table_ids' => 'required|array|min:1',

        ]);

        $tableIds = $request->input('table_ids');

        $minTableId = min($tableIds);

        Table::where('id', $minTableId)->update(['status' => 'Booked']);

        Table::whereIn('id', $tableIds)
            ->where('id', '!=', $minTableId)
            ->update(['status' => 'merge', 'mergeWith' => $minTableId]);



        return response()->json([
            'message' => 'Table statuses updated successfully',
            'min_table_id' => $minTableId,
        ]);
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
        return Util::getResponse($menu);
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
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            '*.p_id' => 'required|exists:menus,id',
            '*.qty' => 'required|integer|min:1',
            '*.table' => 'required|exists:tables,id',
        ]);

        // Return validation errors if they exist
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Retrieve the member based on the provided token
        $token = $request->header('token');
        $member = Member::where('token', $token)->first();
        $restaurantId = $member->restaurantId;

        $cartItems = $request->all();
        $addedItems = [];

        // Loop through each item and add to the cart
        foreach ($cartItems as $item) {
            $addToCart = new AddToCart();
            $addToCart->menuId = $item['p_id'];
            $addToCart->tableId = $item['table'];
            $addToCart->qty = $item['qty'];
            $addToCart->restaurantId = $restaurantId;
            $addToCart->save();

            $addedItems[] = $addToCart;
        }

        // Log added items for debugging
        Log::info('Items added to cart:', [
            'addedItems' => $addedItems,
            'restaurantId' => $restaurantId,
        ]);

        // Trigger the event
        event(new ItemAddedToCart($addedItems));

        // Log event broadcast confirmation
        Log::info('Event ItemAddedToCart broadcasted', [
            'addedItemsCount' => count($addedItems),
        ]);

        return Util::getResponse('Item added');
    }

    public function viewCart(Request $request)
    {
        $tableId = $request->table_id;
        $token = $request->header('token');
        $member = Member::where('token', $token)->first();
        $restaurantId = $member->restaurantId;

        // Fetch cart data
        $cartData = DB::table('menus')
            ->crossJoin('add_to_carts')
            ->select('menus.title', 'menus.price', 'menus.photo', 'add_to_carts.qty', 'add_to_carts.status', 'add_to_carts.id as cartId', DB::raw('menus.price * add_to_carts.qty as total'))
            ->where('add_to_carts.menuId', '=', DB::raw('menus.id'))
            ->where('add_to_carts.restaurantId', '=', $restaurantId)
            ->where('add_to_carts.tableId', '=', $tableId)
            ->get();

        // Calculate grand total
        $grandTotal = $cartData->sum('total');

        // Return response with cart data and grand total
        return Util::getResponse([
            'cartData' => $cartData,
            'grandTotal' => $grandTotal
        ]);
    }

    public function deleteCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:add_to_carts,id',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $token = $request->header('token');
        $member = Member::where('token', $token)->first();
        $restaurantId = $member->restaurantId;
        $data = AddToCart::where('id', $request->id)->where('restaurantId', $restaurantId)->delete();

        return Util::getResponse($data);
    }
    public function changeCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:add_to_carts,id',
            'qty' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $token = $request->header('token');
        $member = Member::where('token', $token)->first();
        $restaurantId = $member->restaurantId;
        $data = AddToCart::where('id', $request->id)->where('restaurantId', $restaurantId)->update(['qty' => $request->qty]);
        return Util::getResponse($data);
    }
    public function orderView(Request $request)
    {

        $token = $request->header('token');
        $member = Member::where('token', $token)->first();
        $restaurantId = $member->restaurantId;

        $cartData1 = DB::table('add_to_carts')
            ->select(
                'tables.tableNumber',
                'add_to_carts.tableId',
                DB::raw('COUNT(add_to_carts.id) as total_orders'),
                DB::raw('SUM(add_to_carts.qty) as qty'),
                'add_to_carts.status',
                'menus.title'
            )
            ->join('menus', 'add_to_carts.menuId', '=', 'menus.id')
            ->join('tables', 'add_to_carts.tableId', '=', 'tables.id')
            ->where('add_to_carts.status', '=', 'Pending')
            ->groupBy('tables.tableNumber', 'add_to_carts.tableId', 'add_to_carts.status', 'menus.title')
            ->orderBy('add_to_carts.tableId', 'asc')
            ->get();

        // Transform data into the required nested structure
        $responseData = [];
        foreach ($cartData1 as $cart) {
            // Group by tableId
            $tableId = $cart->tableId;

            // Initialize table if not exists
            if (!isset($responseData[$tableId])) {
                $responseData[$tableId] = [
                    "tableNumber" => $cart->tableNumber,
                    "tableId" => $tableId,
                    "getMenu" => []
                ];
            }

            // Add menu details to 'getMenu'
            $responseData[$tableId]['getMenu'][] = [
                'total_orders' => $cart->total_orders,
                'qty' => $cart->qty,
                'status' => $cart->status,
                'title' => $cart->title
            ];
        }

        if ($request->has('summary')) {
            $cartData = DB::table('menus')
                ->join('add_to_carts', 'menus.id', '=', 'add_to_carts.menuId')
                ->select('menus.title', 'menus.price', 'menus.photo', DB::raw('SUM(add_to_carts.qty) as qty'))
                ->where('add_to_carts.restaurantId', '=', $restaurantId)
                ->groupBy('menus.id', 'menus.title', 'menus.price', 'menus.photo')
                ->get();
            return Util::getResponse([
                'cartData' => $cartData,
            ]);
        }

        return Util::getResponse([
            'cartData' => array_values($responseData)
        ]);
    }

    public function updateTableStatus(Request $request)
    {

        $tableId = $request->tableId;
        $productId = $request->productId;

        if ($tableId == true && $productId == true) {
            $cartData = AddToCart::where('tableId', $tableId)
                ->where('menuId', $productId)->get();
            foreach ($cartData as $cart) {
                $cart->status = 'Done';
                $cart->save();
            }
        }


        if ($productId == true) {
            $cartData = AddToCart::where('menuId', $productId)->get();
            foreach ($cartData as $cart) {
                $cart->status = 'Done';
                $cart->save();
            }
        }

        return Util::getErrorResponse('Status updated successfully');
    }
}