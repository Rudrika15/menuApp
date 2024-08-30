@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Menu List</h2>
        <a class="btn btn-primary btn-sm mb-2 mt-4" href="{{ route('menu.create') }}" id="create-new"><i class="fa fa-plus"></i> Create New Menu</a>
        <div class="form-group">
            <label for="categoryFilter">Filter by Category:</label>
            <select class="form-control" id="categoryFilter">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<table class="table table-bordered data-table bg-white text-center">
    <thead>
        <tr>
            <th style="text-align: center">No</th>
            <th style="text-align: center">Category Name</th>
            <th style="text-align: center">Title</th>
            <th style="text-align: center">Price</th>
            <th style="text-align: center">Photo</th>
            <th style="text-align: center">Status</th>
            <th style="text-align: center">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('menu.index') }}",
                data: function (d) {
                    d.categoryId = $('#categoryFilter').val(); 
                }
            },
            columns: [
                { data: null, name: 'id', orderable: false, searchable: false ,
                render: function(data,type,row,meta){
                    return meta.row + 1;
                }
                },

                {data: 'category_name', name: 'category_name',orderable: false},
                {data: 'title', name: 'title',orderable: false},
                {data: 'price', name: 'price',orderable: false,
                    render: function(data,type,row,meta){
                        return number_format(data,2);
                    }
                },
                {data: 'photo', name: 'photo', orderable: false, searchable: false},
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

        $('#categoryFilter').change(function() {
            table.draw();
        });
    });

    $(document).on('click', '.remove', function() {
            var id = $(this).data("id");
            var url = $(this).data('url');
            // var url = '{{ route("menu.destroy", ":id") }}';
            // url = url.replace(':id', id);

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
</script>
@endsection
