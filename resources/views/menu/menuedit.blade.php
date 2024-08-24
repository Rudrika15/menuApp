@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Menu Edit</h2>
        <a href="{{route('menu.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
    </div>
</div>

<form action="{{route('menu.update',$menu->id)}}" method="POST" id="edit-menu-form" enctype="multipart/form-data">
    @csrf
    <div id="categoty-container">
        <div class="category-form mb-5">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mb-2">
                    <strong>Restaurant:</strong>
                    <select name="restaurantid" class="form-control form-select" id="restaurantid">
                        <option value="{{ $menu->restaurant->id }}" selected>{{ $menu->restaurant->name }}</option>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mb-2">
                    <strong>Category:</strong>
                    <select name="categoryname" class="form-control form-select" id="categoryname">
                        <option value="{{ $menu->category->title }}" selected>{{ $menu->category->title }}</option>
                    </select>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Title:</strong>
                        <input type="text" name="title" class="form-control" id="menutitle"
                            value="{{$menu->title}}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Price:</strong>
                        <input type="text" name="price" class="form-control" id="price"
                            value="{{$menu->price}}">
                    </div>
                </div>

                <div class="col-3">
                    <strong>Image:</strong>
                    <img id="output"  class="img-fluid img-thumbnail" src="{{ asset('menuImage/' .$menu->photo) }}" alt="" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                </div>
            
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <input type="file"  style="padding: 9px 10px 4px" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])" name="photo" id="photo" class="form-contol" accept="image/*">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <strong>Status:</strong>
                    <select name="status" class="form-control status" id="status">
                        <option value="Active" {{ $menu->status=='Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ $menu->status=='Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2"><i class="fa fa-save"></i> Submit</button>
        </div>
    </div>

</form>

<script>
    $(document).ready(function (){
        $('#edit-menu-form').submit(function (data){
            data.preventDefault();

        var formdata = new FormData(this);

            $.ajax({

                url: $(this).attr('action'),
                method: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function (response){
                    if(response.status){
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = "{{ route('menu.index') }}";
                        }, 2000);
                    }else{
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }  
                    }
                },
                error: function (xhr, status, error) {
                    // Handle any errors from the server
                    toastr.error('An error occurred: ' + error);
                }

            });

        });
    });
</script>
@endsection