@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="container-fluid py-4">
        <h3>Create New Company with Admin</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card mb-4">
                <div class="card-header"><h5>Company Information</h5></div>
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" 
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Owner Name</label>
                        <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror"
                               value="{{ old('owner_name') }}">
                        @error('owner_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Company Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code') }}">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">GST Number</label>
                        <input type="text" name="gst_number" class="form-control @error('gst_number') is-invalid @enderror"
                               value="{{ old('gst_number') }}">
                        @error('gst_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact No <span class="text-danger">*</span></label>
                        <input type="text" name="contact_no" class="form-control mobile_no @error('contact_no') is-invalid @enderror"
                               value="{{ old('contact_no') }}">
                        @error('contact_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact No 2</label>
                        <input type="text" name="contact_no2" class="form-control mobile_no @error('contact_no2') is-invalid @enderror"
                               value="{{ old('contact_no2') }}">
                        @error('contact_no2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Telephone No</label>
                        <input type="text" name="telephone_no" class="form-control @error('telephone_no') is-invalid @enderror"
                               value="{{ old('telephone_no') }}">
                        @error('telephone_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Logo (PNG)</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/png">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control @error('website') is-invalid @enderror"
                               value="{{ old('website') }}">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">State Working</label>
                        <select name="state[]" class="form-control @error('state') is-invalid @enderror" multiple>
                            @foreach ($states as $s)
                                <option value="{{ $s->id }}"
                                    {{ collect(old('state'))->contains($s->id) ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror"
                               value="{{ old('product_name') }}">
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Subscription Type</label>
                        <input type="text" name="subscription_type" class="form-control @error('subscription_type') is-invalid @enderror"
                               value="{{ old('subscription_type') }}">
                        @error('subscription_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tally Configuration</label>
                        <select name="tally_configuration" class="form-select @error('tally_configuration') is-invalid @enderror">
                            <option value="0" {{ old('tally_configuration') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('tally_configuration') == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('tally_configuration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Start Date<span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">VALIDITY UPTO<span class="text-danger">*</span></label>
                        <input type="date" name="validity_upto" class="form-control">
                        @error('validity_upto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">USER ASSIGNED<span class="text-danger">*</span></label>
                        <input type="number" name="user_assigned" class="form-control">
                        @error('user_assigned')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5>Admin User Information</h5></div>
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="user_password" class="form-control @error('user_password') is-invalid @enderror">
                        @error('user_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="user_password_confirmation" class="form-control">
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
@push('scripts')
<script>
$('.mobile_no').on('input', function() {
    // Remove non-digit characters
    this.value = this.value.replace(/\D/g, '');
    
    // Limit to 10 digits
    if (this.value.length > 10) {
        this.value = this.value.slice(0, 10);
    }
});
$(document).ready(function() {
    $('select[name="state[]"]').select2({
        placeholder: "Select State(s)"
    });
});
</script>
@endpush
