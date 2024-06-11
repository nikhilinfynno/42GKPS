@extends('layouts.master')

@section('title')
Profile
@endsection

@section('content')
@component('components.breadcrumb')
{{-- @slot('li_1') Profile @endslot --}}
@slot('title') Profile @endslot
@endcomponent

<div class="row">
	<div class="col-lg-12 master-board">
		<div class="card" id="customerList">
			<div class="card-body border-bottom-dashed border-bottom">
				<h6 class="card-title mb-0 flex-grow-1">Update Personal Details</h6>
			</div>
			<div class="card-body">
				<form id="editProfileForm" action="{{ route('update.personal.profile') }}" method="POST" novalidate="">
					@method('put')
					@csrf
					<div class="row">
						<div class="col-lg-6">
							<div class="row">
								<div class="mb-3">
									<label for="first_name">First Name <span class="text-danger">*</span></label>
									<input class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" type="text"
										placeholder="First Name"
										value="{{ isset($user->first_name) ? $user->first_name : old('first_name') }}">
										@error('first_name')
										<label class="error m-t-1 text-danger">{{ $message }}</label>
										@enderror
								</div>
								
								<div class="mb-3">
									<label for="last_name">Last Name <span class="text-danger">*</span></label>
									<input class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" type="text"
										placeholder="Last Name"
										value="{{ isset($user->last_name) ? $user->last_name : old('last_name') }}">
										@error('last_name')
										<label class="error m-t-1 text-danger">{{ $message }}</label>
										@enderror
								</div>
								<div class="mb-3">
									<label for="email">Email <span class="text-danger">*</span></label>
									<input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="text" placeholder="Email"
										value="{{ isset($user->email) ? $user->email : old('email') }}">
										@error('email')
										<label class="error m-t-1 text-danger">{{ $message }}</label>
										@enderror
								</div>
							</div>
						</div>
						<div class="col-lg-6">

							<div class="row">
								<div class="mb-3">
									<label for="current_password">Current Password </label>
									<input class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" type="password"
										placeholder="Current Password">
										@error('current_password')
										<label class="error m-t-1 text-danger">{{ $message }}</label>
										@enderror
								</div>
								<div class="mb-3">
									<label for="password">New Password </label>
									<input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password"
										placeholder="New Password">
										@error('password')
										<label class="error m-t-1 text-danger">{{ $message }}</label>
										@enderror
								</div>
								<div class="mb-3">
									<label for="password_confirmation">Confirm Password </label>
									<input class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation"
										type="password" placeholder="Confirm Password">
										@error('password_confirmation')
										<label class="error m-t-1 text-danger">{{ $message }}</label>
										@enderror
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="mt-3">
								<button class="btn btn-success saveFrom" data-action="" type="submit">Save</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection