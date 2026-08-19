<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @hasSection('title')
            @yield('title')
        @else
            Dashboard | Machine Tool
        @endif
    </title>
    <link href="{{ asset('assets/css/backend/styles.css') }}" rel="stylesheet" />

    <script src="{{ asset('assets/js/backend/fontawesome/all.js') }}" crossorigin="anonymous"></script>

    <link href="{{ asset('assets/css/backend/toastr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/backend/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/backend/sweetalert2.js') }}" crossorigin="anonymous"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Chart.js Data Labels Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js">
    </script>
    <script>
        // Register the plugin
        Chart.register(ChartDataLabels);
    </script>



    <link rel="stylesheet" href="{{ asset('assets/vendor/summernote/summernote.min.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    {{-- <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" /> --}}

    <link rel="shortcut icon"
        href="{{ Helper::getSettings('site_logo') ? asset(Helper::getSettings('site_logo')) : asset('assets/img/favicon.png') }}" />

</head>
