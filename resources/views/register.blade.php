<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .text-danger {
        color: red; /* Red text color */
        font-weight: bold; /* Bold text */
        padding-left: 10px; /* Padding to align with input fields */
        display: none; /* Hidden by default */
    }
    
    .text-danger.show {
        display: block; /* Only show when the 'show' class is added */
        margin-bottom: 8px; /* Adds space below the error message */
    }
    
    </style>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="post" id="register-form" action="{{route('registerdata.store')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="name" id="name"
                                            placeholder="Name">
                                            <span class="text-danger"  id="name-error" > </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control form-control-user" name="email" id="email"
                                            placeholder="Email Address">
                                            <span class="text-danger"  id="email-error" > </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="password" id="password"
                                        placeholder="Password">
                                        <span class="text-danger"  id="password-error" > </span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user"
                                            name="gstNumber" id="gstNumber" placeholder="GST-Number">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user"
                                            name="upi" id="upi" placeholder="UPI">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="file" class="form-control form-control-user" style="padding: 12px 18px 36px" id="logo"
                                        name="logo" accept="image/*">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user"
                                            name="color1" id="color1" placeholder="Color1">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user"
                                            name="color2" id="color2" placeholder="Color2">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user"
                                        name="address" id="address" placeholder="Address">
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
                                <hr>
                                <a href="index.html" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{route('welcome')}}">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {

        $('#register-form').submit(function (data){
            data.preventDefault();
            $('#name-error').text('').removeClass('show');
            $('#email-error').text('').removeClass('show');
            $('#password-error').text('').removeClass('show');
            var formdata = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function (response){
                    if(response.status){
                        window.location.href = "{{route('welcome')}}";
                    }else{
                        alert('Registration failed. Please try again.');
                    }
                },
                error: function (xhr, status, error) {
                    var errors = xhr.responseJSON.errors;
                    if (errors.name) {
                    $('#name-error').text(errors.name[0]).addClass('show');
                    }
                    if (errors.email) {
                    $('#email-error').text(errors.email[0]).addClass('show');
                    }                    
                    if (errors.password) {
                    $('#password-error').text(errors.password[0]).addClass('show');
                    }                }
            });
        });
    });
</script>
</body>

</html>