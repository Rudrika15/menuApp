@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Table Create</h2>
        <a href="{{route('table.index')}}" class="btn btn-sm btn-primary mb-3" ><i class="fa fa-arrow-left"></i> Back </a>
    </div>
</div>

<form action="{{route('table.store')}}" method="POST" id="create-table-form" >
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
                        <strong>TableNumber:</strong>
                        <input type="text" name="tableNumber" class="form-control" id="tableNumber"
                            placeholder="TableNumber">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Capacity:</strong>
                        <input type="text" name="capacity" class="form-control" id="capacity"
                            placeholder="Capacity">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <strong>Status:</strong>
                    <select name="status" class="form-control status" id="status">
                        <option value="Booked">Booked</option>
                        <option value="Occupied" >Occupied</option>
                        <option value="Available" selected>Available</option>
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

        $('#create-table-form').submit(function (data){
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
                            window.location.href = "{{ route('table.index') }}";
                        }, 2000);   
                    }else{
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        }else {
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