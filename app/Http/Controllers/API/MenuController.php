<?php

namespace App\Http\Controllers\API;

use App\Models\Menu;
use App\Helpers\Util;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getmenu(Request $request)
    {
        $restaurant =  $request->get('restaurant');
        $categories = Category::where('restaurantId', $restaurant->id)->get();

        $menurestaurant = Menu::with('category')->where('restaurantId', $restaurant->id)->where('status','Active');

        if(isset($request->categoryId)){
            $menurestaurant = $menurestaurant->where('categoryId', $request->categoryId)->where('status','Active');
        }
        if(isset($request->title))
        {
            $menurestaurant = $menurestaurant->where('title','like','%' . $request->title . '%');
        }
        if(isset($request->price)){
            $menurestaurant = $menurestaurant->where('price','like','%' . $request->price . '%');

        }
        $menu = $menurestaurant->get();
    
        return Util::getResponse($menu); 

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        try{ $validatedData = $request->validate([
            'categoryid' => 'required',
            'title' => 'required',
            'price'=>  'required|numeric',
            ]);
            $restaurant =  $request->get('restaurant');
            $data = new Menu();
            $data->restaurantid = $restaurant->id;
            $data->categoryid = $request->categoryid;
            $data->title = $request->title;
            $data->price = $request->price;
            // $data->status = $request->status;
            
            if($image = $request->file('photo')){
                $path = 'menuImage/';
                $imagename = time(). "." . $image->getClientOriginalExtension();
                $image->move($path,$imagename);
                $data->photo = $imagename;
            }
            $data->save();
            return Util::postResponse($data,'Menu added successfully'); 

        }catch(\Throwable $th){
            Util::getErrorResponse($th);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
