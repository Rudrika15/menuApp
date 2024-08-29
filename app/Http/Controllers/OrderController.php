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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $restaurantId = Session::get('id');
            $data = OrderMaster::whereHas('table',function($query) use ($restaurantId){
                $query->where('restaurantId', $restaurantId);
            })->with('table')->select('order_masters.*');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function($row){
                return $row->created_at->format('d/m/y');
            })
            ->addColumn('action', function($row){
                return '<a href="'.route('orderdetail.create',['orderId' => $row->id]).'" class="btn btn-outline-info btn-sm"><i class="fa fa-print"></i> Print</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
            
        }
        return view('order.orderview');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $orderId = $request->input('orderId');
        $order = OrderMaster::findOrFail($orderId);
        
        $orderDetails = OrderDetail::with('menu')
            ->where('orderId', $orderId)
            ->get();
        $restaurant = Restaurant::first();

        return view('order.orderdetail', compact('order', 'orderDetails','restaurant'));
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
