@extends('layouts.master')

@section('title')
 @if(isset($user)) Edit @else Add @endif HOF / Family
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/classic.min.css')}}" />
<!-- 'classic' theme -->
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/monolith.min.css')}}" />
<!-- 'monolith' theme -->
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/nano.min.css')}}" />
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{route('hof.index')}}">HOFs</a> @endslot
@slot('title') @if(isset($user)) Edit @else Add @endif HOF / Family @endslot
@endcomponent


<div class="row">
	<div class="col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist"
					aria-orientation="vertical">
					<a class="nav-link {{(request()->has('member') &&$user->member_code==request('member')) ? 'active show' : (!request()->has('member') ? 'active show' : '')}} member-tab" id="member-{{$user->member_code ?? 'HOF'}}" data-bs-toggle="pill" href="#member-{{$user->member_code ?? 'HOF'}}-tab-body" data-member="{{$user->member_code ?? 'HOF'}}"
						role="tab" aria-controls="member-{{$user->member_code ?? 'HOF'}}-tab-body" aria-selected="false" tabindex="-1">
						@if(isset($user->id))
						{{ $user->middle_name}} (HOF)
						@else
						Add HOF Details
						@endif
					</a>
					@if(isset($user))
						@foreach($user->members as $member)
							<a class="nav-link member-tab {{(request()->has('member') &&$member->member_code==request('member')) ? 'active show' : ''}}" id="member-{{$member->member_code}}" data-bs-toggle="pill" href="#member-{{$member->member_code}}-tab-body"
								role="tab" aria-controls="member-{{$member->member_code}}-tab-body" aria-selected="true" data-member="{{$member->member_code ?? 'HOF'}}">
								{{$member->middle_name}} ( {{$member->userDetail->hofRelation->title}})
							</a>
						@endforeach
					
						{{-- Keep this last as it is add form link --}}
					<a class="nav-link member-tab {{(!request()->has('member') || (request()->has('member') && request('member') == 'new-member')) ? 'active show' : ''}}" id="new-member-form-tab" data-bs-toggle="pill"
						href="#new-member-form-tab-body" role="tab"  data-member="new-member" aria-controls="new-member-form-tab-body" aria-selected="true">
							Add New Member
					</a>
					@endif
				</div>
			</div>
		</div>
	</div> <!-- end col-->
	<div class="col-lg-9">
		<div class="tab-content text-muted mt-3 mt-lg-0">
			<div class="tab-pane fade {{(request()->has('member') && $user->member_code==request('member')) ? 'active show' : (!request()->has('member') ? 'active show' : '')}}" id="member-{{$user->member_code ?? 'HOF'}}-tab-body" role="tabpanel"
				aria-labelledby="member-{{$user->member_code ?? 'HOF'}}">
				<div class="d-flex mb-4">
					<div class="flex-grow-1">
						@include('users.form_data',['userdata' => $user ?? null ])
					</div>
				</div>
			</div>
			
			@if(isset($user))
			@foreach($user->members as $member)
				<div class="tab-pane fade {{(request()->has('member') &&$member->member_code==request('member')) ? 'active show' : ''}}" id="member-{{$member->member_code}}-tab-body" role="tabpanel" aria-labelledby="member-{{$member->member_code}}">
					<div class="d-flex mb-4">
						<div class="flex-grow-1">
							@include('users.form_data',['userdata' => $member])
						</div>
					</div>
				</div>
			@endforeach
			{{-- New Member form --}}
			<div class="tab-pane fade {{(!request()->has('member') || (request()->has('member') && request('member') == 'new-member')) ? 'active show' : ''}}" id="new-member-form-tab-body" role="tabpanel"
				aria-labelledby="new-member-form-tab">
				<div class="d-flex mb-4">
					<div class="flex-grow-1">
						@include('users.form_data_new_member')
					</div>
				</div>
			</div>
			<!--end tab-pane-->
			@endif
		</div>
	</div> <!-- end col-->
</div>


@endsection
@section('script')
<script type="text/javascript" src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

<script src="{{ asset('assets/js/users/user-form-validation.js') }}"></script>
<script src="{{ asset('assets/js/users/user-form.js') }}"></script>
<script src="{{ asset('assets/js/common/country-state-city-dependency.js') }}"></script>
 
@endsection