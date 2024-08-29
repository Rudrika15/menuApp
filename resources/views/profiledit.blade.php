@extends('layout.layout')

@section('content')
<div class="container">
    <h2>Update Profile</h2>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <a href="{{route('dashboard.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>

    <form action="{{ route('profile.update') }}" method="POST" id="edit-profile-form">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{  $restaurant->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $restaurant->email}}" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control form-control-user" name="password" id="password"
                placeholder="Password">
        </div>
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <strong>gstNumber:</strong>
                <input type="text" class="form-control form-control-user"
                    name="gstNumber" id="gstNumber" value="{{  $restaurant->gstNumber }}">
            </div>
            <div class="col-sm-6">
                <strong>UPI:</strong>
                <input type="text" class="form-control form-control-user"
                    name="upi" id="upi" value="{{  $restaurant->upi }}">
            </div>
        </div>
        <div class="form-group">
            <strong>Image:</strong>
            <img id="output"  class="img-fluid img-thumbnail" src="{{ asset('restaurantLogo/' .$restaurant->logo) }}" alt="" style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 5px;">
        </div>
    
        {{-- <div class="col-xs-12 col-sm-12 col-md-12"> --}}
            <div class="form-group">
                <input type="file"  onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])" name="logo" id="logo" class="form-contol" accept="image/*">
            </div>
        {{-- </div> --}}
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <strong>Color1:</strong>
                <input type="text" class="form-control form-control-user"
                    name="color1" id="color1" value="{{  $restaurant->color1 }}">
            </div>
            <div class="col-sm-6">
                <strong>Color2:</strong>
                <input type="text" class="form-control form-control-user"
                    name="color2" id="color2" value="{{ $restaurant->color2 }}">
            </div>
        </div>
        <div class="form-group">
            <strong>Address:</strong>
            <input type="text" class="form-control form-control-user"
                name="address" id="address" value="{{ $restaurant->address }}">
        </div>
        <div class="form-group">
            <strong>Status:</strong>
            <select name="status" class="form-control status" id="status">
                <option value="Active" {{ $restaurant->status=='Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ $restaurant->status=='Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
<script>
    $(document).ready(function (){
        $('#edit-profile-form').submit(function (data){
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
                            location.reload();
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
