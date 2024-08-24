<?php

namespace App\Http\Controllers;

use App\Helpers\Util;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('register');
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
        // return $request;
        request()->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:restaurants,email',
            'password' => 'required|min:6',
        ]);
       
        $data = new Restaurant();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->gstNumber = $request->gstNumber;
        $data->upi = $request->upi;
        if($image = $request->file('logo')){
            $path = 'restaurantLogo/';
            $imagename = time(). "." . $image->getClientOriginalExtension();
            $image->move($path,$imagename);
            $data->logo = $imagename;
        }
        $data->color1 = $request->color1;
        $data->color2 = $request->color2;
        $data->address = $request->address;
        $data->token = Util::generateToken();
        $data->status = "Active";
        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'Register Successfully Saved!...',
            'data' => $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $email = session('email');
    
        $restaurant = Restaurant::where('email', $email)->first();
        return view('profiledit', compact('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $email = session('email'); 
        $restaurantId = Session::get('id');   
        
        $restaurant = Restaurant::where('email', $email)->first();
        if (!$restaurant) {
            return redirect()->route('profile.edit')->with('error', 'Restaurant not found.');
        }
    
        try{ $validatedData =  $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:restaurants,email,'.$restaurantId,
            'password'=> 'nullable|min:6',
            ]);

            $data = Restaurant::find($restaurantId);
            $data->name = $request->name;
            $data->email = $request->email;
            if($request->password){
                $data->password = bcrypt($request->password);
            }
            if($request->gstNumber){
                $data->gstNumber = $request->gstNumber;
            }
            if($request->upi){
                $data->upi = $request->upi;
            }

            if($request->hasFile('logo')){
                $image = $request->file('logo');
                $path = 'restaurantLogo/';
                $imageName = time() . "." . $image->getClientOriginalExtension();
                $image->move($path, $imageName);

                if($data->logo){
                    $oldimagepath = public_path('restaurantLogo/' . $data->logo);
                    if (file_exists($oldimagepath)) {
                        unlink($oldimagepath);
                    }
                }
                $data->logo = $imageName;            

            }
            if($request->color1){
                $data->color1 = $request->color1;
            }
            if($request->color2){
                $data->color2 = $request->color2;
            }
            if($request->address){
                $data->address = $request->address;
            }   
            $data->status = $request->status;

            $data->save();
            session(['email' => $data->email,'password' => $data->password,'name' => $data->name]);

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
        //
    }
}
