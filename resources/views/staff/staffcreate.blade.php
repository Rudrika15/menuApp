@extends('layout.layout')

@section('content')
<div class="col-lg-12 margin-tb">
    <h2>Staff Create</h2>
    <a href="{{route('staff.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
</div>

<form action="{{route('staff.store')}}" method="POST" id="create-staff-form" >
    @csrf
    <div id="categoty-container">
        <div class="category-form mb-5">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mb-2">
                    <strong>Restaurant:</strong>
                    <select name="restaurantid" class="form-control form-select" id="restaurantid">
                        <option value="{{ $restaurant->id }}" selected>{{ $restaurant->name }}</option>
                    </select>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Name">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>ContactNumber:</strong>
                        <input type="text" name="contactNumber" class="form-control" id="contactNumber"
                            placeholder="ContactNumber">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <input type="email" name="email" class="form-control" id="email"
                            placeholder="Email">
                    </div>
                </div>                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Password:</strong>
                        <input type="text" name="password" class="form-control" id="password"
                            placeholder="Password">
                    </div>
                </div>                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>StaffType:</strong>
                        <input type="text" name="staffType" class="form-control" id="staffType"
                            placeholder="StaffType">
                    </div>
                </div>                
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <strong>Status:</strong>
                    <select name="status" class="form-control status" id="status">
                        <option value="Active" >Active</option>
                        <option value="Inactive" >Inactive</option>
                    </select>
                </div>

        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2"><i class="fa fa-save"></i> Submit</button>
        </div>
    </div>

</form>

<script>
    $(document).ready(function(){

        $('#create-staff-form').submit(function (data){
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
                            window.location.href = "{{ route('staff.index') }}";
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