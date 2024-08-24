<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $restaurant = Restaurant::where('email', $request->email)->first();
        

        if ($restaurant && Hash::check($request->password, $restaurant->password)) {
            session(['email' => $request->email,'password' => $request->password,'id' => $restaurant->id,'name' => $restaurant->name]);
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => $restaurant, 
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'This credentials do not match',
            ]);
        }

    }

    public function logout(){
        Session::forget(['email','password']);
        return redirect()->route('welcome');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
