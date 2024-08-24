@extends('layout.layout')

@section('content')
<a href="{{route('staff.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
<table class="table table-bordered text-center">
    <tr>
        <th>Restaurant Name</th>
        <th>Name</th>
        <th>ContactNumber</th>
        <th>Email</th>
        <th>StaffType</th>
        <th>Status</th>
    </tr>
<tr>
    <td>
        {{$staff->restaurant->name}}
    </td>
    <td>
        {{$staff->name}}
    </td>
    <td>
        {{$staff->contactNumber}}
    </td>
    <td>
        {{$staff->email}}
    </td>
    <td>
        {{$staff->staffType}}
    </td>
    <td>
        {{$staff->status}}
    </td>

</tr>
</table>

@endsection
