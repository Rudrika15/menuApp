@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Staff List</h2>
        <a class="btn btn-primary btn-sm mb-2 mt-4" href="{{route('staff.create')}}" id="create-new"><i
                class="fa fa-plus"></i>
            Create New Staff</a>
    </div>
</div>
@if($staff->count() !== 0)

<table class="table table-bordered text-center bg-white">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>ContactNumber</th>
        <th>Email</th>
        <th>StaffType</th>
        <th>status</th>
        <th>Show</th>
        <th>Edit</th>
        <th>Delete</th>

    </tr>
    @foreach ($staff as $item)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->contactNumber }}</td>
        <td>{{ $item->email }}</td>
        <td>{{ $item->staffType }}</td>

        <td>{{ $item->status }}</td>
        <td>
            <a href="{{route('staff.show',$item->id)}}" class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i>
                Show</a>
        </td>
        <td>
            <a href="{{route('staff.edit',$item->id)}}" class="btn btn-outline-warning btn-sm"><i
                    class="fa fa-pencil-alt"></i> Edit</a>
        </td>
        <td>
            <button class="btn btn-outline-danger btn-sm remove" data-id="{{$item->id}}"> <i class="fa fa-trash"></i>
                Delete</button>
        </td>

    </tr>
    @endforeach
    @else
    <div style="text-align: center; font-size: 2.5rem; margin-top: 76px">Record Not Found</div>
    @endif

</table>
<script>
    $(document).ready(function(){
        $(".remove").click(function(){
            var id = $(this).data("id");
            var url = '{{ route("staff.destroy", ":id") }}';
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
                        type: 'GET',
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

{!! $staff->withQueryString()->links('pagination::bootstrap-5') !!}

@endsection