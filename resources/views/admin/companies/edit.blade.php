@extends('admin.layout.layout')
@section('title', 'Edit Company | Trackag')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Edit Company</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Company</a></li>
                            <li class="breadcrumb-item active">Edit Company</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-body p-4">
                        <form action="{{ route('companies.update', $company->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Edit Company</h5>
                                </div>
                                <div class="card-body row g-3">

                                    <div class="col-md-4">
                                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $company->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Owner Name<span class="text-danger">*</span></label>
                                        <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror"
                                            value="{{ old('owner_name', $company->owner_name) }}">
                                            @error('owner_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Company Code <span class="text-danger">*</span></label>
                                        <input type="text" readonly name="code" class="form-control @error('code') is-invalid @enderror"
                                            value="{{ old('code', $company->code) }}">
                                            @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">GST Number<span class="text-danger">*</span></label>
                                        <input type="text" name="gst_number" class="form-control @error('gst_number') is-invalid @enderror"
                                            value="{{ old('gst_number', $company->gst_number) }}">
                                            @error('gst_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-8">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" rows="1"
                                            class="form-control">{{ old('address', $company->address) }}</textarea>
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
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email',$company->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label d-block mb-2">Logo (PNG)<span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($company->logo)
                                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo Preview" height="50"
                                                    width="50" class="rounded border">
                                            @else
                                                <div class="border rounded bg-light d-flex align-items-center justify-content-center"
                                                    style="height:50px; width:50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif

                                            <input type="file" name="logo" class="form-control w-auto @error('logo') is-invalid @enderror" accept="image/png">
                                            @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Website<span class="text-danger">*</span></label>
                                        <input type="url" name="website"
                                            class="form-control @error('website') is-invalid @enderror"
                                            value="{{ old('website',$company->website) }}">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-md-4">
                                        <label class="form-label">State Working<span class="text-danger">*</span></label>
                                        <select name="state[]" class="form-control @error('state') is-invalid @enderror"
                                            multiple>
                                            @foreach($state as $s)
                                                <option value="{{ $s->id }}" {{ in_array($s->id, explode(',', old('state', $company->state ?? ''))) ? 'selected' : '' }}>
                                                    {{ $s->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div> --}}
                                    @php
                                        $selectedStates = old('state') ?? explode(',', $company->state ?? '');
                                    @endphp
                                    <div class="col-md-4">
                                        <label class="form-label">State Working<span class="text-danger">*</span></label>
                                        <select name="state[]" class="form-control @error('state') is-invalid @enderror" multiple>
                                            @foreach($state as $s)
                                                <option value="{{ $s->id }}" {{ in_array($s->id, $selectedStates) ? 'selected' : '' }}>
                                                    {{ $s->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                        <label class="form-label">Start Date<span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                            value="{{ old('start_date', $company->start_date ? \Carbon\Carbon::parse($company->start_date)->format('Y-m-d') : '') }}">
                                            @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-4">
                                        <label class="form-label">Validity Upto<span class="text-danger">*</span></label>
                                        <input type="date" name="validity_upto" class="form-control @error('validity_upto') is-invalid @enderror"
                                            value="{{ old('validity_upto', $company->validity_upto ? \Carbon\Carbon::parse($company->validity_upto)->format('Y-m-d') : '') }}">
                                            @error('validity_upto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">User Assigned<span class="text-danger">*</span></label>
                                        <input type="text" name="user_assigned" class="form-control @error('user_assigned') is-invalid @enderror"
                                            value="{{ old('user_assigned', $company->user_assigned) }}">
                                            @error('user_assigned')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Admin User Information</h5>
                                </div>
                                <div class="card-body row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Contact No<span class="text-danger">*</span></label>
                                        <input type="text" name="contact_no" class="form-control @error('contact_no') is-invalid @enderror"
                                            value="{{ old('contact_no', $company->contact_no) }}">
                                            @error('contact_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Password </label>
                                        <div class="input-group">
                                        <input type="password" name="user_password" id="user_password"
                                            class="form-control @error('user_password') is-invalid @enderror">
                                            <button type="button" class="btn btn-outline-secondary" 
                                                    onclick="togglePassword('user_password', this)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Confirm Password </label>
                                        <div class="input-group">
                                        <input type="password" name="user_password_confirmation" id="user_password_confirmation" class="form-control @error('user_password') is-invalid @enderror">
                                        <button type="button" class="btn btn-outline-secondary" 
                                                    onclick="togglePassword('user_password_confirmation', this)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        @error('user_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update Company & Admin</button>
                                <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
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
        $('.mobile_no').on('input', function () {
            // Remove non-digit characters
            this.value = this.value.replace(/\D/g, '');

            // Limit to 10 digits
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
        $(document).ready(function () {
            $('select[name="state[]"]').select2({
                placeholder: "Select State(s)"
            });
        });
    </script>
@endpush