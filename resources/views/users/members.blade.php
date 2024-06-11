@extends('layouts.master')

@section('title')
Members
@endsection
@section('css')
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/classic.min.css')}}" />
<!-- 'classic' theme -->
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/monolith.min.css')}}" />
<!-- 'monolith' theme -->
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/nano.min.css')}}" /> <!-- 'nano' theme -->
@endsection
@section('content')
@component('components.breadcrumb')
{{-- @slot('li_1') Profile @endslot --}}
@slot('title') Members @endslot
@endcomponent

<div class="row">
	<div class="col-lg-12 master-board">
		<div class="card" id="customerList">
			<div class="card-body border-bottom-dashed border-bottom">
				<div class="row justify-content-start">
					<div class="col-lg-3 col-sm-3">
						<a href="{{route('hof.create')}}" class=" mt-1 btn btn-primary waves-effect waves-light">
							<i class="ri-add-line align-bottom me-1"></i>
							Add HOF / Famliy
						</a>
					</div>
				</div>
			</div>

			<div class="card-body">
				<div class="row justify-content-end">
					<div class="col-lg-2 col-md-2">
						<div class="mb-3">
							<label for="userBloodGroup">Blood Group </label>
							<select class="form-select mb-3 mt-1 js-example-basic-single data-custom-filters" id="userBloodGroup" data-target-index="5" >
								<option value="">All</option>
								@foreach($blood_groups as $key => $blood_group)
								<option value="{{$key}}">{{$blood_group}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-2 col-md-2">
						<div class="mb-3">
							<label for="userMaritalStatus">Marrital Status </label>
							<select class="form-select mb-3 mt-1 js-example-basic-single data-custom-filters" id="userMaritalStatus" data-target-index="4" >
								<option value="">All</option>
								@foreach($marital_statuses as $key => $marital_status)
								<option value="{{$key}}">{{$marital_status}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-2 col-md-2">
						<div class="mb-3">
							<label for="userGender">Gender </label>
							<select class="form-select mb-3 mt-1 data-custom-filters" id="userGender" data-target-index="8" >
								<option value="">All</option>
								@foreach($gender as $key => $genderName)
								<option value="{{$key}}">{{$genderName}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-2 col-md-2">
						<div class="mb-3">
							<label for="userTypeStatus">Status </label>
							<select class="form-select mb-3 mt-1 data-custom-filters" id="userTypeStatus" data-target-index="9" >
								<option value="">All</option>
								@foreach($status as $key => $userStatus)
								<option value="{{$key}}">{{$userStatus}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3">
						<div class="mb-3">
							<label for="nativeVillage">Native Village </label>
							<select class="form-select mb-3 mt-1 js-example-basic-single data-custom-filters" id="nativeVillage" data-target-index="3" >
								<option value="">All</option>
								@foreach($native_villages as $key => $nativeVillage)
								<option value="{{$key}}">{{$nativeVillage}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3">
						<div class="mb-3">
							<label for="education">Education </label>
							<select class="form-select mb-3 mt-1 js-example-basic-single data-custom-filters data-custom-filters" id="education" data-target-index="6" >
								<option value="">All</option>
								@foreach($educations as $key => $education)
								<option value="{{$key}}">{{$education}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3">
						<div class="mb-3">
							<label for="occupation">Occupation </label>
							<select class="form-select mb-3 mt-1 js-example-basic-single data-custom-filters" id="occupation" data-target-index="7" >
								<option value="">All</option>
								@foreach($occupations as $key => $occupation)
								<option value="{{$key}}">{{$occupation}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<table id="memberDataTable" class="dataTableClass table nowrap align-middle" style="width:100%"
						data-load="{{ route('members.index') }}">
						<thead>
							<tr>
								<th data-index="0" >First Name</th>
								<th data-index="1" >Email</th>
								<th data-index="2" >Phone</th>
								<th data-index="3">Native Village</th>
								<th data-index="4" >Marrital Status</th>
								<th data-index="5" >Blood Group</th>
								<th data-index="6" >Education</th>
								<th data-index="7" >Occupation</th>
								<th data-index="8" >Gender</th>
								<th data-index="9" >Status</th>
								<th width="100px" data-index="10">Action</th>
								<th data-index="11" >Middle Name</th>
								<th data-index="12" >Last Name</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
@include('common.data-table-scripts')
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/users/member-list.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
@endsection