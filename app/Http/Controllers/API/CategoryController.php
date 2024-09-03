<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getcategory(Request $request)
    {
        $restaurant =  $request->get('restaurant');
        $category = Category::where('restaurantId',$restaurant->id)->where('status','Active')->get();
        return Util::getResponse($category); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required',
            ]);
            $restaurant =  $request->get('restaurant');
            $data = new Category();
            $data->restaurantid = $restaurant->id;
            $data->title = $request->title;
            if ($image = $request->file('photo')) {
                $path = 'categoryImage/';
                $imagename = time() . "." . $image->getClientOriginalExtension();
                $image->move($path, $imagename);
                $data->photo = $imagename;
            }
            $data->save();
            return Util::postResponse($data, 'Category added successfully'); 

        } catch(\Throwable $th){
            Util::getErrorResponse($th);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
