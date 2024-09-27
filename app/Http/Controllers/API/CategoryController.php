<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Member;
use App\Models\Menu;
use App\Models\OrderDetail;
use App\Models\OrderMaster;
use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        try {
            $tokenData = $request->header('token');
            $restaurant = Restaurant::where('token', $tokenData)->first();
            $restaurantId = $restaurant->id;
            $categories = Category::where('restaurantId', $restaurantId)->where('status', '!=', 'Deleted')->get();
            return Util::getResponse($categories);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }
    public function getCategoryById(Request $request, $id)
    {
        try {
            $tokenData = $request->header('token');
            $restaurant = Restaurant::where('token', $tokenData)->first();
            $restaurantId = $restaurant->id;
            $categories = Category::where('restaurantId', $restaurantId)
                ->where('id', $id)
                ->where('status', '!=', 'Deleted')->get();
            return Util::getResponse($categories);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }
    public function getTrashCategories(Request $request)
    {
        try {
            $tokenData = $request->header('token');
            $restaurant = Restaurant::where('token', $tokenData)->first();
            $restaurantId = $restaurant->id;
            $categories = Category::where('restaurantId', $restaurantId)->where('status', '=', 'Deleted')->get();
            return Util::getResponse($categories);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function restoreDeletedCategory(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;

        try {
            $category = Category::where('id', $id)->where('restaurantId', $restaurantId)
                ->where('status', '=', 'Deleted')->first();
            if (!$category) {
                return Util::getErrorResponse("Category not found");
            }
            $category->status = 'Active';
            $category->save();
            return Util::getResponse($category);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function permanentDeleteCategory(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;

        try {
            $category = Category::where('id', $id)->where('restaurantId', $restaurantId)
                ->where('status', '=', 'Deleted')->first();
            if (!$category) {
                return Util::getErrorResponse("Category not found");
            }
            $category->delete();
            return Util::getResponse($category);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function addCategories(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        $rules = [
            'title' => 'required',
            'photo' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['status' => false, 'message' => $firstError], 200);
        }

        try {
            $category = new Category();
            $category->title = $request->title;
            $category->photo = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('categoryPhoto'), $category->photo);
            $category->restaurantId = $restaurantId;
            $category->save();
            return Util::postResponse($category, "categoryPhoto/" . $category->photo);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function editCategories(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;
        try {
            $category = Category::where('id', $id)->where('restaurantId', $restaurantId)->first();
            if (!$category) {
                return Util::getErrorResponse("Category not found");
            }
            $category->title = $request->title;
            if ($request->photo) {
                $category->photo = time() . '.' . $request->photo->extension();
                $request->photo->move(public_path('categoryPhoto'), $category->photo);
            }
            $category->save();
            return Util::postResponse($category, "categoryPhoto/" . $category->photo);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }

        // try {
        //     $category = Category::find($id);
        //     if (!$category) {
        //         return Util::getErrorResponse("Category not found");
        //     }
        //     $category->title = $request->title;
        //     if ($request->photo) {
        //         $category->photo = time() . '.' . $request->photo->extension();
        //         $request->photo->move(public_path('categoryPhoto'), $category->photo);
        //     }
        //     $category->save();
        //     return Util::postResponse($category, "categoryPhoto/" . $category->photo);
        // } catch (\Throwable $th) {
        //     Util::getErrorResponse($th);
        // }
    }

    public function deleteCategories(Request $request, $id)
    {
        $tokenData = $request->header('token');
        $restaurant = Restaurant::where('token', $tokenData)->first();
        $restaurantId = $restaurant->id;

        try {
            $category = Category::where('id', $id)->where('restaurantId', $restaurantId)->first();
            if (!$category) {
                return Util::getErrorResponse("Category not found");
            }
            $category->status = 'Deleted';
            $category->save();
            return Util::getResponse($category);
        } catch (\Throwable $th) {
            Util::getErrorResponse($th);
        }
    }

    public function addPassword()
    {
        $restaurants = Restaurant::all();
        foreach ($restaurants as $restaurant) {
            $restaurant->password = Hash::make('123456');
            $restaurant->save();
        }
    }



    public function billCreate(Request $request)
    {
        $tokenData = $request->header('token');
        $restaurant = Member::where('token', $tokenData)->first();

        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found.'], 404);
        }

        $restaurantId = $restaurant->restaurantId;

        $table = Table::where('restaurantId', $restaurantId)
            ->whereHas('getOrders')
            ->with('getOrders.orderDetails.menu')->get([
                'id',
                'tableNumber'
            ]);

        return Util::getResponse($table);
    }
}
