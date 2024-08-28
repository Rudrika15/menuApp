@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Menu List</h2>
        <a class="btn btn-primary btn-sm mb-2 mt-4" href="{{route('menu.create')}}" id="create-new"><i
                class="fa fa-plus"></i>
            Create New Menu</a>
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
            <th>Show</th>
            <th>Edit</th>
            <th>Delete</th>
    
        </tr>
    </thead>
    <tbody id="menu-table">
        @foreach ($menu as $item)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $item->category ? $item->category->title : 'No Category'}}</td>
            <td>{{ $item->title }}</td>
            <td>{{ number_format($item->price,2) }}</td>
            <td><img src="menuImage/{{ $item->photo }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;"></td>
            <td>{{ $item->status }}</td>
            <td>
                <a href="{{route('menu.show',$item->id)}}" class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i>
                    Show</a>
            </td>
            <td>
                <a href="{{route('menu.edit',$item->id)}}" class="btn btn-outline-warning btn-sm"><i
                        class="fa fa-pencil-alt"></i> Edit</a>
            </td>
            <td>
                <button class="btn btn-outline-danger btn-sm remove" data-id="{{$item->id}}" data-url="{{route('menu.destroy',$item->id)}}"> <i class="fa fa-trash"></i>
                    Delete</button>
            </td>
    
        </tr>
        @endforeach
    </tbody>

</table>
@else
<div style="text-align: center; font-size: 2.5rem; margin-top: 76px">Record Not Found</div>
@endif

<script>
    var menuShowUrl = "{{ route('menu.show', ':id') }}";
    var menuEditUrl = "{{ route('menu.edit', ':id') }}";
    var menuDeleteUrl = "{{ route('menu.destroy', ':id') }}";    
    $(document).ready(function(){
        
        $('#categoryFilter').change(function() {
            var categoryId = $(this).val();
            
            $.ajax({
                url: '{{route("menu.index")}}',
                type: 'GET',
                data: {
                    categoryId: categoryId
                },
                success: function(response){
                    var menus = response.menus;
                    var html = '';
                    if(menus.length > 0){
                        for (let i=0; i<menus.length; i++){
                            var showUrl = menuShowUrl.replace(':id', menus[i].id);
                            var editUrl = menuEditUrl.replace(':id', menus[i].id);
                            var deleteUrl = menuDeleteUrl.replace(':id', menus[i].id);
                            var categoryTitle = menus[i].category ? menus[i].category.title : 'No Category';
                            html += `<tr>
                                    <td>${i+1}</td>
                                    <td>${categoryTitle}</td>
                                    <td>${menus[i].title}</td>
                                    <td>${menus[i].price}</td>
                                    <td><img src="menuImage/${menus[i].photo}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;"></td>
                                    <td>${menus[i].status}</td>
                                     <td>
                                     <a href="${showUrl}" class="btn btn-outline-info btn-sm">
                                       <i class="fa fa-eye"></i> Show
                                     </a>
                                    </td>
                                    <td>
                                      <a href="${editUrl}" class="btn btn-outline-warning btn-sm"><i
                                       class="fa fa-pencil-alt"></i> Edit</a>
                                     </td>
                                    <td>
                                       <button class="btn btn-outline-danger btn-sm remove" data-id="${menus[i].id}" data-url="${deleteUrl}"> <i class="fa fa-trash"></i>
                                        Delete</button>
                                     </td>

                                    </tr>`;
                        }  
                    }else{
                        html = '<tr><td colspan="12">No menus found.</td></tr>';
                    }   

                    $('#menu-table').html(html)

                },
                error: function(){
                    toastr.error('Something went wrong while fetching the filtered menu');
                }
            });
            
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
{{-- {!! $menu->withQueryString()->links('pagination::bootstrap-5') !!} --}}

@endsection