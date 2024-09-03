<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body>
    <section class="vh-100" style="background-color: #508bfc;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 ">

                            <h3 class="mb-5">Restaurant Registration</h3>

                            <form action="{{ route('restaurant.register.store') }}" method="POST"  >
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="col-md-4 col-form-label ">{{ __('Enter Name') }} <span class="text-danger">*</span></label> 
                                    <input id="name" type="name" class="form-control" name="name" placeholder="Enter Name"  autocomplete="name" >
                                   @error('name')
                                           <span class="text-danger">{{ $message }}</span>    
                                   @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="col-md-4 col-form-label ">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control" name="email" placeholder="Enter Email address"  autocomplete="email" >
                               @error('email')
                               <span class="text-danger">{{ $message }}</span>  
                               @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="col-md-4 col-form-label ">{{ __('Password') }} <span class="text-danger">*</span></label>
                                    <input id="password" type="password" class="form-control" placeholder="Enter Password" name="password" autocomplete="current-password">
                                   @error('password')
                                   <span class="text-danger">{{ $message }}</span>  
                                   @enderror
                                </div>
                              
                                <div class="mb-3">
                                    <div>
                                        <button type="submit" class="btn btn-dark">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "toastClass": "toast-success"
        }

        // toastr.success('Login successful!');
        // $('#login-form').on('submit', function (e) {
        //     e.preventDefault();

        //     var email = $('#email').val();
        //     var password = $('#password').val();
        //     var csrfToken = $('input[name="_token"]').val();

        //     $.ajax({
        //         url: '{{ route("login") }}',
        //         type: 'POST',
        //         data: {
        //             _token: csrfToken,
        //             email: email,
        //             password: password
        //         },
        //         success: function (response) {
        //             toastr.success('Login successful!');
        //             window.location.href = '{{ route("home") }}'; // Redirect to the home page or another page after successful login
        //         },
        //         error: function (response) {
        //             if (response.status === 422) {
        //                 var errors = response.responseJSON.errors;
        //                 if (errors.email) {
        //                     $('#email-error').text(errors.email[0]);
        //                     toastr.error(errors.email[0]);
        //                 }
        //                 if (errors.password) {
        //                     $('#password-error').text(errors.password[0]);
        //                    toastr.error(errors.password[0]);
        //                 }
        //             } else {
        //                 toastr.error('An error occurred. Please try again.');
        //             }
        //         }
        //     });
        // });
    </script>
</body>

</html>
