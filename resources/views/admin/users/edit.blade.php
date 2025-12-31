@extends('admin.layout.layout')
@section('title', 'Edit Users | Trackag')

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
                        <li class="breadcrumb-item active">Edit User</li>
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
                            <div class="card-title mb-0">Edit User</div>
                        </div>

                        {{-- Flash Messages --}}
                        {{-- @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3">
                                <strong>Success:</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show m-3">
                                <strong>Error:</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show m-3">
                                    <strong>Error:</strong> {{ $error }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endforeach
                        @endif --}}

                        {{-- Form Start --}}
                        <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">

                                {{-- Personal Information --}}
                                <h5 class="mb-3">Personal Information</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $user->name) }}">
                                               @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Email </label>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ old('email', $user->email) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Mobile<span class="text-danger">*</span></label>
                                        <input type="text" name="mobile" class="form-control mobile_no @error('mobile') is-invalid @enderror"
                                               value="{{ old('mobile', $user->mobile) }}">
                                               @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Company Mobile<span class="text-danger">*</span></label>
                                        <input type="text" name="company_mobile" class="form-control mobile_no @error('company_mobile') is-invalid @enderror"
                                               value="{{ old('company_mobile', $user->company_mobile) }}">
                                               @error('company_mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control"
                                            value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="">Select</option>
                                            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Marital Status</label>
                                        <select name="marital_status" class="form-select">
                                            <option value="">Select</option>
                                            <option value="Single" {{ old('marital_status', $user->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ old('marital_status', $user->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" rows="1" class="form-control">{{ old('address', $user->address) }}</textarea>
                                    </div>

                                    {{-- Location Dropdowns --}}
                                    <div class="col-md-3">
                                        <label class="form-label">State<span class="text-danger">*</span></label>
                                        <select name="state_id" id="state_id" class="form-select @error('state_id') is-invalid @enderror">
                                            <option value="">Select State</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" {{ old('state_id', $user->state_id) == $state->id ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
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
                                        <input type="text" name="village" class="form-control" value="{{ old('village', $user->village) }}">
                                    </div>
                                     <div class="col-md-3">
                                        <label class="form-label">Pincode</label>
                                        <input type="number" name="pincode" id="pincode" class="form-control"
                                            value="{{ old('pincode',$user->pincode) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Postal Address</label>
                                        <input type="text" name="postal_address" class="form-control"
                                               value="{{ old('postal_address', $user->postal_address) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Latitude</label>
                                        <input type="text" name="latitude" class="form-control"
                                               value="{{ old('latitude', $user->latitude) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Longitude</label>
                                        <input type="text" name="longitude" class="form-control"
                                               value="{{ old('longitude', $user->longitude) }}">
                                    </div>
                                </div>

                                {{-- Employment Information --}}
                                <h5 class="mb-3">Employment Information</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label">User Code<span class="text-danger">*</span></label>
                                        <input type="text" name="user_code" class="form-control @error('user_code') is-invalid @enderror"
                                            value="{{ old('user_code', $user->user_code) }}">
                                            @error('user_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Designation<span class="text-danger">*</span></label>
                                        <select name="designation_id" class="form-select @error('designation_id') is-invalid @enderror">
                                            <option value="">Select Designation</option>
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation->id }}" {{ old('designation_id', $user->designation_id) == $designation->id ? 'selected' : '' }}>
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
                                            <option value="">Select Reporting Manager</option>
                                            @foreach ($users as $manager)
                                                <option value="{{ $manager->id }}" {{ old('reporting_to', $user->reporting_to) == $manager->id ? 'selected' : '' }}>
                                                    {{ $manager->name }}
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
                                            value="{{ old('headquarter', $user->headquarter) }}">
                                            @error('headquarter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <label class="form-label">User Type</label>
                                        <input type="text" name="user_type" class="form-control" value="{{ old('user_type', $user->user_type) }}">
                                    </div> --}}
                                    <div class="col-md-3">
                                        <label class="form-label">User Type</label>
                                        <select name="user_type" id="userType" class="form-select">
                                            <option value="">Select User Type</option>
                                            <option value="sales_person" {{ old('user_type',$user->user_type) == 'sales_person' ? 'selected' : '' }}>
                                                sales person</option>
                                            <option value="other" {{ old('user_type',$user->user_type) == 'other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Joining Date<span class="text-danger">*</span></label>
                                        <input type="date" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror" max="{{ date('Y-m-d') }}"
                                            value="{{ old('joining_date', optional($user->joining_date)->format('Y-m-d')) }}">
                                            @error('joining_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Emergency Contact<span class="text-danger">*</span></label>
                                        <input type="text" name="emergency_contact_no" class="form-control @error('emergency_contact_no') is-invalid @enderror"
                                            value="{{ old('emergency_contact_no',$user->emergency_contact_no) }}">
                                            @error('emergency_contact_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Is Self Sale<span class="text-danger">*</span></label>
                                        <select name="is_self_sale" class="form-select @error('is_self_sale') is-invalid @enderror">
                                            <option value="0" {{ old('is_self_sale',$user->is_self_sale) == '0' ? 'selected' : '' }}>
                                                No</option>
                                            <option value="1" {{ old('is_self_sale',$user->is_self_sale) == '1' ? 'selected' : '' }}>
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
                                                {{ old('is_multi_day_start_end_allowed',$user->is_multi_day_start_end_allowed) == '0' ? 'selected' : '' }}>No
                                            </option>
                                            <option value="1"
                                                {{ old('is_multi_day_start_end_allowed',$user->is_multi_day_start_end_allowed) == '1' ? 'selected' : '' }}>
                                                Yes</option>
                                        </select>
                                        @error('is_multi_day_start_end_allowed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Allow Tracking</label>
                                        <select name="is_allow_tracking" class="form-select">
                                            <option value="1" {{ old('is_allow_tracking', $user->is_allow_tracking) == '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('is_allow_tracking', $user->is_allow_tracking) == '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Depo Assign</label>
                                        <select name="depo_id" class="form-select">
                                            <option value="">Select Depo</option>
                                            @foreach ($depos as $depo)
                                                <option value="{{ $depo->id }}" {{ old('depo_id', $user->depo_id) == $depo->id ? 'selected' : '' }}>
                                                    {{ $depo->depo_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Is Web Login Access<span class="text-danger">*</span></label>
                                        <select name="is_web_login_access" class="form-select @error('is_web_login_access') is-invalid @enderror">
                                            <option value="1"
                                                {{ old('is_web_login_access',$user->is_web_login_access) == '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0"
                                                {{ old('is_web_login_access',$user->is_web_login_access) == '0' ? 'selected' : '' }}>No</option>
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
                                        <input type="text" name="account_no" class="form-control" value="{{ old('account_no', $user->account_no) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Branch Name</label>
                                        <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name', $user->branch_name) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">IFSC Code </label>
                                        <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $user->ifsc_code) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">PAN Card No. </label>
                                        <input type="text" name="pan_card_no" class="form-control" value="{{ old('pan_card_no', $user->pan_card_no) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Aadhar No. </label>
                                        <input type="text" name="aadhar_no" class="form-control" value="{{ old('aadhar_no', $user->aadhar_no) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Driving Lic No. </label>
                                        <input type="text" name="driving_lic_no" class="form-control" value="{{ old('driving_lic_no', $user->driving_lic_no) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Driving Expiry</label>
                                        <input type="date" name="driving_expiry" class="form-control" value="{{ old('driving_expiry', $user->driving_expiry) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Passport No. </label>
                                        <input type="text" name="passport_no" class="form-control" value="{{ old('passport_no', $user->passport_no) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Passport Expiry</label>
                                        <input type="date" name="passport_expiry" class="form-control" value="{{ old('passport_expiry', $user->passport_expiry) }}">
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center flex-wrap">
                                        <div class="me-3">
                                            <label class="form-label d-block">Upload Cancel Cheque Photos</label>
                                            <input type="file" name="cancel_cheque_photos[]" class="form-control" accept="image/*" multiple onchange="validateChequePhotos(this)">
                                            <small class="text-muted">You can upload up to 3 images.</small>
                                        </div>

                                        @if ($user->cancel_cheque_photos)
                                            @php
                                                $photos = json_decode($user->cancel_cheque_photos, true);
                                            @endphp
                                            @if(!empty($photos))
                                                <div class="d-flex align-items-center flex-wrap">
                                                    @foreach($photos as $photo)
                                                        <img src="{{ asset('storage/' . $photo) }}" width="50" height="50" class="me-2 mb-2 border rounded">
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    

                                </div>

                                {{-- Authentication --}}
                                <h5 class="mb-3">Authentication</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label d-block mb-1">Profile Image:</label>
                                        <div class="d-flex align-items-center">
                                            <input type="file" name="image" class="form-control me-2" style="flex: 1 1 auto; min-width: 0;" accept="image/*">
                                            @if ($user->image)
                                                <img src="{{ asset('storage/' . $user->image) }}" width="50" height="50" class="border rounded">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Password <small>(Leave blank to keep existing)</small></label>
                                         <div class="input-group">
                                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                        </div>
                                        
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    <div class="col-md-4">
                                        <label class="form-label">Confirm Password</label>
                                        <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" placeholder="Confirm new password">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', this)">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                        </div>
                                        @error('password')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
                                    </div>
                                </div>

                                {{-- Roles --}}
                                <h5 class="mb-3">Assign Roles<span class="text-danger">*</span></h5>
                                <div class="row g-3 mb-3">
                                    @foreach ($roles as $role)
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input type="checkbox" name="roles[]" data-role="{{ $role->name }}" value="{{ $role->name }}"
                                                       class="form-check-input role-checkbox" id="role-{{ $role->name }}"
                                                       {{ in_array($role->name, old('roles', $user->getRoleNames()->toArray())) ? 'checked' : '' }}>
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
                                <button type="submit" class="btn btn-primary">Update User</button>
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
    $(document).on('change', '.role-checkbox', function () {
        if ($(this).is(':checked')) {
            // badha bija role checkbox uncheck
            $('.role-checkbox').not(this).prop('checked', false);
        }
    });
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
        if (this.value.length > 10) this.value = this.value.slice(0, 10);
    });

    function handleRoles() {
        let userType = $('#userType').val();

        $('.role-checkbox').each(function () {
            let role = $(this).data('role');

            if (userType === 'sales_person' && role === 'sub_admin') {
                $(this).prop('checked', false);
                $(this).prop('disabled', true);
            } else if(userType == "" && role === 'sub_admin'){
                $(this).prop('checked', false);
                $(this).prop('disabled', true);
            } else {
                $(this).prop('disabled', false);
            }
        });
    }

    $('#userType').on('change', function () {
        handleRoles();
    });

    handleRoles();
</script>
<script>
$(document).ready(function() {
    function loadDistricts(stateId, selectedDistrict = null) {
        if(!stateId) {
            $('#district_id').html('<option value="">-- Select District --</option>');
            $('#tehsil_id').html('<option value="">-- Select Tehsil --</option>');
            return;
        }
        $.get("{{ route('depos.get-districts') }}", { state_id: stateId }, function(data){
            let html = '<option value="">-- Select District --</option>';
            $.each(data, function(i, d){
                html += `<option value="${d.id}" ${selectedDistrict == d.id ? 'selected' : ''}>${d.name}</option>`;
            });
            $('#district_id').html(html);

            // If editing, trigger tehsil load
            let selectedTehsil = "{{ old('tehsil_id', $user->tehsil_id) }}";
            if(selectedTehsil) {
                loadTehsils($('#district_id').val(), selectedTehsil);
            }
        });
    }

    function loadTehsils(districtId, selectedTehsil = null) {
        if(!districtId) {
            $('#tehsil_id').html('<option value="">-- Select Tehsil --</option>');
            return;
        }
        $.get("{{ route('depos.get-tehsils') }}", { district_id: districtId }, function(data){
            let html = '<option value="">-- Select Tehsil --</option>';
            $.each(data, function(i, t){
                html += `<option value="${t.id}" ${selectedTehsil == t.id ? 'selected' : ''}>${t.name}</option>`;
            });
            $('#tehsil_id').html(html);
        });
    }

    // On state change
    $('#state_id').on('change', function() {
        let stateId = $(this).val();
        loadDistricts(stateId);
    });

    // On district change
    $('#district_id').on('change', function() {
        let districtId = $(this).val();
        loadTehsils(districtId);
    });

    // On page load: pre-select districts & tehsils if editing
    let initialState = $('#state_id').val();
    let selectedDistrict = "{{ old('district_id', $user->district_id) }}";
    if(initialState) {
        loadDistricts(initialState, selectedDistrict);
    }
});

</script>
@endpush
