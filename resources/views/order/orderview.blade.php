@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h1>Order List</h1>
        <a href="{{route('order.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
    </div>
</div>
    <table class="table table-bordered data-table bg-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Table Number</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Date</th>
                <th>Status</th>
                <th width="100px">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

<script type="text/javascript">
  $(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('order.index') }}",
        columns: [
            { data: null, name: 'id', orderable: false, searchable: false ,

            render: function(data,type,row,meta){
                return meta.row + 1;
            }
            },
            {data: 'tableId', name: 'tableId'},
            {data: 'name', name: 'name'},
            {data: 'contactNumber', name: 'contactNumber'},
            {data: 'date', name: 'date'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
  });
</script>
@endsection
