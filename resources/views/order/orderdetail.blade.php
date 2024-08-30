@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h1>Order Details</h1>
    </div>
</div>
<div class="container-fluid"> 
    <div class="row mb-2">
        <div class="col-12 text-center">
            <img src="{{ asset('restaurantLogo/' . $restaurant->logo) }}" alt="Restaurant Logo" style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 5px; display:block; margin:20px auto 5px">
        </div>
        <div class="col-12 text-center ">
            <h2 style="font-weight: bold; margin-bottom:0;">{{ $restaurant->name ?? '-' }}</h2>
            <p>{{ $restaurant->address ?? '-' }}</p>
        </div>
    </div>
    <div class="row ">
        <div class="col-12 text-right">
            <a href="{{route('orderbill.printorder',request('orderId'))}}" target="_blank" class="btn btn-sm btn-primary mb-3"><i class="fa fa-print"></i> Print</a>
        </div>
    </div>
    <div class="row  align-items-end">
        <div class="col-md-6 text-left">
            <p style="font-weight: bold; margin-bottom:4px">Name: {{ $order->name }}</p>
        </div>

        <div class="col-md-6 text-right">
            <p style="font-weight: bold; margin-bottom:4px">GST Number: {{ $restaurant->gstNumber ?? '-' }}</p>
            <p style="font-weight: bold; margin-bottom:4px">Date: {{ $order->created_at->format('d/m/y') }}</p>
        </div>
    </div>
    
    <table class="table table-bordered bg-white text-center">
        <thead>
            <tr>
                <th>No</th>
                <th>Menu Title</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderDetails as $detail)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $detail->menu->title }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ number_format($detail->menu->price, 2) }}</td>
                <td>{{ number_format($detail->qty * $detail->menu->price, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3"></td>
                <td class="text-center">
                        <strong>Total:</strong>
                <td>
                        <strong>{{ number_format($orderDetails->sum(function($detail) {
                            return $detail->qty * $detail->menu->price;
                        }), 2) }}</strong>
                </td>    
            </tr>
        </tbody>
    </table>
    
</div>
@endsection