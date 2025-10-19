@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="container-fluid py-4">
        <h3>Edit Company</h3>

        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-header"><h5>Company Information</h5></div>
                <div class="card-body row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $company->name) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Owner Name</label>
                        <input type="text" name="owner_name" class="form-control"
                               value="{{ old('owner_name', $company->owner_name) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Company Code <span class="text-danger">*</span></label>
                        <input type="text" readonly name="code" class="form-control"
                               value="{{ old('code', $company->code) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">GST Number</label>
                        <input type="text" name="gst_number" class="form-control"
                               value="{{ old('gst_number', $company->gst_number) }}">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="1" class="form-control">{{ old('address', $company->address) }}</textarea>
                    </div>

                    

                    <div class="col-md-4">
                        <label class="form-label">Contact No 2</label>
                        <input type="text" name="contact_no2" class="form-control mobile_no"
                               value="{{ old('contact_no2', $company->contact_no2) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Telephone No</label>
                        <input type="text" name="telephone_no" class="form-control mobile_no"
                               value="{{ old('telephone_no', $company->telephone_no) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $company->email) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label d-block mb-2">Logo (PNG)</label>
                        <div class="d-flex align-items-center gap-3">
                            @if($company->logo)
                                <img src="{{ asset('storage/'.$company->logo) }}" 
                                    alt="Logo Preview"
                                    height="50" width="50"
                                    class="rounded border">
                            @else
                                <div class="border rounded bg-light d-flex align-items-center justify-content-center"
                                    style="height:50px; width:50px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif

                            <input type="file" name="logo" class="form-control w-auto" accept="image/png">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control"
                               value="{{ old('website', $company->website) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">State Working</label>
                        <select name="state[]" class="form-control" multiple>
                            @foreach($state as $s)
                                <option value="{{ $s->id }}"
                                    {{ in_array($s->id, explode(',', old('state', $company->state ?? ''))) ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control"
                               value="{{ old('product_name', $company->product_name) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Subscription Type</label>
                        <input type="text" name="subscription_type" class="form-control"
                               value="{{ old('subscription_type', $company->subscription_type) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tally Configuration</label>
                        <select name="tally_configuration" class="form-select">
                            <option value="0" {{ old('tally_configuration', $company->tally_configuration) == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('tally_configuration', $company->tally_configuration) == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                     <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ old('start_date', $company->start_date ? \Carbon\Carbon::parse($company->start_date)->format('Y-m-d') : '') }}">
                    </div>


                    <div class="col-md-4">
                        <label class="form-label">Validity Upto</label>
                        <input type="date" name="validity_upto" class="form-control"
                            value="{{ old('validity_upto', $company->validity_upto ? \Carbon\Carbon::parse($company->validity_upto)->format('Y-m-d') : '') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">User Assigned</label>
                        <input type="text" name="user_assigned" class="form-control"
                            value="{{ old('user_assigned', $company->user_assigned) }}">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5>Admin User Information</h5></div>
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Contact No<span class="text-danger">*</span></label>
                        <input type="text" name="contact_no" class="form-control"
                               value="{{ old('contact_no', $company->contact_no) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="user_password" class="form-control @error('user_password') is-invalid @enderror">
                        @error('user_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="user_password_confirmation" class="form-control">
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Update Company</button>
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
