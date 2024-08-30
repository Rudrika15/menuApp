<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>menuApp - Dashboard</title>
    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">

    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!--jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- SweetAlert CSS and JS -->
    <script src="https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.js"></script>
    <link rel="stylesheet" href="https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
    @media print{
        .client-name{
            top: 57px;
            position: relative;
        }
    }
</style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Content Wrapper -->
        
        <div id="content-wrapper" class="d-flex flex-column" style="background-color: transparent">
            <!-- Main Content -->
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <img src="{{ asset('restaurantLogo/' . $restaurant->logo) }}" alt="Restaurant Logo"
                                style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 5px; display:block; margin:20px auto 5px">
                        </div>
                        <div class="col-12 text-center ">
                            <h2 style="font-weight: bold; margin-bottom:0;">{{ $restaurant->name ?? '-' }}</h2>
                            <p>{{ $restaurant->address ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="row  align-items-end">
                        <div class="col-md-6 text-left">
                            <p class="client-name" style="font-weight: bold; margin-bottom: 4px;">Name: {{ $order->name }}</p>
                        </div>

                        <div class="col-md-6 text-right">
                            <p style="font-weight: bold; margin-bottom: 4px;">GST Number: {{ $restaurant->gstNumber ?? '-' }}</p>
                            <p style="font-weight: bold; margin-bottom: 4px;">Date: {{ $order->created_at->format('d/m/y') }}</p>
                        </div>
                    </div>

                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Menu Title</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDetails as $detail)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $detail->menu->title }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>{{ number_format($detail->menu->price, 2) }}</td>
                                <td>{{ number_format($detail->qty * $detail->menu->price, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"></td>
                                <td class="text-center">
                                    <strong>Total:</strong>
                                <td>
                                    <strong>{{ number_format($orderDetails->sum(function($detail) {
                                        return $detail->qty * $detail->menu->price;
                                    }), 2) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
    <script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js')}}"></script>
    <script src="{{asset('js/demo/chart-pie-demo.js')}}"></script>

    <script>
        window.print();
    </script>
</body>

</html>