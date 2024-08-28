@extends('layout.layout')

@section('content')
<a href="{{route('category.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
<table class="table table-bordered text-center">
    <tr>
        {{-- <th>Restaurant Name</th> --}}
        <th>Title</th>
        <th>Photo</th>
        <th>Status</th>
    </tr>
<tr>
    {{-- <td>
        {{$category->restaurant->name}}
    </td> --}}
    <td>
        {{$category->title}}
    </td>
    <td>
        <img src="{{asset('categoryImage/' . $category->photo)}}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
    </td>
    <td>
        {{$category->status}}
    </td>

</tr>
</table>

@endsection
