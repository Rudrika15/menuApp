<?php

namespace App\Http\Controllers;

use App\Models\AddToCart;
use App\Models\Menu;
use App\Models\OrderDetail;
use App\Models\OrderMaster;
use App\Models\Restaurant;
use App\Models\Table;
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
            ->with('table')
            ->select('order_masters.*');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tableNumber', function($row){
                return $row->table->tableNumber;
            })
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
        $order->status = "Inactive";
        $order->save();
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
            ->addColumn('tableNumber', function($row){
                return $row->table->tableNumber;
            })
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
     * Remove the specified resource from storage.
     */
    public function addorderindex(Request $request)
    {
        $restaurantId = Session::get('id');
        $menu = Menu::where('restaurantId',$restaurantId)->get();
        $table = Table::where('restaurantId',$restaurantId)
        ->get();
        $addtocart = AddToCart::with('table')->where('restaurantId',$restaurantId)->get();
        return view('order.addorder', compact('menu', 'table','addtocart')) ->with('i', (request()->input('page', 1) - 1) * 5);

    }

    public function addtocartorder(Request $request)
    {
        $restaurantId = Session::get('id');

        $data =new AddToCart();
        $data->restaurantid = $restaurantId;
        $data->tableId = $request->tableId;
        $data->menuId = $request->menuId;
        $data->qty = $request->qty;
        $data->save();
        return response()->json([
            'status' => true,
            'message' => 'Order Add to Cart Successfully Saved!...',
            'data' => $data,
        ]);

    }

    public function ordermaster(Request $request){
        try{ $request->validate([
            'name' => 'required',
            'contactNumber' => 'required|numeric|digits:10|regex:/^[6-9]\d{9}$/',
        ]);

        $data = new OrderMaster();
        $data->tableId = $request->tableId;
        $data->name = $request->name;
        $data->contactNumber = $request->contactNumber; 
        $data->save();

        $items = $request->input("items");
        foreach($items as $item){
            $menuId = $item['menuId'];
            $qty = $item['qty'];

            $Data = new OrderDetail();
            $Data->orderId = $data->id;
            $Data->menuId = $menuId;
            $Data->qty = $qty;
            
            $Data->save();
        }
        $addtocart = AddToCart::where('tableId',$request->tableId);
        $addtocart->delete();

        return response()->json([
            'status' => true,
            'message' => 'Order Successfully Saved!...',
            'data' => $data,
        ]);
        }catch(\Illuminate\Validation\ValidationException $e){
        return response()->json([
            'status' => false,
            'errors' => $e->errors(),
        ]);
        }
    }


    public function deleteaddtocartorder(Request $request){

        $itemId = $request->input('id');
        $addtocart = AddToCart::find($itemId);
        $addtocart->delete();
        
        try{return response()->json([
            'status' => true,
            'message' => 'Item deleted Successfully!...',
            'data' => $addtocart,
        ]);
    }catch(\Illuminate\Validation\ValidationException $e){
        return response()->json([
            'status' => false,
            'errors' => $e->errors(),
        ]);
        }

    }
}
