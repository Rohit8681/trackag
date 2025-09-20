@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="container-fluid py-4">
        <h3>Create New Company with Admin</h3>

        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- @if ($errors->any()) 
                        @dd($errors->all());
            @endif --}}
            {{-- ========================= --}}
            {{-- Company Information --}}
            {{-- ========================= --}}
            <div class="card mb-4">
                <div class="card-header"><h5>Company Information</h5></div>
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Company Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Company Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Owner Name</label>
                        <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">GST Number</label>
                        <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact No</label>
                        <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact No 2</label>
                        <input type="text" name="contact_no2" class="form-control" value="{{ old('contact_no2') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Telephone No</label>
                        <input type="text" name="telephone_no" class="form-control" value="{{ old('telephone_no') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Logo (PNG)</label>
                        <input type="file" name="logo" class="form-control" accept="image/png">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">State Working</label>
                        <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Subscription Type</label>
                        <input type="text" name="subscription_type" class="form-control" value="{{ old('subscription_type') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tally Configuration</label>
                        <select name="tally_configuration" class="form-select">
                            <option value="0" {{ old('tally_configuration') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('tally_configuration') == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ========================= --}}
            {{-- Admin User Information --}}
            {{-- ========================= --}}
            <div class="card mb-4">
                <div class="card-header"><h5>Admin User Information</h5></div>
                <div class="card-body row g-3">

                    {{-- Basic --}}
                    <div class="col-md-4">
                        <label class="form-label">Name *</label>
                        <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email *</label>
                        <input type="email" name="user_email" class="form-control" value="{{ old('user_email') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="user_mobile" class="form-control" value="{{ old('user_mobile') }}">
                    </div>

                    {{-- Extra --}}
                    <div class="col-md-4">
                        <label class="form-label">User Code</label>
                        <input type="text" name="user_code" class="form-control" value="{{ old('user_code') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">User Type</label>
                        <input type="text" name="user_type" class="form-control" value="{{ old('user_type') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Role</label>
                        <input type="text" name="role" class="form-control" value="{{ old('role') }}">
                    </div>

                    {{-- Personal --}}
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Joining Date</label>
                        <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Emergency Contact</label>
                        <input type="text" name="emergency_contact_no" class="form-control" value="{{ old('emergency_contact_no') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Marital Status</label>
                        <select name="marital_status" class="form-select">
                            <option value="">Select</option>
                            <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                        </select>
                    </div>

                    {{-- Employment --}}
                    <div class="col-md-4">
                        <label class="form-label">Designation</label>
                        <select name="designation_id" class="form-select">
                            <option value="">Select</option>
                            @foreach ($designations as $d)
                                <option value="{{ $d->id }}" {{ old('designation_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Reporting To</label>
                        <select name="reporting_to" class="form-select">
                            <option value="">Select</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" {{ old('reporting_to') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Headquarter</label>
                        <input type="text" name="headquarter" class="form-control" value="{{ old('headquarter') }}">
                    </div>

                    {{-- Settings --}}
                    <div class="col-md-4">
                        <label class="form-label">Is Self Sale</label>
                        <select name="is_self_sale" class="form-select">
                            <option value="0" {{ old('is_self_sale') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_self_sale') == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Multi-Day Start/End Allowed</label>
                        <select name="is_multi_day_start_end_allowed" class="form-select">
                            <option value="0" {{ old('is_multi_day_start_end_allowed') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_multi_day_start_end_allowed') == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Allow Tracking</label>
                        <select name="is_allow_tracking" class="form-select">
                            <option value="1" {{ old('is_allow_tracking') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('is_allow_tracking') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    {{-- Address --}}
                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="user_address" class="form-control">{{ old('user_address') }}</textarea>
                    </div>

                    {{-- Location --}}
                    <div class="col-md-3">
                        <label class="form-label">State</label>
                        <select name="state_id" id="state" class="form-select">
                            <option value="">Select</option>
                            @foreach ($states as $s)
                                <option value="{{ $s->id }}" {{ old('state_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">District</label>
                        <select name="district_id" id="district" class="form-select">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">City</label>
                        <select name="city_id" id="city" class="form-select">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tehsil</label>
                        <select name="tehsil_id" id="tehsil" class="form-select">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pincode</label>
                        <select name="pincode_id" id="pincode" class="form-select">
                            <option value="">Select</option>
                        </select>
                    </div>

                    {{-- Postal / Lat / Lng --}}
                    <div class="col-md-4">
                        <label class="form-label">Postal Address</label>
                        <input type="text" name="postal_address" class="form-control" value="{{ old('postal_address') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}">
                    </div>

                    {{-- Auth --}}
                    <div class="col-md-6">
                        <label class="form-label">Password *</label>
                        <input type="password" name="user_password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="user_password_confirmation" class="form-control" required>
                    </div>

                    {{-- Profile --}}
                    <div class="col-md-6">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="user_image" class="form-control" accept="image/*">
                    </div>

                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save Company & Admin</button>
                <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</main>
@endsection
