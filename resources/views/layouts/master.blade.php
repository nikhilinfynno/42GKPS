<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="dark"
    data-sidebar="light" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')| {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <meta name="_token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
    @include('layouts.head-css')
</head>
@section('body')
@include('layouts.body')
@show
<!-- Begin page -->
<div id="layout-wrapper">
    @include('layouts.topbar')
    @include('layouts.sidebar')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @include('layouts.footer')
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->

@include('layouts.customizer')

<!-- JAVASCRIPT -->

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> --}}


<script src="https://code.jquery.com/jquery-3.6.0.min.js"
integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>

<script src="{{ URL::asset('assets/js/pages/select2.init.js') }}"></script>

<script src="{{ asset('assets/js/helper.js') }}"></script>

@include('layouts.vendor-scripts')
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/delete-record.js') }}"></script>

@if (Session::has('success'))  
<script>
showToastbar('success',"{{ Session::get('message') }}")
</script>
@elseif ((Session::has('error')))
<script>
showToastbar("error","{{ Session::get('error') }}");
</script>
@endif
</body>
</html>