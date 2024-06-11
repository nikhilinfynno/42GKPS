<form id="postForm" action="{{ isset($userdata) ? route('hof.update', ['user' => $userdata->crypt_id,'member'=>$userdata->member_code]) : route('hof.store') }}" method="POST" novalidate enctype="multipart/form-data">
	<div class="row">
		
		<div class="col-lg-12 master-board">
			<div class="card">
				<div class="card-header align-items-center d-flex">
					<h4 class="card-title mb-0 flex-grow-1">Personal Details</h4>
				</div>
				<div class="card-body">
					@if(isset($userdata))
					@method('put')
					@endif
					@csrf
					<input type="hidden" name="type" value="{{(isset($userdata)) ? $userdata->user_type : '1'}}"/>
					<div class="row">
						<div class="col-lg-3">
							 <div class="mb-5">
								<label for="prefix">Prefix <span class="text-danger">*</span></label>
								@php
								$selectedPrefix = isset($userdata->prefix) ? $userdata->prefix : old('prefix');
								@endphp
								<select class="form-select mb-5 " name="prefix">
									@foreach ($prefixes as $prefix)
										<option value="{{$prefix}}" {{($selectedPrefix==$prefix) ? "selected='selected'" : ""}}>{{$prefix}}</option>
									@endforeach
								</select>
								@error('prefix')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>  
						<div class="col-lg-3">
							<div class="mb-5">
								<label for="first_name">First Name <span class="text-danger">*</span></label>
								<input class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" type="text"
									placeholder="Surname" value="{{ isset($userdata->first_name) ? $userdata->first_name : (old('first_name') ? old('first_name') : 'Patel') }}">
								@error('first_name')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="col-lg-3">
							<div class="mb-5">
								<label for="middle_name">Middle Name <span class="text-danger">*</span></label>
								<input class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name"
									type="text" placeholder="Own Name"
									value="{{ isset($userdata->middle_name) ? $userdata->middle_name : old('middle_name') }}">
								@error('middle_name')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="col-lg-3">
							<div class="mb-5">
								<label for="last_name">Last Name <span class="text-danger">*</span></label>
								<input class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name"
									type="text" placeholder="Father / Husband Name"
									value="{{ isset($userdata->last_name) ? $userdata->last_name : old('last_name') }}">
								@error('last_name')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						@if(isset($userdata->user_type) && $userdata->user_type != 1)
						<div class="col-lg-3">
							<div class="mb-5">
								<label for="relation">Relation with HOF <span class="text-danger">*</span></label>
								@php
								$selectedRelation = isset($userdata->userDetail->relation_id) ? $userdata->userDetail->relation_id : old('relation');
								$showSelf=true;
								@endphp
								<select class="form-select mb-5 " name="relation">
									@foreach ($relations as $relation)
									@if((isset($userdata) && $userdata->user_type != App\Models\User::HOF) && $relation->id == \App\Models\HofRelation::RELATION_SELF)
									@php 
									 	continue;
									@endphp
									@endif
								 
									<option value="{{$relation->id}}" {{($selectedRelation == $relation->id) ? 'selected' : ''}} {{($selectedPrefix==$relation->id) ? "selected='selected'" : ""
										}}>{{$relation->title}}</option>
										
									@endforeach
								</select>
								@error('relation')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
						@endif
						<div class="{{(isset($userdata->user_type) && $userdata->user_type != 1) ? 'col-lg-5' : 'col-lg-8'}}">
							<div class="mb-5">
								<label for="email">Email @if(isset($userdata->user_type) && $userdata->user_type==1)<span class="text-danger">*</span>@endif</label>
								<input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email"
									placeholder="fullname@mail.com" value="{{ isset($userdata->email) ? $userdata->email : old('email') }}">
								@error('email')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="col-lg-4">
							<div class="mb-5">
								<label for="phone">Phone number @if(isset($userdata->user_type) && $userdata->user_type==1)<span class="text-danger">*</span>@endif</label>
								<div class="input-group" data-input-flag id="phone_code" data-url="{{asset('')}}">
									@php 
										$selectedPhoneCode = ''
									@endphp
									<select name="country_code" style="max-width: 70px;" class="form-control">
										@foreach ($phone_codes as $phone_code)
										@php 
											$selectedPhoneCode = isset($userdata->country_code) && $userdata->country_code == $phone_code ? 'selected' : (old('country_code') === $phone_code ? 'selected' : ($phone_code
											== '+91' ? 'selected' : ''));
										@endphp
											<option {{$selectedPhoneCode}} value="{{$phone_code}}">{{$phone_code}}</option>
										@endforeach
									</select>
									<input type="text" class="form-control rounded-end flag-input" value="{{ isset($userdata->phone) ? $userdata->phone : old('phone') }}" name="phone" placeholder="Enter number" id="phone"
										oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10"/>
										
								</div>
								@error('phone')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-4">
							<div class="mb-5">
								<label for="dob">Date Of Birth</label>
								<input type="text" class="form-control flatpickr-input active" data-provider="flatpickr" data-date-format="d M, Y"
									data-mindate="25 12, 2021" value="{{ isset($userdata->userDetail->dob) ? $userdata->userDetail->dob : old('dob') }}" name="dob" readonly="readonly">
								 
								@error('dob')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="col-lg-4">
							<div class="mb-5">
								<label for="weight">Weight<span class="text-danger">*</span></label>
								<input class="form-control @error('weight') is-invalid @enderror" id="weight"
									name="weight" type="text" placeholder="55"
									  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="3" value="{{ isset($userdata->userDetail->weight) ? $userdata->userDetail->weight : old('weight') }}">
								 
								@error('weight')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="col-lg-4">
							<div class="mb-5">
								<label for="height">Select Height<span class="text-danger">*</span></label>
								@php
								$selectedHeight = '';
								@endphp
								<select class="form-select mb-5 js-example-basic-single" name="height">
									@foreach ($human_heights as $human_height)
									
									@php
									$selectedHeight = isset($userdata->height) && $userdata->height == $human_height ? 'selected' :
									(old('human_height') === $human_height ? 'selected' : ($human_height
									== "5'6\"" ? 'selected' : ''));
									@endphp
									<option value="{{$human_height}}" {{($selectedHeight==$human_height) ? "selected='selected'" : "" }}>
										{{$human_height}}
									</option>
									@endforeach
								</select>
								@error('height')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-4">
							
							<div class="row mb-2">
								<label for="marital_status">Gender<span class="text-danger">*</span></label>
								@php 
								$selectedGender = '';
								$selectedGenderGroup = 'new';
								@endphp
								@foreach($genders as $key => $gender)
								@php
								$selectedGender = isset($userdata->userDetail->gender) && $userdata->userDetail->gender == $key ? 'checked' : (old('gender') == $key ? 'checked' : ($key
								== 1 ? 'checked' : ''));
								if(isset($userdata->member_code)){
									$selectedGenderGroup = $userdata->member_code;
								}
								@endphp
									<div class="col-lg-4">
										<input class="form-check-input" value="{{$key}}" type="radio" {{$selectedGender}} name="gender" id="{{$gender}}-{{$key}}-{{$selectedGenderGroup}}">
										<label class="form-check-label" for="{{$gender}}-{{$key}}-{{$selectedGenderGroup}}">
											 {{$gender}}
										</label>
									</div>
								@endforeach
							</div>
							<div class="row mb-2">
							<div class="mt-5">
								<label for="blood_group">Blood Group<span class="text-danger">*</span></label>
								@php
								$selectedBloodGroup = isset($userdata->userDetail->blood_group) ? $userdata->userDetail->blood_group : old('blood_group');
								@endphp
								<select class="form-select mb-5 js-example-basic-single" name="blood_group">
									@foreach ($bloodGroups as $key => $bloodGroup)
									<option value="{{$key}}" {{($selectedBloodGroup==$key) ? "selected='selected'" : "" }}>{{$bloodGroup}}
									</option>
									@endforeach
								</select>
								@error('blood_group')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="row mb-2">
								<div class="mb-1">
									<label for="marital_status">Marital Status<span class="text-danger">*</span></label>
									@php
									$selectedMaritalStatus = isset($userdata->userDetail->marital_status) ? $userdata->userDetail->marital_status : old('marital_status');
									@endphp
									<select class="form-select mb-5 js-example-basic-single" name="marital_status">
										@foreach ($maritalStatuses as $key => $maritalStatus)
										<option value="{{$key}}" {{($selectedMaritalStatus==$key) ? "selected='selected'" :
											"" }}>{{$maritalStatus}}</option>
										@endforeach
									</select>
									@error('marital_status')
									<label class="error m-t-1 text-danger">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="row mb-2">
								<div class="mt-4">
									<label for="user_status">User Status<span class="text-danger">*</span></label>
									@php
									$selectedMemberStatus = isset($userdata->status) ? $userdata->status :
									old('status');
									$memberStatus = (isset($userdata->user_type) && $userdata->user_type==2) ? $member_status : $hof_status;
									@endphp
									 
									<select class="form-select mb-5 js-example-basic-single" name="status">
										@foreach ($memberStatus as $key => $status)
										<option value="{{$key}}" {{($selectedMemberStatus==$key) ? "selected='selected'" : "" }}>{{$status}}
										</option>
										@endforeach
									</select>
									@error('status')
									<label class="error m-t-1 text-danger">{{ $message }}</label>
									@enderror
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="mb-3">
								<p class="text-muted">Add User Image under <strong>1MB</strong> in size.</p>
								<div class="text-center">
									<div class="position-relative d-inline-block">
										<div class="position-absolute top-100 start-100 translate-middle">
											<label for="user-image-input" class="mb-0" data-bs-toggle="tooltip" data-bs-placement="right"
												title="Select Image">
												<div class="avatar-xs">
													<div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
														<i class="ri-image-fill"></i>
													</div>
												</div>
											</label>
											<input class="form-control d-none" value="" id="user-image-input" name="image" type="file"
												accept="image/png, image/jpeg">
										</div>
										<div class="avatar-lg">
											<div class="avatar-title bg-light rounded">
												<img src="{{isset($userdata->avatar_url) ? $userdata->avatar_url:''}}" id="user-img"
													class="avatar-md h-auto" />
											</div>
										</div>
									</div>
								</div>
								@error('image')
								<label class="error mt-2 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header align-items-center d-flex">
					<h4 class="card-title mb-0 flex-grow-1">Education and Occupation Details</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-6">
							<div class="mb-5">
								<label for="occupation_id">Occupation<span class="text-danger">*</span></label>
								@php
								$selectedOccupation = isset($userdata->userDetail->occupation_id) ? $userdata->userDetail->occupation_id : old('occupation_id');
								@endphp
								<select class="form-select mb-5 js-example-basic-single" name="occupation_id">
									<option value="">Select Occupation</option>
									@foreach ($occupations as $occupation)
									<option value="{{$occupation->id}}" {{($selectedOccupation==$occupation->id) ? "selected='selected'" :
										"" }}>{{$occupation->name}}</option>
									@endforeach
								</select>
								@error('prefix')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-5">
								<label for="occupation_detail">Occupation Detail </label>
								<input class="form-control @error('occupation_detail') is-invalid @enderror" id="occupation_detail"
									name="occupation_detail" type="text" placeholder="Structure Designer"
									value="{{ isset($userdata->userDetail->occupation_detail) ? $userdata->userDetail->occupation_detail : old('occupation_detail') }}">
								@error('occupation_detail')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-5">
								<label for="education_id">Education<span class="text-danger">*</span></label>
								@php
								$selectedEducation = isset($userdata->userDetail->education_id) ? $userdata->userDetail->education_id : old('native_village_id');
								@endphp
								<select class="form-select mb-5 js-example-basic-single" name="education_id">
									<option value="">Select Education</option>
									@foreach ($educations as $education)
									<option value="{{$education->id}}" {{($selectedEducation==$education->id) ? "selected='selected'" :
										"" }}>{{$education->title}}</option>
									@endforeach
								</select>
								@error('prefix')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-5">
								<label for="education_detail">Education Detail </label>
								<input class="form-control @error('education_detail') is-invalid @enderror" id="education_detail"
									name="education_detail" type="text" placeholder="Structure Designer"
									value="{{ isset($userdata->userDetail->education_detail) ? $userdata->userDetail->education_detail : old('education_detail') }}">
								@error('education_detail')
								<label class="error m-t-1 text-danger">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="{{(isset($userdata->user_type) && $userdata->user_type==2) ? $userdata->member_code : 'HOF' }}-member-address">
				<div class="card-header align-items-center d-flex">
					<h4 class="card-title mb-0 flex-grow-1">Address Details</h4>
					@if(isset($userdata) && $userdata->user_type==2)
					<a href="javascript:void(0)" data-section="{{(isset($userdata->user_type) && $userdata->user_type==2) ? $userdata->member_code : 'HOF' }}-member-address" class="btn btn-info address-clone">Same
						As HOF</a>
					@endif
				</div>
				<div class="card-body">
				<div class="row">
					<div class="col-lg-3">
						<div class="mb-5">
							<label for="native_village_id">Native Village <span class="text-danger">*</span></label>
							@php
							$selectedNative = isset($userdata->userDetail->native_village_id) ? $userdata->userDetail->native_village_id : old('native_village_id');
							@endphp
							<select class="form-select mb-5 js-example-basic-single" name="native_village_id">
								<option value="">Select Native Village</option>
								@foreach ($nativeVillages as $nativeVillage)
								<option value="{{$nativeVillage->id}}" {{($selectedNative==$nativeVillage->id) ? "selected='selected'" : "" }}>{{$nativeVillage->name}}</option>
								@endforeach
							</select>
							@error('native_village_id')
							<label class="error m-t-1 text-danger">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="col-lg-9">
						<div class="mb-5">
							<label for="address">Address <span class="text-danger">*</span></label>
							<input class="form-control @error('address') is-invalid @enderror"  
								name="address" type="text" placeholder="House number, street ,area"
								value="{{ isset($userdata->userDetail->address) ? $userdata->userDetail->address : old('address') }}">
							@error('address')
							<label class="error m-t-1 text-danger">{{ $message }}</label>
							@enderror
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4">
						<div class="mb-5">
							<label for="country_id_{{$userdata->id ?? 'hof'}}">Country<span class="text-danger">*</span></label>
							@php
							$selectedCountry = isset($userdata->userDetail->country_id) ? $userdata->userDetail->country_id : old('country_id');
							@endphp
							<select class="form-select mb-5 js-example-basic-single country_id" name="country_id" id="country_id_{{$userdata->id ?? 'hof'}}" data-id="{{$userdata->id ?? 'hof'}}" data-url="{{route('location.countries')}}" data-selected="{{$selectedCountry}}">
								{{-- @foreach ($countries as $country) --}}
								{{-- <option value="{{$country->id}}" {{($selectedCountry==$country->id) ? "selected='selected'" :
									"" }}>{{$country->name}}</option>
								@endforeach --}}
							</select>
							@error('country_id')
							<label class="error m-t-1 text-danger">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="col-lg-4">
						<div class="mb-5">
							<label for="state_id_{{$userdata->id ?? 'hof'}}">State<span class="text-danger">*</span></label>
							@php
							$selectedState = isset($userdata->userDetail->state_id) ? $userdata->userDetail->state_id : old('state_id');
							@endphp
							<select class="form-select mb-5 js-example-basic-single state_id" name="state_id" id="state_id_{{$userdata->id ?? 'hof'}}" data-id="{{$userdata->id ?? 'hof'}}"
								data-url="{{route('location.states')}}" data-selected="{{$selectedState}}">
									
							</select>
							@error('state_id')
							<label class="error m-t-1 text-danger">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="col-lg-4">
						<div class="mb-5">
							<label for="city_id_{{$userdata->id ?? 'hof'}}">City<span class="text-danger">*</span></label>
							@php
							$selectedState = isset($userdata->userDetail->city_id) ? $userdata->userDetail->city_id : old('city_id');
							@endphp
							<select class="form-select mb-5 js-example-basic-single city_id	" name="city_id" id="city_id_{{$userdata->id ?? 'hof'}}" data-url="{{route('location.cities')}}" data-selected="{{$selectedState}}" data-id="{{$userdata->id ?? 'hof'}}">
					
							</select>
							@error('city_id')
							<label class="error m-t-1 text-danger">{{ $message }}</label>
							@enderror
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<div class="row">
		<div class="col-lg-12">
			<button class="btn btn-success saveFrom" data-action="" type="submit">Save</button>
			<a href="{{route('hof.index')}}" class="btn btn-light waves-effect" />Cancel</a>
		</div>
	</div>
</form>
 