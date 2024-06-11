@extends('layouts.master')

@section('title')
HOFs
@endsection
@section('css')
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/classic.min.css')}}" /> <!-- 'classic' theme -->
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/monolith.min.css')}}" /> <!-- 'monolith' theme -->
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/nano.min.css')}}" /> <!-- 'nano' theme -->
@endsection
@section('content')
@component('components.breadcrumb')
{{-- @slot('li_1') Profile @endslot --}}
@slot('title') HOFs @endslot
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
					{{-- <div class="col-lg-2 col-md-2">
						<div class="mb-3">
							<label for="userTypeStatus"> </label>
							<select class="form-select mb-3 mt-1" id="userTypeStatus">
								<option value="active">Active Users</option>
								<option value="deleted">Deleted Users</option>
							</select>
						</div>
					</div> --}}
					<div class="col-lg-2 col-md-2">
						<div class="mb-3">
							<label for="userTypeStatus">Status </label>
							<select class="form-select mb-3 mt-1" id="userTypeStatus">
								<option value="">All</option>
								@foreach($status as $key => $userStatus)
								<option value="{{$key}}">{{$userStatus}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-2 col-md-2">
						<div class="mb-3">
							<label for="nativeVillage">Native Village </label>
							<select class="form-select mb-3 mt-1 js-example-basic-single" id="nativeVillage">
								<option value="">All</option>
								@foreach($native_villages as $nativeVillage)
								<option value="{{$nativeVillage->id}}">{{$nativeVillage->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<table id="userDataTable" class="dataTableClass table nowrap align-middle" style="width:100%" data-load="{{ route('hof.index') }}">
						<thead>
							<tr>
								<th>First Name</th>
								<th>Email</th>
								<th>phone</th>
								<th>Native Village</th>
								<th>Gender</th>
								<th>Status</th>
								<th width="100px">Action</th>
								<th>Middle Name</th>
								<th>Last Name</th>
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
	<script src="{{ asset('assets/js/users/user-list.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
@endsection
