@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h1>Old Order List</h1>
    </div>
</div>
    <table class="table table-bordered data-tables bg-white text-center">
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">Table Number</th>
                <th style="text-align: center">Name</th>
                <th style="text-align: center">Contact Number</th>
                <th style="text-align: center">Date</th>
                <th style="text-align: center">Status</th>
                <th style="text-align: center" width="100px">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

<script type="text/javascript">
  $(function () {
    var table = $('.data-tables').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('oldorder.index') }}",
        columns: [
            { data: null, name: 'id', orderable: false, searchable: false ,

                render: function(data,type,row,meta){
                    return meta.row + 1;
                }
            },
            {data: 'tableId', name: 'tableId',orderable: false},
            {data: 'name', name: 'name',orderable: false},
            {data: 'contactNumber', name: 'contactNumber',orderable: false},
            {data: 'date', name: 'date',orderable: false},
            {data: 'status', name: 'status',orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
        {
            targets: 0, 
            orderable: false
        }
        ],
        order: [] 
    });
  });
</script>
@endsection
