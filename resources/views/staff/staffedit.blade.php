@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Staff Edit</h2>
        <a href="{{route('staff.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
    </div>
</div>

<form action="{{route('staff.update',$staff->id)}}" method="POST" id="edit-staff-form" >
    @csrf
    <div id="staff-container">
        <div class="staff-form mb-5">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mb-2">
                    <strong>Restaurant:</strong>
                    <select name="restaurantname" class="form-control form-select" id="restaurantname">
                        <option value="{{ $staff->restaurant->name }}" selected>{{ $staff->restaurant->name }}</option>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" class="form-control" id="name"
                            value="{{$staff->name}}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>ContactNumber:</strong>
                        <input type="text" name="contactNumber" class="form-control" id="contactNumber"
                        value="{{$staff->contactNumber}}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <input type="email" name="email" class="form-control" id="email"
                        value="{{$staff->email}}">
                    </div>
                </div>                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>StaffType:</strong>
                        <input type="text" name="staffType" class="form-control" id="staffType"
                        value="{{$staff->staffType}}">
                    </div>
                </div>                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <strong>Status:</strong>
                    <select name="status" class="form-control status" id="status">
                        <option value="Active" {{ $staff->status=='Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ $staff->status=='Inactive' ? 'selected' : '' }}>Inactive</option>
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
        $('#edit-staff-form').submit(function (data){
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