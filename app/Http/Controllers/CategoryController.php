<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurantId = Session::get('id');
        $category = Category::where('restaurantId', $restaurantId)->paginate(5);
        return view('catagories', compact('category'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $restaurantId = Session::get('id');
        $restaurant = Restaurant::find($restaurantId);
        return view('createcategories', compact('restaurant', 'restaurantId'));
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
            $restaurantId = Session::get('id');
            $data = new Category();
            $data->restaurantid = $restaurantId;
            $data->title = $request->title;
            if ($image = $request->file('photo')) {
                $path = 'categoryImage/';
                $imagename = time() . "." . $image->getClientOriginalExtension();
                $image->move($path, $imagename);
                $data->photo = $imagename;
            }
            $data->save();

            return response()->json([
                'status' => true,
                'message' => 'Category Successfully Saved!...',
                'data' => $data,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ]);
        }
    }

    public function trashcategory()
    {
        $restaurantId = Session::get('id');
        $category = Category::where('restaurantId', $restaurantId)->where('status', 'Inactive')->onlyTrashed()->paginate(5);
        return view('trashcategories', compact('category'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function restore(string $id)
    {
        $category = Category::onlyTrashed()->with('menu')->find($id);

        if ($category) {
            $category->restore();

            $category->status = "Active";
            $category->save();

            $menus = $category->menu()->onlyTrashed()->get();
            foreach ($menus as $menu) {
                $menu->restore();
                $menu->status = "Active";
                $menu->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Category and its menus restored successfully!',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found or already restored.',
            ]);
        }
    }

    public function forcedelete(string $id)
    {
        $category = Category::withTrashed()->findOrFail($id);
    
        if ($category) {
            if(!empty($category->photo))
            {
                $imagepath = public_path('categoryImage/'.$category->photo);
                if(file_exists($imagepath)){
                    unlink($imagepath);
                }
            }

            $category->menu()->forceDelete();
    
            $category->forceDelete();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Category and its menus permanently deleted!',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found!',
            ]);
        }
    }
    
    public function show(string $id)
    {
        $category = Category::all()->find($id);
        return view('viewcategory', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::with('restaurant')->find($id);
        return view('categoryedit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                "title" => 'required',
            ]);

            $data  = Category::find($id);
            $data->title = $request->title;
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $path = 'categoryImage/';
                $imageName = time() . "." . $image->getClientOriginalExtension();
                $image->move($path, $imageName);

                if ($data->photo) {
                    $oldimagepath = public_path('categoryImage/' . $data->photo);
                    if (file_exists($oldimagepath)) {
                        unlink($oldimagepath);
                    }
                }

                $data->photo = $imageName;
            }

            // $data->status = $request->status;
            $data->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Updated successfully!',
                'data' => $data,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Category::find($id);
        // if($data)
        // {
        //     if(!empty($data->photo))
        //     {
        //         $imagepath = public_path('categoryImage/'.$data->photo);
        //         if(file_exists($imagepath)){
        //             unlink($imagepath);
        //         }
        //     }
        // }
        $data->status = "Inactive";
        $data->save();
        $menus = $data->menu;
        foreach ($menus as $menu) {
            $menu->status = "Inactive";
            $menu->save();
        }
        $data->menu()->delete();
        $data->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Deleted Successfully!',
            'data' => $data,
        ]);
    }
}
