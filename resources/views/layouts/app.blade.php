<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name', 'Universal Booking Solution') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-rateyo@2.3.0/jquery.rateyo.min.css" />
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
    <style>
        li.input-control.input-control-14.ui-sortable-handle::before {
            /* content: 'sd'; */
            content: "";

            margin-right: -16px !important;
        }
    </style>

</head>

<body class="">

    <div id="app">
        <!-- Header -->
        @include('layouts.header')

        <!-- Side nav bar -->
        @include('layouts.navbar')

        <!-- all contents -->
        @yield('content')
    </div>
    <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>

    <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>

    <!-- Apex Chart -->
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <!-- custom-chart js -->
    <script src="{{asset('assets/js/pages/dashboard-main.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src="{{asset('assets/js/plugins/bootstrap.min.js')}}"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-rateyo@2.3.0/jquery.rateyo.min.js"></script>

    <!-- sweet alert Js -->
    <script src="{{asset('assets/js/plugins/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/ac-alert.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    <!-- DataTable Js -->
    <script src="{{asset('assets/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/dataTables.bootstrap4.min.js')}}"></script>
</body>

</html>