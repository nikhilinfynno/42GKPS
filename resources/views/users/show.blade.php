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
	<div class="col-lg-3 master-board">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="avatar-sm flex-shrink-0">
						<span class="avatar-title bg-light text-primary rounded-circle fs-3">
							<i class="ri-wallet-3-fill align-middle"></i>
						</span>
					</div>
					<div class="flex-grow-1 ms-3">
						<p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Wallet Balance</p>
						<h4 class=" mb-0">{{config('constant.DEFAULT_CURRENCY_SYMBOL')}}<span class="counter-value"
								data-target="{{$user->wallet->balance ?? 0}}">{{$user->wallet->balance ?? 0}}</span></h4>
					</div>
		
				</div>
			</div><!-- end card body -->
		</div>
		<div class="card pricing-box ribbon-box right">
			<div class="card-body p-4 m-2">
				@if(isset($user->latestSubscription))
					@if(isset($user->latestSubscription->is_active) &&$user->latestSubscription->is_active == 1)
						<div class="ribbon-two ribbon-two-success"><span>Active</span></div>
					@else
						<div class="ribbon-two ribbon-two-danger"><span>Cancelled</span></div>
					@endif
					<div>
						<div class="d-flex align-items-center">
							<div class="flex-grow-1">
								<h5 class="mb-1 fw-semibold">{{$user->latestSubscription->plan->name}}</h5>
							</div>
						</div>
			
						<div class="pt-4">
							<h2><sup><small>{{config('constant.DEFAULT_CURRENCY_SYMBOL')}}</small></sup> {{$user->latestSubscription->plan->price}}<span class="fs-13 text-muted">/{{$user->latestSubscription->plan->frequency}} Days</span></h2>
						</div>
					</div>
					<hr class="my-4 text-muted">
					<div>
						<ul class="list-unstyled vstack gap-3 text-muted">
							<li>
								<div class="d-flex">
									<div class="flex-shrink-0 text-success me-1">
										<i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
									</div>
									<div class="flex-grow-1">
										Upto <b>{{$user->latestSubscription->plan->number_of_posts}}</b> Post <strong>{{
										\App\Models\Plan::TYPE[$user->latestSubscription->plan->type]}}</strong>
									</div>
								</div>
							</li>
						</ul>
					</div>
				@else
					<div class="ribbon-two ribbon-two-primary"><span>Free</span></div>
					<div>
						<p class="text-muted mb-0 text-center">No Plan Subscribed</p>
					</div>
				@endif
			</div>
		</div>
		<div class="card">
			<div class="card-body border-bottom-dashed border-bottom">
				<div class="row">
					<div class="col-lg-12 col-sm-12">
						<h6 class="card-title mb-0 flex-grow-1">User Detail</h6>
					</div>
				</div>
			</div>
			<div class="card-body">
				 <div class="mb-3">
					<label for="firstnameInput" class="form-label">First Name</label>
					<input  type="text" class="form-control" placeholder="Enter your First Name" value="{{$user->first_name}}" readonly>
				</div>
				<div class="mb-3">
					<label for="firstnameInput" class="form-label">Last Name</label>
					<input  type="text" class="form-control" placeholder="Enter your Last Name" value="{{$user->last_name}}" readonly>
				</div>
				<div class="mb-3">
					<label for="firstnameInput" class="form-label">Phone Number</label>
					<div class="input-group">
						<div class="input-group-text">{{$user->country_code ?? '+1'}}</div>
						<input type="text" class="form-control" id="inlineFormInputGroupUsername" placeholder="Username" readonly value="{{$user->phone ?? '--'}}">
					</div>
				</div>
				<div class="mb-3">
					<label for="firstnameInput" class="form-label">Email</label>
					<input type="text" class="form-control" placeholder="Enter your Last Name" value="{{$user->email}}" readonly>
				</div>
			</div>
		</div>
		
	</div>
	<div class="col-lg-9 master-board">
		<div class="card">
			<div class="card-body border-bottom-dashed border-bottom">
				<div class="row">
					<div class="col-lg-12 col-sm-12">
						<h6 class="card-title mb-0 flex-grow-1">User Subscriptions</h6>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="row justify-content-end">
					<div class="col-lg-2 col-md-2">
						<div class="mb-3">
							<label for="subscriptionStatus">Status </label>
							<select class="form-select mb-3 " id="subscriptionStatus">
								<option value="">Show All</option>
								@foreach ($subscriptionStatus as $key => $item)
								<option value="{{$key}}">{{$item}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<table id="userSubscriptionDataTable" class="table nowrap align-middle" style="width:100%"
						data-load="{{ route('user.subscriptions',['user'=>$user]) }}">
						<thead>
							<tr>
								<th>Plan</th>
								<th>Amount</th>
								<th>Start At</th>
								<th>Exprires At</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body border-bottom-dashed border-bottom">
				<div class="row">
					<div class="col-lg-12 col-sm-12">
						<h6 class="card-title mb-0 flex-grow-1">User Wallet Transactions</h6>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="row justify-content-end">
				</div>
				<div class="row">
					<table id="userWalletDataTable" class="table nowrap align-middle" style="width:100%" data-load="{{ route('user.wallet',['user'=>$user]) }}">
						<thead>
							<tr>
								<th>Transaction Id</th>
								<th>Payment Id</th>
								<th>Amount</th>
								<th>Type</th>
								<th>Status</th>
								<th>Description</th>
								<th>Date</th>
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
<script src="{{ asset('assets/js/users/user-view.js') }}"></script>
@endsection
