<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\OrderMaster;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $restaurantId = Session::get('id');
            $data = OrderMaster::whereHas('table',function($query) use ($restaurantId){
                $query->where('restaurantId', $restaurantId);
            })
            ->where('status','Active')
            ->with('table')->select('order_masters.*');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function($row){
                return $row->created_at->format('d/m/y');
            })
            ->addColumn('action', function($row){
                return '<a href="'.route('orderdetail.create',['orderId' => $row->id]).'" class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i> Preview</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
            
        }
        return view('order.orderview');
    }

    public function create(Request $request)
    {
        $orderId = $request->input('orderId');
        $order = OrderMaster::findOrFail($orderId);
        
        $orderDetails = OrderDetail::with('menu')
            ->where('orderId', $orderId)
            ->get();
        $restaurant = Restaurant::first();

        return view('order.orderdetail', compact('order', 'orderDetails','restaurant')) ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function printorder(Request $request,$orderId)
    {
        $order = OrderMaster::findOrFail($orderId);
        
        $orderDetails = OrderDetail::with('menu')
            ->where('orderId', $orderId)
            ->get();
        $restaurant = Restaurant::first();

        return view('order.orderbill', compact('order', 'orderDetails','restaurant')) ->with('i', (request()->input('page', 1) - 1) * 5);

    }

    public function oldorderindex(Request $request)
    {
        if($request->ajax()){
            $restaurantId = Session::get('id');
            $data = OrderMaster::whereHas('table',function($query) use ($restaurantId){
                $query->where('restaurantId', $restaurantId);
            })
            ->where('status','Inactive')
            ->with('table')->select('order_masters.*');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function($row){
                return $row->created_at->format('d/m/y');
            })
            ->addColumn('action', function($row){
                return '<a href="'.route('orderdetail.create',['orderId' => $row->id]).'" class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i> Preview</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
            
        }
        return view('order.oldorderview');

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
