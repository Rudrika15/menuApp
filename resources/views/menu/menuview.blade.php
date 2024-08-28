@extends('layout.layout')

@section('content')
<a href="{{route('menu.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
<table class="table table-bordered text-center">
    <tr>
        {{-- <th>Restaurant Name</th> --}}
        <th>Category Name</th>
        <th>Title</th>
        <th>Price</th>
        <th>Photo</th>
        <th>Status</th>
    </tr>
<tr>
    {{-- <td>
        {{$menu->restaurant->name}}
    </td> --}}
    <td>
        {{$menu->category->title}}
    </td>

    <td>
        {{$menu->title}}
    </td>
    <td>
        {{$menu->price}}
    </td>
    <td>
        <img src="{{asset('menuImage/'.$menu->photo)}}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
    </td>
    <td>
        {{$menu->status}}
    </td>

</tr>
</table>

@endsection
