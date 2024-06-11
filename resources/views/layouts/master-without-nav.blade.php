<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
    @include('layouts.head-css')
</head>

<body>

    @yield('body')

    @yield('content')

    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    @include('layouts.vendor-scripts')
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    @if (Session::has('status'))
    <script>
        // showToastbar('success',"{{ Session::get('success') }}")
        showToastbar('success',"{{ Session::get('status') }}")
    </script>
    @elseif ((Session::has('error')))
    <script>
        showToastbar("error","{{ Session::get('error') }}");
    </script>
    @endif
</body>

</html>