@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h1>Order Details</h1>
        <a href="{{route('order.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>

    </div>
</div>
<div class="container"> 
    <div class="row mb-4  align-items-center">
        <div class="col-6 text-center text-lg-left">
            <img src="{{ asset('restaurantLogo/' . $restaurant->logo) }}" alt="Restaurant Logo" style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 5px;">
        </div>
        <div class="col-6 text-center text-lg-right">
            <p style="font-weight: bold">GST Number: {{ $restaurant->gstNumber ?? '-' }}</p>
            <p style="font-weight: bold">Date: {{ $order->created_at->format('d/m/y') }}</p>
        </div>
    </div>

    <div class="mb-0 mt-2">
        <p style="font-weight: bold;">Name: {{ $order->name }}</p>
    </div>
    
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Menu Title</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderDetails as $detail)
            <tr>
                <td>{{ $detail->menu->title }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ number_format($detail->menu->price, 2) }}</td>
                <td>{{ number_format($detail->qty * $detail->menu->price, 2) }}</td>
            </tr>
            @endforeach
            <td colspan="11">
                <div class="mt-3 text-right">
                    <h4>Total Amount: {{ number_format($orderDetails->sum(function($detail) {
                        return $detail->qty * $detail->menu->price;
                    }), 2) }}</h4>
            </div>
        </td>
        </tbody>
    </table>
    
</div>

@endsection