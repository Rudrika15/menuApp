@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Trashed Catagories List</h2>
    </div>
</div>
@if($category->count() !== 0)
<table class="table table-bordered text-center">
    <tr>
        <th>No</th>
        <th>Title</th>
        <th>Photo</th>
        <th>Status</th>
        <th>Restore</th>
        <th>Delete</th>

    </tr>
    @foreach ($category as $item)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $item->title }}</td>
        <td><img src="{{ asset('categoryImage/' . $item->photo) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;"></td>
        <td>{{ $item->status }}</td>
        <td>
            <button class="btn btn-outline-warning btn-sm restore" data-id="{{$item->id}}">
                <i class="fa fa-trash-restore"></i> Restore
            </button>
            {{-- <a href="{{route('restore.category',$item->id)}}" class="btn btn-outline-warning btn-sm"><i
                    class="fa fa-pencil-alt"></i> Restore</a> --}}
        </td>
        <td>
            <button class="btn btn-outline-danger btn-sm force-delete" data-id="{{$item->id}}"> <i class="fa fa-trash"></i>
                Delete</button>
        </td>

    </tr>
    @endforeach
    
</table>
@else
<div style="text-align: center; font-size: 2.5rem; margin-top: 76px">Record Not Found</div>
@endif
{!! $category->withQueryString()->links('pagination::bootstrap-5') !!}
<script>
    $(document).ready(function(){
        $(".restore").click(function(){
            var id = $(this).data("id");
            var url = '{{ route("restore.category", ":id") }}';
            url = url.replace(':id', id);

            swal({
                title: "Are you sure?",
                text: "You want to restore this category?",
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
                    swal("Cancelled", "The category is still in the trash :)", "error");
                }
            });
        });

        $(".force-delete").click(function(){
            var id = $(this).data("id");
            var url = '{{ route("forcedelete.category", ":id") }}';
            url = url.replace(':id', id);

        swal({
        title: "Are you sure?",
        text: "You want to permanently delete this category and its menus?",
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
                        data: {_token:'{{csrf_token()}}'},
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
                    swal("Cancelled", "The category is still in the trash  :)", "error");
                }
            });
        });
    });
</script>
@endsection