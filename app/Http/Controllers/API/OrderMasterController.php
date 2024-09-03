<?php

namespace App\Http\Controllers\API;

use App\Helpers\Util;
use App\Models\OrderMaster;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class OrderMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getActiveorders(Request $request)
    {
        $restaurant =  $request->get('restaurant')->id; 
        $data = OrderMaster::whereHas('table',function($query) use ($restaurant){
            $query->where('restaurantId', $restaurant);
            
        })->with('table')->where('status','Active');

        if(isset($request->tableId)){
            $data = $data->where('tableId', $request->tableId);
        }
        if(isset($request->name)){
            $data = $data->where('name','like','%'.$request->name .'%');
        }
        if(isset($request->contactNumber)){
            $data = $data->where('contactNumber',$request->contactNumber);
        }
        $order = $data->get();
        return Util::getResponse($order);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getInactiverders(Request $request)
    {
        $restaurant =  $request->get('restaurant')->id; 

        $data = OrderMaster::whereHas('table',function($query) use ($restaurant){
            $query->where('restaurantId', $restaurant);
            
        })->with('table')->where('status','Inactive');    

        if(isset($request->tableId)){
            $data = $data->where('tableId', $request->tableId);
        }
        if(isset($request->name)){
            $data = $data->where('name','like','%'.$request->name .'%');
        }
        if(isset($request->contactNumber)){
            $data = $data->where('contactNumber',$request->contactNumber);
        }
        $order = $data->get();
        return Util::getResponse($order);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderMaster $orderMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderMaster $orderMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderMaster $orderMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderMaster $orderMaster)
    {
        //
    }
}
