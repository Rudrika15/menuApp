<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $restaurantId = Session::get('id');   
        $categories = Category::where('restaurantId',$restaurantId)->get();
        $menurestaurant = Menu::with('category')->where('restaurantId',$restaurantId);
        
        if ($request->has('categoryId') && $request->categoryId != '') {
            $menurestaurant->where('categoryId', $request->categoryId);
        }
        
        if ($request->ajax()) {
            $menus = $menurestaurant->get();
            return response()->json([
                'menus' => $menus,
            ]);
        }    
        $menu = $menurestaurant->paginate(5);
        return view('menu.menuindex',compact('menu','categories'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $restaurantId = Session::get('id');   
        $restaurant = Restaurant::find($restaurantId);
        $category = $restaurant ? $restaurant->category : [];
        return view('menu.menucreate',compact('restaurant','category'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
                
        try{ $validatedData = $request->validate([
            // 'restaurantid' => 'required',
            'categoryid' => 'required',
            'title' => 'required',
            'price'=>  'required|numeric',
            ]);
            $restaurantId = Session::get('id');   
            $data = new Menu();
            $data->restaurantid = $restaurantId;
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
        
            return response()->json([
                'status' => true,
                'message' => 'Menu Successfully Saved!...',
                'data' => $data,
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ]);
        }
        
    }
    public function trashmenu()
    {
        $restaurantId = Session::get('id');
        $menu = Menu::with('category')
            ->where('restaurantId', $restaurantId)
            ->onlyTrashed()
            ->where('status', 'Inactive')
            ->paginate(5);
            
        return view('menu.trashmenu', compact('menu'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function restore(string $id){

        $menu = Menu::onlyTrashed()->find($id);

        if ($menu) {
            $menu->restore();

            $menu->status = "Active";
            $menu->save();

            $menus = $menu->onlyTrashed()->get();
            foreach ($menus as $menu) {
                $menu->restore();
                $menu->status = "Active";
                $menu->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Menus restored successfully!',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Menu not found or already restored.',
            ]);
        }
    }

    public function forcedelete(string $id){
        $menu = Menu::withTrashed()->findOrFail($id);
    
        if ($menu) {
            if(!empty($menu->photo))
            {
                $imagepath = public_path('menuImage/'.$menu->photo);
                if(file_exists($imagepath)){
                    unlink($imagepath);
                }
            }

            $menu->forceDelete();
        
            return response()->json([
                'status' => 'success',
                'message' => 'Menus permanently deleted!',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Menu not found!',
            ]);
        }

    }
        /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $menu = Menu::all()->find($id);
        return view('menu.menuview',compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Menu::find($id);
        $restaurant = $menu->restaurant;
        $categories = $restaurant ? $restaurant->category : [];
        return view('menu.menuedit',compact('menu','categories'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    
        try{ $validatedData = $request->validate([
            "title" => 'required',
            "price"=>  'required|numeric',
            ]);

            $data  = Menu::find($id);
            $data->title = $request->title;
            $data->price = $request->price;
            $data->categoryid = $request->categoryid;
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $path = 'menuImage/';
                $imageName = time() . "." . $image->getClientOriginalExtension();
                $image->move($path, $imageName);

                if($data->photo){
                    $oldimagepath = public_path('menuImage/' . $data->photo);
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
        }catch(\Illuminate\Validation\ValidationException $e){
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
        $data = Menu::find($id);
        $data->status = "Inactive";
        $data->save();
        // if($data)
        // {
        //     if(!empty($data->photo))
        //     {
        //         $imagepath = public_path('menuImage/'.$data->photo);
        //         if(file_exists($imagepath)){
        //             unlink($imagepath);
        //         }
        //     }
        // }
        $data->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Deleted Successfully!',
            'data' => $data,
        ]);

    }

}