<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ get_setting('site_title', config('app.name', 'Universal Booking Solution')) }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ get_setting('favicon') ? asset('storage/' . get_setting('favicon')) : asset('assets/images/favicon.ico') }}" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-rateyo@2.3.0/jquery.rateyo.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/backend/loader.css') }}?v={{ time() }}">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">

    <style>
        li.input-control.input-control-14.ui-sortable-handle::before {
            content: "";
            margin-right: -16px !important;
        }
    </style>
</head>

<body>
    <div id="app">
        @include('admin.layouts.header')
        @include('admin.layouts.navbar')
        @yield('content')
    </div>

    <!-- JS ORDER MATTERS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Core Vendor -->
    <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>

    <!-- Plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-rateyo@2.3.0/jquery.rateyo.min.js"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Custom -->
    <script src="{{ asset('assets/js/pages/dashboard-main.js') }}"></script>
    <script src="{{ asset('assets/js/pages/ac-alert.js') }}"></script>
    <script src="{{ asset('assets/js/backend/custom.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/backend/bookingform.js') }}?v={{ time() }}"></script>
</body>
</html>
