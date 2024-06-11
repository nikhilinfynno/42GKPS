@extends('layouts.master-without-nav')

@section('title')
Reset Passowrd
@endsection

@section('content')
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="/" class="d-inline-block auth-logo">
                                    {{-- <h1 class="text-white">{{config('app.name')}}</h1> --}}
                                    <img alt="{{config('app.name')}}" src="{{ URL::asset('assets/images/42-gham-logo-100x100.png') }}" alt="" height="100px">
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Forgot Password?</h5>
                                    
                                </div>
                                <div class="p-2">
                                    <p class="text-muted">Please provide your registered email address to receive the reset link.</p>
                                    <form class="forms-sample" id="passwordResetForm" method="post"
                                        action="{{ route('password.email') }}">
                                        @csrf
                                        <input type="hidden" value="{{ request()->token ?? '' }}" name="p_r_token">
                                        <div class="form-group">
                                            <label for="email"><span class="text-danger">* </span>Email address</label>
                                            <input type="email" class="form-control" id="email" placeholder="Email"
                                                name="email">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="float-left mt-2">
                                            <button type="submit" class="btn btn-success mr-2 mb-2 mb-md-0">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Wait, I remember my password... <a href="{{ route('login') }}"
                                    class="fw-semibold text-primary text-decoration-underline"> Click here </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> {{config('app.name')}} <i class="mdi mdi-heart text-danger"></i></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
@endsection
