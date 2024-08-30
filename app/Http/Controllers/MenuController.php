<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $restaurantId = Session::get('id');
        $categories = Category::where('restaurantId', $restaurantId)->get();
        $menurestaurant = Menu::with('category')->where('restaurantId', $restaurantId);
    
        // Apply category filter if provided
        if ($request->has('categoryId') && !empty($request->categoryId)) {
            $menurestaurant = $menurestaurant->where('categoryId', $request->categoryId);
        }
    
        if ($request->ajax()) {
            return DataTables::of($menurestaurant)
                ->addIndexColumn()
                ->addColumn('category_name', function($row){
                    return $row->category ? $row->category->title : 'No Category';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('menu.show', $row->id).'" class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i> Show</a>';
                    $btn .= ' <a href="'.route('menu.edit', $row->id).'" class="btn btn-outline-warning btn-sm"><i class="fa fa-pencil-alt"></i> Edit</a>';
                    $btn .= ' <button class="btn btn-outline-danger btn-sm remove" data-id="'.$row->id.'" data-url="'.route('menu.destroy', $row->id).'"><i class="fa fa-trash"></i> Delete</button>';
                    return $btn;
                })
                ->editColumn('photo', function($row) {
                    $photoUrl = $row->photo ? asset('menuImage/'.$row->photo) : 'https://via.placeholder.com/100'; // Placeholder if no image
                    return '<img src="'.$photoUrl.'" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">';
                })
                ->rawColumns(['action', 'photo'])
                ->make(true);
        }
    
        return view('menu.menuindex', compact('categories'));        
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