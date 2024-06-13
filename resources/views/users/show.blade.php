@extends('layouts.master')

@section('title')
User Detail
@endsection
@section('css')
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{route('hof.index')}}">Users</a> @endslot
@slot('title') Detail @endslot
@endcomponent


<div class="row">
	@foreach ($familyMembers as $member)
		<div class="col-lg-12 master-board">
			
			<div class="card ribbon-box left">
				@if($member['is_hof'])
					{{-- <div class="ribbon-two ribbon-two-primary"><span>HOF</span></div> --}}
					<div class="ribbon ribbon-danger ribbon-shape mt-2"><span>HOF</span></div>
				@endif
				<div class="card-header align-items-center d-flex">
					<h4 class="card-title mb-0 flex-grow-1" style="{{($member['is_hof']) ? 'margin-left:60px;' : '' }}">{{$member['full_name']}} <span
						class="badge {{($member['is_hof']) ? 'badge-gradient-primary' : 'badge-gradient-success' }} ">{{$member['relation']}}</span></h4>
					<div>
						<button type="button" class="btn btn-soft-secondary btn-sm material-shadow-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit Member">
							<i class=" bx bx-edit"></i>
						</button>
						<a href="{{route('hof.pdf', ['user' => encrypt($member['id'])])}}" class="btn btn-soft-primary btn-sm material-shadow-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Download PDF">
							<i class="bx bx-download"></i>
						</a>
						<a href="#" class="btn btn-soft-danger btn-sm material-shadow-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete Member">
							<i class="bx bx-trash"></i>
						</a>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-2">
							<div class="avatar-lg">
								<img src="{{$member['avatar_url']}}" alt="user-img" class="img-thumbnail rounded-circle">
							</div>
							 
						</div>
						<div class="col-lg-10">
							<div class="row mb-4 sm-mt-4">
								<div class="col-lg-4">
									<div class="hstack -50 gap-1 mb-1">
										<strong>Member Code</strong>: {{$member['member_code']}}
									</div>
									<div class="hstack -50 gap-1 mb-1">
										<strong>Family Code</strong>: {{$member['family_code']}}
									</div>
									<div class="hstack -50 gap-1 mb-1">
										<strong>Phone</strong>: {{$member['phone'] ?? '--'}}
									</div>
									<div class="hstack -50 gap-1 mb-1">
										<strong>Email</strong>: {{$member['email'] ?? '--'}}
									</div>
									<div class="hstack -50 gap-1 mb-1">
										<strong>Date of Birth</strong>: {{$member['dob']}}
									</div>
								</div>
								<div class="col-lg-3">
									
									<div class="hstack -50 gap-1 mb-1">
										<strong>Age</strong>: {{$member['age']}}
									</div>
									<div class="hstack -50 gap-1 mb-1">
										<strong>Gender</strong>: {{$member['gender']}}
									</div>
									<div class="hstack -50 gap-1 mb-1">
										<strong>Blood Group</strong>: {{$member['blood_group'] ?? '--'}}
									</div>
									<div class="hstack -50 gap-1 mb-1">
										<strong>Weight</strong>: {{$member['weight'] ?? '--'}}
									</div>
									<div class="hstack -50 gap-1 mb-1">
										<strong>Height</strong>: {{$member['height'] ?? '--'}}
									</div>
								</div>
								
								<div class="col-lg-5">
									<div class="hstacka -50 gap-1 mb-1">
										<strong>Education</strong>: {{$member['education'] ?? '--'}} {{$member['education_detail']}}
									</div>
									<div class="hstacka -50 gap-1 mb-1">
										<strong>Occupation</strong>: {{$member['occupation'] ?? '--'}} {{$member['occupation_detail']}}
									</div>
									<div class="hstack -50 gap-1 mb-1">
										<strong>Native Village </strong>: {{$member['native_village'] ?? '--'}}
									</div>
									<div class="hstacka -50 gap-1 mb-1">
										<strong>Address  </strong>: {{$member['address']}}, {{$member['city']}}, {{$member['state']}} ,
										{{$member['country']}}
									</div>
									 
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
</div>

@endsection 
@section('script')
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
@endsection
