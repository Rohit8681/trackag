@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        {{-- Page Header --}}
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Admin Management</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Create New Admin</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="card-title mb-0">Create New Admin</div>
                            </div>

                            {{-- Flash Messages --}}
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger alert-dismissible fade show m-3">
                                    <strong>Error:</strong> {{ Session::get('error_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show m-3">
                                    <strong>Success:</strong> {{ Session::get('success_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible fade show m-3">
                                        <strong>Error:</strong> {!! $error !!}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Form Start --}}
                            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">

                                    {{-- Personal Information --}}
                                    <h5 class="mb-3">Personal Information</h5>
                                    <div class="row g-3 mb-4">
                                        {{-- Name, Email, Mobile --}}
                                        <div class="col-md-4">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email') }}" required>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label class="form-label">Mobile</label>
                                            <input type="text" name="mobile" class="form-control"
                                                value="{{ old('mobile') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Company Mobile</label>
                                            <input type="text" name="company_mobile" class="form-control"
                                                value="{{ old('company_mobile') }}">
                                        </div>

                                        {{-- DOB, Gender, Marital Status --}}
                                        <div class="col-md-4">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" name="date_of_birth" class="form-control"
                                                value="{{ old('date_of_birth') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Gender</label>
                                            <select name="gender" class="form-select">
                                                <option value="">Select</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Marital Status</label>
                                            <select name="marital_status" class="form-select">
                                                <option value="">Select</option>
                                                <option value="Single"
                                                    {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single
                                                </option>
                                                <option value="Married"
                                                    {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married
                                                </option>
                                            </select>
                                        </div>

                                        {{-- Address --}}
                                        <div class="col-md-12">
                                            <label class="form-label">Address</label>
                                            <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                                        </div>

                                        {{-- Location Dropdowns --}}
                                        <div class="col-md-3">
                                            <label class="form-label">State</label>
                                            <select name="state_id" id="state" class="form-select">
                                                <option value="">Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">District</label>
                                            <select name="district_id" id="district" class="form-select">
                                                <option value="">Select District</option>
                                            </select>
                                        </div>

                                        {{-- <div class="col-md-3">
                                            <label class="form-label">City</label>
                                            <select name="city_id" id="city" class="form-select">
                                                <option value="">Select City</option>
                                            </select>
                                        </div> --}}

                                        <div class="col-md-3">
                                            <label class="form-label">Taluka</label>
                                            <select name="tehsil_id" id="tehsil" class="form-select">
                                                <option value="">Select Taluka</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Village</label>
                                            <input type="text" name="village" class="form-control"
                                                value="{{ old('village') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Pincode</label>
                                            <select name="pincode_id" id="pincode" class="form-select">
                                                <option value="">Select Pincode</option>
                                            </select>
                                        </div>

                                        {{-- Pincode, Postal, Lat/Lng --}}
                                        {{-- <div class="col-md-4">
                                            <label class="form-label">Pincode</label>
                                            <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}">
                                        </div> --}}



                                        <div class="col-md-4">
                                            <label class="form-label">Postal Address</label>
                                            <input type="text" name="postal_address" class="form-control"
                                                value="{{ old('postal_address') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Latitude</label>
                                            <input type="text" name="latitude" class="form-control"
                                                value="{{ old('latitude') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Longitude</label>
                                            <input type="text" name="longitude" class="form-control"
                                                value="{{ old('longitude') }}">
                                        </div>
                                    </div>

                                    {{-- Employment Information --}}
                                    <h5 class="mb-3">Employment Information</h5>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label">User Code</label>
                                            <input type="text" name="user_code" class="form-control"
                                                value="{{ old('user_code') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Designation</label>
                                            <select name="designation_id" class="form-select">
                                                <option value="">Select Designation</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}"
                                                        {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                                        {{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <select name="reporting_to" class="form-select">
                                            <option value="">Select Reporting To</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('reporting_to') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>


                                        <div class="col-md-4">
                                            <label class="form-label">Headquarter</label>
                                            <input type="text" name="headquarter" class="form-control"
                                                value="{{ old('headquarter') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">User Type</label>
                                            <input type="text" name="user_type" class="form-control"
                                                value="{{ old('user_type') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Joining Date</label>
                                            <input type="date" name="joining_date" class="form-control"
                                                value="{{ old('joining_date') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Emergency Contact</label>
                                            <input type="text" name="emergency_contact_no" class="form-control"
                                                value="{{ old('emergency_contact_no') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Is Self Sale</label>
                                            <select name="is_self_sale" class="form-select">
                                                <option value="0" {{ old('is_self_sale') == '0' ? 'selected' : '' }}>
                                                    No</option>
                                                <option value="1" {{ old('is_self_sale') == '1' ? 'selected' : '' }}>
                                                    Yes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Multi-Day Start/End Allowed</label>
                                            <select name="is_multi_day_start_end_allowed" class="form-select">
                                                <option value="0"
                                                    {{ old('is_multi_day_start_end_allowed') == '0' ? 'selected' : '' }}>No
                                                </option>
                                                <option value="1"
                                                    {{ old('is_multi_day_start_end_allowed') == '1' ? 'selected' : '' }}>
                                                    Yes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Allow Tracking</label>
                                            <select name="is_allow_tracking" class="form-select">
                                                <option value="1"
                                                    {{ old('is_allow_tracking') == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0"
                                                    {{ old('is_allow_tracking') == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Depo Assign</label>
                                            <select name="depo_id" class="form-select">
                                                <option value="">Select Depo</option>
                                                @foreach ($depos as $depo)
                                                        <option value="{{ $depo->id }}"
                                                            {{ old('depo_id') == $depo->id ? 'selected' : '' }}>
                                                            {{ $depo->depo_name }}
                                                        </option>
                                                    @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Is Web Login Access</label>
                                            <select name="is_web_login_access" class="form-select">
                                                <option value="1"
                                                    {{ old('is_web_login_access') == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0"
                                                    {{ old('is_web_login_access') == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Other Info --}}
                                    <h5 class="mb-3">Other Info</h5>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label">A/C No. </label>
                                            <input type="text" name="account_no" class="form-control"
                                                value="{{ old('account_no') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Branch Name</label>
                                            <input type="text" name="branch_name" class="form-control"
                                                value="{{ old('branch_name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">IFSC Code </label>
                                            <input type="text" name="ifsc_code" class="form-control"
                                                value="{{ old('ifsc_code') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">PAN Card No. </label>
                                            <input type="text" name="pan_card_no" class="form-control"
                                                value="{{ old('pan_card_no') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Aadhar No. </label>
                                            <input type="text" name="aadhar_no" class="form-control"
                                                value="{{ old('aadhar_no') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Driving Lic No. </label>
                                            <input type="text" name="driving_lic_no" class="form-control"
                                                value="{{ old('driving_lic_no') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Driving Expiry </label>
                                            <input type="text" name="driving_expiry" class="form-control"
                                                value="{{ old('driving_expiry') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Passport No. </label>
                                            <input type="text" name="passport_no" class="form-control"
                                                value="{{ old('passport_no') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Passport Expiry </label>
                                            <input type="text" name="passport_expiry" class="form-control"
                                                value="{{ old('passport_expiry') }}">
                                        </div>
                                    </div>

                                    {{-- Company Mapping --}}
                                    <h5 class="mb-3">Company Information</h5>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Company <span class="text-danger">*</span></label>
                                            @if ($authUser->hasRole('master_admin'))
                                                <select name="company_id" class="form-select" required>
                                                    <option value="">Select Company</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}"
                                                            {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                            {{ $company->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="hidden" name="company_id"
                                                    value="{{ $authUser->company_id }}">
                                                <input type="text" class="form-control"
                                                    value="{{ $authUser->company->name }}" disabled>
                                            @endif
                                        </div>
                                    </div>


                                    {{-- Authentication --}}
                                    <h5 class="mb-3">Authentication</h5>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control" required
                                                placeholder="Enter password">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Confirm Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                required placeholder="Confirm password">
                                        </div>
                                    </div>

                                    {{-- Profile Image --}}
                                    <h5 class="mb-3">Profile Image</h5>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                        </div>
                                    </div>

                                    {{-- Roles --}}
                                    <h5 class="mb-3">Assign Roles</h5>
                                    <div class="row g-3 mb-3">
                                        @foreach ($roles as $role)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                        class="form-check-input" id="role-{{ $role->name }}"
                                                        {{ is_array(old('roles')) && in_array($role->name, old('roles')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="role-{{ $role->name }}">
                                                        {{ ucfirst($role->name) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">Create Admin</button>
                                </div>
                            </form>
                            {{-- Form End --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
