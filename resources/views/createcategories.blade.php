@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Catagory Create</h2>
        <a href="{{route('category.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
    </div>
</div>

<form action="{{route('category.store')}}" method="POST" id="create-category-form" enctype="multipart/form-data">
    @csrf
    <div id="categoty-container">
        <div class="category-form mb-5">
            <div class="row">
                {{-- <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mb-2">
                    <strong>Restaurant:</strong>
                    <select name="restaurantid" class="form-control form-select" id="restaurantid">
                        @if($restaurant)
                        <option value="{{ $restaurant->id }}" selected>{{ $restaurant->name }}</option>
                        @endif
                    </select>
                </div> --}}

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Title:</strong>
                        <input type="text" name="title" class="form-control" id="categorytitle"
                            placeholder="Title">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image:</strong>
                        <img id="imagePreview" src="#" alt="Image Preview" style="display:none; margin-bottom:5px;  width: 100px; height: 100px; object-fit: cover; border-radius: 5px;"/>
                        <input type="file" class="form-control " style="padding: 9px 10px 36px" id="photo"
                        name="photo" accept="image/*" onchange="previewImage(event)">
                    </div>
                </div>
                {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                    <strong>Status:</strong>
                    <select name="status" class="form-control status" id="status">
                        <option value="Active" >Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div> --}}

        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2"><i class="fa fa-save"></i> Submit</button>
        </div>
    </div>

</form>

<script>
    $(document).ready(function(){

        $('#create-category-form').submit(function (data){
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
                        $('button[type="submit"]').text('Submitted').prop('disabled', true);
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

function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('imagePreview');
        output.src = reader.result;
        output.style.display = 'block'; 
    }
    reader.readAsDataURL(event.target.files[0]);
}


</script>
@endsection