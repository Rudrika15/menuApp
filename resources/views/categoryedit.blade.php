@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Catagory Edit</h2>
        <a href="{{route('category.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
    </div>
</div>

<form action="{{route('category.update',$category->id)}}" method="POST" id="edit-category-form" enctype="multipart/form-data">
    @csrf
    <div id="categoty-container">
        <div class="category-form mb-5">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mb-2">
                    <strong>Restaurant:</strong>
                    <select name="restaurantid" class="form-control form-select" id="restaurantid">
                        @if($category)
                        <option value="{{ $category->restaurant->id }}" selected>{{ $category->restaurant->name }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Title:</strong>
                        
                        <input type="text" name="title" class="form-control" id="categorytitle"
                            value="{{$category->title}}">
                    </div>
                </div>
                <div class="col-3">
                    <strong>Image:</strong>
                    <img id="output"  class="img-fluid img-thumbnail" src="{{ asset('categoryImage/' .$category->photo) }}" alt="" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                </div>
            
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <input type="file"  style="padding: 9px 10px 4px" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])" name="photo" id="photo" class="form-contol" accept="image/*">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <strong>Status:</strong>
                    <select name="status" class="form-control status" id="status">
                        <option value="Active" {{ $category->status=='Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ $category->status=='Inactive' ? 'selected' : '' }}>Inactive</option>
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
        $('#edit-category-form').submit(function (data){
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
                            window.location.href = "{{ route('category.index') }}";
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