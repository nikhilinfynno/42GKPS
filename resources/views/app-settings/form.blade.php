@extends('layouts.master')

@section('title')
 App Settings
@endsection
@section('css')
<!-- quill css -->
<link href="{{asset('assets/libs/quill/quill.core.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/quill/quill.bubble.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/quill/quill.snow.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('components.breadcrumb')
 
@slot('title') App Settings @endslot
@endcomponent

<form id="appSettingsForm" action="{{route('user.setting.update')}}" method="POST" novalidate enctype="multipart/form-data">
	<div class="row">
		<div class="col-lg-8 master-board">
			<div class="card">
				<div class="card-body">
					@csrf
					<div class="row">
						@if(count($appSettingsData))
						@foreach ($appSettingsData as $key => $appSetting)
						<div class="col-lg-12">
							<div class="mb-3">
								<label for="{{ $key }}">{{ $appSetting['label'] }}<span class="text-danger">*</span></label>
								@if($appSetting['type'] == 'textbox')
								<input class="form-control @error($key) is-invalid @enderror" id="{{ $key }}" name="{{ $key }}" type="text"
									placeholder="{{ $appSetting['label'] }}" value="{{ old($key) ? old($key) : $appSetting['value'] }}">
								@error($key)
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
								@endif
								@if($appSetting['type'] == 'radio')
								<div class="form-check mb-2">
									<input class="form-check-input" type="radio" name="{{ $key }}" id="{{$key}}True" value="1" {{ old($key)==1 ? 'checked' : ''
										}}>
									<label class="form-check-label" for="{{ $key }}True">
										True
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="{{ $key }}" value="0" {{ old($key)==0 ? 'checked' : ''
										}} id="{{$key}}False">
									<label class="form-check-label" for="{{ $key }}False">
										False
									</label>
								</div>
								@endif
							</div>
						</div>
						@endforeach
						<div class="col-lg-12">
							<button class="btn btn-success saveFrom" data-action="" type="submit">Save</button>
							<a href="{{route('dashboard')}}" class="btn btn-light waves-effect" /> Cancel</a>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 master-board">
			 
			 
		</div>
	</div>
</form>

@endsection
@section('script')
<script src="{{ asset('assets/libs/@ckeditor/@ckeditor.min.js') }}"></script>
<!-- quill js -->
<script src="{{ asset('assets/libs/quill/quill.min.js')}}"></script>

<script src="{{ asset('assets/js/post-form.js') }}"></script>
 
@endsection