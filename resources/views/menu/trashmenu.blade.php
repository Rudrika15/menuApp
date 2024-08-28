@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Trashed Menu List</h2>
    </div>
</div>

@if($menu->count() !== 0)
<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>No</th>
            <th>Category Name</th>
            <th>Title</th>
            <th>Price</th>
            <th>Photo</th>
            <th>Status</th>
            <th>Restore</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody id="menu-table">
        @foreach ($menu as $item)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $item->category ? $item->category->title : 'No Category' }}</td>
            <td>{{ $item->title }}</td>
            <td>{{ number_format($item->price, 2) }}</td>
            <td><img src="{{ asset('menuImage/'.$item->photo) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;"></td>
            <td>{{ $item->status }}</td>
            <td>
                <button class="btn btn-outline-warning btn-sm restore" data-id="{{$item->id}}">
                    <i class="fa fa-trash-restore"></i> Restore
                </button>
    
                {{-- <a href="{{ route('restore.menu', $item->id) }}" class="btn btn-outline-warning btn-sm">
                    <i class="fa fa-pencil-alt"></i> Restore
                </a> --}}
            </td>
            <td>
                <button class="btn btn-outline-danger btn-sm remove" data-id="{{ $item->id }}" data-url="{{ route('forcedelete.menu', $item->id) }}">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div style="text-align: center; font-size: 2.5rem; margin-top: 76px">Record Not Found</div>
@endif

<script>
    $(document).ready(function(){
        $(".restore").click(function(){
            var id = $(this).data("id");
            var url = '{{ route("restore.menu", ":id") }}';
            url = url.replace(':id', id);

            swal({
                title: "Are you sure?",
                text: "You want to restore this Menu?",
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
                    swal("Cancelled", "The Menu is still in the trash :)", "error");
                }
            });
        });
    });

    $(document).on('click', '.remove', function() {

        var id = $(this).data("id");
        var url = $(this).data('url');

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this menu item!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message, 'Success');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                            $('button[data-id="' + id + '"]').closest('tr').remove();
                        } else {
                            toastr.error(response.message, 'Error');
                        }
                    },
                    error: function() {
                        alert('Something went wrong');
                    },
                });
            } else {
                swal("Cancelled", "Your menu item is safe :)", "error");
            }
        });
    });
</script>

@endsection
