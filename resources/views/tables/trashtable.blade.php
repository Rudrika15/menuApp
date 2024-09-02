@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Trashed Table List</h2>
    </div>
</div>
@if($table->count() !== 0)

<table class="table table-bordered text-center">
    <tr>
        <th>No</th>
        <th>TableNumber</th>
        <th>Capacity</th>
        <th>Status</th>
        <th>Restore</th>
        <th>Delete</th>

    </tr>
     @foreach ($table as $item)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $item->tableNumber }}</td>
        <td>{{ $item->capacity }}</td>
        <td>{{ $item->status }}</td>
        <td>
            <button class="btn btn-outline-warning btn-sm restore" data-id="{{$item->id}}">
                <i class="fa fa-trash-restore"></i> Restore
            </button>

            {{-- <a href="{{route('table.edit',$item->id)}}" class="btn btn-outline-warning btn-sm"><i
                    class="fa fa-pencil-alt"></i> Restore</a> --}}
        </td>
        <td>
            <button class="btn btn-outline-danger btn-sm remove" data-id="{{$item->id}}"> <i class="fa fa-trash"></i>
                Delete</button>
        </td>

    </tr>
    @endforeach


</table>
@else
<div style="text-align: center; font-size: 2.5rem; margin-top: 76px">Record Not Found</div>
@endif

{!! $table->withQueryString()->links('pagination::bootstrap-5') !!}
<script>
    $(document).ready(function(){
        $(".restore").click(function(){
            var id = $(this).data("id");
            var url = '{{ route("restore.table", ":id") }}';
            url = url.replace(':id', id);

            swal({
                title: "Are you sure?",
                text: "You want to restore this Table?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, restore it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm){
                if(isConfirm){
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            if (response.status === 'success') {
                                toastr.success(response.message, 'Success');
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                toastr.error(response.message, 'Error');
                            }
                        },
                        error: function() {
                            toastr.error('Something went wrong', 'Error');
                        }
                    });
                } else {
                    swal("Cancelled", "The Table is still in the trash :)", "error");
                }
            });
        });

        $(".remove").click(function(){
            var id = $(this).data("id");
            var url = '{{ route("forcedelete.table", ":id") }}';
            url = url.replace(':id', id);

        swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        closeOnConfirm: false,
        closeOnCancel: false
        },     
        function(isConfirm){
            if(isConfirm){
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {_token: '{{csrf_token()}}'},
                        success: function(response) {
                            if (response.status === 'success') {
                              toastr.success(response.message, 'success');
                             setTimeout(function() {
                            location.reload();
                             }, 1000);
                                $('button[data-id="' + id + '"]').closest('tr').remove();
                            } else {
                                toastr.error(response.message, 'Error');
                            }
                        },
                        error: function() {
                        alert('Something is wrong');
                        },
                    });
            }else{
                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
        });
    });
</script>

@endsection