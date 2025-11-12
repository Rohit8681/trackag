@extends('admin.layout.layout')
@section('title', 'Create Users | Trackag')

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
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                            <li class="breadcrumb-item active">Create New User</li>
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

                            {{-- @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible fade show m-3">
                                        <strong>Error:</strong> {!! $error !!}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endforeach
                            @endif --}}

                            {{-- Form Start --}}
                            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">

                                    {{-- Personal Information --}}
                                    <h5 class="mb-3">Personal Information</h5>
                                    <div class="row g-3 mb-4">
                                        {{-- Name, Email, Mobile --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" >
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Email </label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email') }}">
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label class="form-label">Mobile<span class="text-danger">*</span></label>
                                            <input type="text" name="mobile" class="form-control mobile_no @error('mobile') is-invalid @enderror"
                                                value="{{ old('mobile') }}">
                                                @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Company Mobile<span class="text-danger">*</span></label>
                                            <input type="text" name="company_mobile" class="form-control mobile_no @error('company_mobile') is-invalid @enderror"
                                                value="{{ old('company_mobile') }}">
                                                @error('company_mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                                            <textarea name="address" rows="1" class="form-control">{{ old('address') }}</textarea>
                                        </div>

                                        {{-- Location Dropdowns --}}
                                        <div class="col-md-3">
                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                            <select name="state_id" id="state_id" class="form-select @error('state_id') is-invalid @enderror">
                                                <option value="">Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('state_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">District<span class="text-danger">*</span></label>
                                            <select name="district_id" id="district_id" class="form-select @error('district_id') is-invalid @enderror">
                                                <option value="">Select District</option>
                                            </select>
                                            @error('district_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Taluka<span class="text-danger">*</span></label>
                                            <select name="tehsil_id" id="tehsil_id" class="form-select @error('tehsil_id') is-invalid @enderror">
                                                <option value="">Select Taluka</option>
                                            </select>
                                            @error('tehsil_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Village</label>
                                            <input type="text" name="village" class="form-control"
                                                value="{{ old('village') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Pincode</label>
                                            {{-- <select name="pincode_id" id="pincode" class="form-select">
                                                <option value="">Select Pincode</option>
                                            </select> --}}
                                            <input type="number" name="pincode" id="pincode" class="form-control"
                                                value="{{ old('pincode') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Postal Address</label>
                                            <input type="text" name="postal_address" class="form-control"
                                                value="{{ old('postal_address') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Latitude</label>
                                            <input type="text" name="latitude" class="form-control"
                                                value="{{ old('latitude') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Longitude</label>
                                            <input type="text" name="longitude" class="form-control"
                                                value="{{ old('longitude') }}">
                                        </div>
                                    </div>

                                    {{-- Employment Information --}}
                                    <h5 class="mb-3">Employment Information</h5>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label">User Code<span class="text-danger">*</span></label>
                                            <input type="text" name="user_code" class="form-control @error('user_code') is-invalid @enderror"
                                                value="{{ old('user_code') }}">
                                                @error('user_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Designation<span class="text-danger">*</span></label>
                                            <select name="designation_id" class="form-select @error('designation_id') is-invalid @enderror">
                                                <option value="">Select Designation</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}"
                                                        {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                                        {{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('designation_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Reporting To<span class="text-danger">*</span></label>
                                            <select name="reporting_to" class="form-select @error('reporting_to') is-invalid @enderror">
                                                <option value="">Select Reporting To</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ old('reporting_to') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('reporting_to')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Headquarter<span class="text-danger">*</span></label>
                                            <input type="text" name="headquarter" class="form-control @error('headquarter') is-invalid @enderror"
                                                value="{{ old('headquarter') }}">
                                                @error('headquarter')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">User Type</label>
                                            <input type="text" name="user_type" class="form-control"
                                                value="{{ old('user_type') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Joining Date<span class="text-danger">*</span></label>
                                            <input type="date" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror" max="{{ date('Y-m-d') }}"
                                                value="{{ old('joining_date') }}">
                                                @error('joining_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Emergency Contact<span class="text-danger">*</span></label>
                                            <input type="text" name="emergency_contact_no" class="form-control @error('emergency_contact_no') is-invalid @enderror"
                                                value="{{ old('emergency_contact_no') }}">
                                                @error('emergency_contact_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Is Self Sale<span class="text-danger">*</span></label>
                                            <select name="is_self_sale" class="form-select @error('is_self_sale') is-invalid @enderror">
                                                <option value="0" {{ old('is_self_sale') == '0' ? 'selected' : '' }}>
                                                    No</option>
                                                <option value="1" {{ old('is_self_sale') == '1' ? 'selected' : '' }}>
                                                    Yes</option>
                                            </select>
                                            @error('is_self_sale')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Multi-Day Start/End Allowed<span class="text-danger">*</span></label>
                                            <select name="is_multi_day_start_end_allowed" class="form-select @error('is_multi_day_start_end_allowed') is-invalid @enderror">
                                                <option value="0"
                                                    {{ old('is_multi_day_start_end_allowed') == '0' ? 'selected' : '' }}>No
                                                </option>
                                                <option value="1"
                                                    {{ old('is_multi_day_start_end_allowed') == '1' ? 'selected' : '' }}>
                                                    Yes</option>
                                            </select>
                                            @error('is_multi_day_start_end_allowed')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Allow Tracking</label>
                                            <select name="is_allow_tracking" class="form-select">
                                                <option value="1"
                                                    {{ old('is_allow_tracking') == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0"
                                                    {{ old('is_allow_tracking') == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
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

                                        <div class="col-md-3">
                                            <label class="form-label">Is Web Login Access<span class="text-danger">*</span></label>
                                            <select name="is_web_login_access" class="form-select @error('is_web_login_access') is-invalid @enderror">
                                                <option value="1"
                                                    {{ old('is_web_login_access') == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0"
                                                    {{ old('is_web_login_access') == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                            @error('is_web_login_access')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            
                                        </div>
                                    </div>

                                    {{-- Other Info --}}
                                    <h5 class="mb-3">Other Info</h5>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label">A/C No. </label>
                                            <input type="text" name="account_no" class="form-control"
                                                value="{{ old('account_no') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Branch Name</label>
                                            <input type="text" name="branch_name" class="form-control"
                                                value="{{ old('branch_name') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">IFSC Code </label>
                                            <input type="text" name="ifsc_code" class="form-control"
                                                value="{{ old('ifsc_code') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">PAN Card No. </label>
                                            <input type="text" name="pan_card_no" class="form-control"
                                                value="{{ old('pan_card_no') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Aadhar No. </label>
                                            <input type="text" name="aadhar_no" class="form-control"
                                                value="{{ old('aadhar_no') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Driving Lic No. </label>
                                            <input type="text" name="driving_lic_no" class="form-control"
                                                value="{{ old('driving_lic_no') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Driving Expiry</label>
                                            <input type="date" name="driving_expiry" class="form-control"
                                                value="{{ old('driving_expiry') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Passport No. </label>
                                            <input type="text" name="passport_no" class="form-control"
                                                value="{{ old('passport_no') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Passport Expiry</label>
                                            <input type="date" name="passport_expiry" class="form-control"
                                                value="{{ old('passport_expiry') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Upload Cancel Cheque Photos</label>
                                            <input type="file" name="cancel_cheque_photos[]" class="form-control" accept="image/*"
                                                   multiple onchange="validateChequePhotos(this)">
                                            <small class="text-muted">You can upload up to 3 images.</small>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Profile Image</label>
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                        </div>
                                    </div>

                                    {{-- Authentication --}}
                                    <h5 class="mb-3">Authentication</h5>
                                    <div class="row g-3 mb-4">
                                        <!-- Password -->
                                        <div class="col-md-4">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" id="password" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    placeholder="Enter password">
                                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                @error('password')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="col-md-4">
                                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" id="password_confirmation" name="password_confirmation"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                                    placeholder="Confirm password">
                                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', this)">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Roles --}}
                                    <h5 class="mb-3">Assign Roles<span class="text-danger">*</span></h5>
                                    <div class="row g-3 mb-3">
                                        @foreach ($roles as $role)
                                            <div class="col-md-2">
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
                                    @error('roles')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">Create</button>
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


@push('scripts')
<script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    function validateChequePhotos(input) {
        if (input.files.length > 3) {
            alert("You can upload only up to 3 Cancel Cheque Photos.");
            input.value = ""; // reset selection
        }
    }
    $('.mobile_no').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
        
        // Limit to 10 digits
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });
</script>
<script>
$(document).ready(function() {
    $('#state_id').on('change', function() {
        var stateId = $(this).val();
        $('#district_id').html('<option value="">Loading...</option>');
        $('#tehsil_id').html('<option value="">-- Select Tehsil --</option>');

        if(!stateId) {
            $('#district_id').html('<option value="">-- Select District --</option>');
            return;
        }

        $.get("{{ route('depos.get-districts') }}", { state_id: stateId }, function(data){
            var html = '<option value="">-- Select District --</option>';
            $.each(data, function(i, d){
                html += '<option value="'+ d.id +'">'+ d.name +'</option>';
            });
            $('#district_id').html(html);
        });
    });

    $('#district_id').on('change', function() {
        var districtId = $(this).val();
        $('#tehsil_id').html('<option value="">Loading...</option>');

        if(!districtId) {
            $('#tehsil_id').html('<option value="">-- Select Tehsil --</option>');
            return;
        }

        $.get("{{ route('depos.get-tehsils') }}", { district_id: districtId }, function(data){
            var html = '<option value="">-- Select Tehsil --</option>';
            $.each(data, function(i, t){
                html += '<option value="'+ t.id +'">'+ t.name +'</option>';
            });
            $('#tehsil_id').html(html);
        });
    });
});
</script>
@endpush

