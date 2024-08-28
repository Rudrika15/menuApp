@extends('layout.layout')

@section('content')
<a href="{{route('table.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
<table class="table table-bordered text-center">
    <tr>
        {{-- <th>Restaurant Name</th> --}}
        <th>TableNumber</th>
        <th>Capacity</th>
        <th>Status</th>
    </tr>
<tr>
    {{-- <td>
        {{$table->restaurant->name}}
    </td> --}}
    <td>
        {{$table->tableNumber}}
    </td>
    <td>
        {{$table->capacity}}
    </td>
    <td>
        {{$table->status}}
    </td>

</tr>
</table>

@endsection
