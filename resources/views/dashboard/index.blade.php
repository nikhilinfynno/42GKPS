@extends('layouts.master')

@section('title')
Dashboard
@endsection

@section('css')
<style>
    .dashboard-opportunity-assigned {
        position: unset !important;
    }
</style>
@endsection

@section('content')
<div class="row">
        <div class="h-100 ">
            <div class="row mb-3 pb-1">
                <div class="col-lg-6">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">{{ $dayType }}, {{ auth()->user()->name }}</h4>
                            <p class="text-muted mb-0">Here's what's happening with your app
                                today.</p>
                        </div>
                    </div><!-- end card header -->
                </div>
                <div class="col-lg-6">
                    <div class="row justify-content-sm-end">
                        
                    </div>
                </div>
                <!--end col-->
            </div>
            <div class="row h-100">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{route('hof.index')}}" id="postTile">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                            <i class="bx bxl-blogger align-middle"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Total Familes</p>
                                        <h4 class=" mb-0"><span class="counter-value" id="totalPost"
                                                data-target="{{$totalFamilies}}">{{$totalFamilies}}</span></h4>
                                    </div>
                                </div>
                            </a>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{route('members.index')}}" id="userTile">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                            <i class="bx bx-user-circle align-middle"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Total Members</p>
                                        <h4 class=" mb-0"><span class="counter-value" id="totalUsers" data-target="{{$totalUsers}}">{{$totalUsers}}</span></h4>
                                    </div>
                                </div>
                            </a>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
                
            </div>
        </div>
        <span id="dashboardRoute" data-action={{ route('dashboard') }}></span>
        @endsection

        @section('script')
               <script type="text/javascript" src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ asset('assets/js/dashboard/dashboard.js') }}"></script>
        @endsection