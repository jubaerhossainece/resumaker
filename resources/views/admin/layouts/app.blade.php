
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Resumake')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Resumake" name="description" />
    <meta content="Riseup Labs" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-stylesheet" />
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">
    @stack('styles')

</head>

<body>

<!-- Begin page -->
<div id="wrapper">


    @include('admin.inc.topbar')

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left-side-menu">

        <div class="slimscroll-menu">

            @include('admin.inc.sidebar')

            <div class="clearfix"></div>

        </div>
        <!-- Sidebar -left -->

    </div>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">

            @yield('content')

        </div>
        <!-- end content -->


        @include('admin.inc.footer')

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->

<!-- Vendor js -->
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>

<!-- yajra datatables js -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

@stack('scripts')

</body>

</html>
