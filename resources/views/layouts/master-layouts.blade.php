<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-layout-style="default"
    data-layout-position="fixed" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-layout-width="fluid">
<head>
    <meta charset="utf-8" />
    <title> @yield('title')| {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
    @include('layouts.head-css')
</head>
<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <!-- Start content -->
                <div class="container-fluid">
                    @yield('content')
                </div> <!-- content -->
            </div>
            @include('layouts.footer')
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    @include('layouts.customizer')
    <!-- END Right Sidebar -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ URL::asset('assets/js/pages/select2.init.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>

    @include('layouts.vendor-scripts')

    <script>
        if (Session::has('success')) {
            Toastify.fire("{{ Session::get('message') }}");
        }
        if (Session::has('error')) {
            Toastify.fire("{{ Session::get('error') }}");
        }
        // Toastify({
        // duration: 3000,
        // newWindow: true,
        // close: true,
        // gravity: "top", // `top` or `bottom`
        // position: "right", // `left`, `center` or `right`
        // stopOnFocus: true, // Prevents dismissing of toast on hover
        // style: {
        //     background: "linear-gradient(to right, #00b09b, #96c93d)",
        // },
        // onClick: function(){} // Callback after click
        // }).showToast();
    </script>
</body>
</html>
