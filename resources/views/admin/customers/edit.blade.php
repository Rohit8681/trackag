@extends('admin.layout.layout')
@section('title', 'Edit Customer | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Customer Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                        <li class="breadcrumb-item active">Edit Customer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Edit Customer</div>
                        </div>

                        <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="card-body row">

                                {{-- Agro Name --}}
                                <div class="mb-3 col-md-4">
                                    <label for="agro_name" class="form-label">Agro Name <span class="text-danger">*</span></label>
                                    <input type="text" name="agro_name" id="agro_name"
                                           class="form-control @error('agro_name') is-invalid @enderror"
                                           value="{{ old('agro_name', $customer->agro_name) }}">
                                    @error('agro_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="contact_person_name" class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_person_name" id="contact_person_name"
                                           class="form-control @error('contact_person_name') is-invalid @enderror"
                                           value="{{ old('contact_person_name', $customer->contact_person_name) }}">
                                    @error('contact_person_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Party Code --}}
                                <div class="mb-3 col-md-4">
                                    <label for="party_code" class="form-label">Party Code <span class="text-danger">*</span></label>
                                    <input type="text" name="party_code" id="party_code"
                                           class="form-control @error('party_code') is-invalid @enderror"
                                           value="{{ old('party_code', $customer->party_code) }}">
                                    @error('party_code') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- State --}}
                                <div class="mb-3 col-md-4">
                                    <label for="state_id" class="form-label">State <span class="text-danger">*</span></label>
                                    <select name="state_id" id="state_id"
                                            class="form-select @error('state_id') is-invalid @enderror">
                                        <option value="">-- Select State --</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id', $customer->state_id) == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- District --}}
                                <div class="mb-3 col-md-4">
                                    <label for="district_id" class="form-label">District <span class="text-danger">*</span></label>
                                    <select name="district_id" id="district_id"
                                            class="form-select @error('district_id') is-invalid @enderror">
                                        <option value="">-- Select District --</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ old('district_id', $customer->district_id) == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('district_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Tehsil --}}
                                <div class="mb-3 col-md-4">
                                    <label for="tehsil_id" class="form-label">Tehsil <span class="text-danger">*</span></label>
                                    <select name="tehsil_id" id="tehsil_id"
                                            class="form-select @error('tehsil_id') is-invalid @enderror">
                                        <option value="">-- Select Tehsil --</option>
                                        @foreach($tehsils as $tehsil)
                                            <option value="{{ $tehsil->id }}" {{ old('tehsil_id', $customer->tehsil_id) == $tehsil->id ? 'selected' : '' }}>
                                                {{ $tehsil->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tehsil_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Address --}}
                                <div class="mb-3 col-md-4">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" name="address" id="address"
                                           class="form-control" value="{{ old('address', $customer->address) }}">
                                </div>

                                {{-- Mobile No --}}
                                <div class="mb-3 col-md-4">
                                    <label for="phone" class="form-label">Mobile No <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone"
                                           class="form-control mobile_no @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $customer->phone) }}">
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- GST No --}}
                                <div class="mb-3 col-md-4">
                                    <label for="gst_no" class="form-label">GST No</label>
                                    <input type="text" name="gst_no" id="gst_no"
                                           class="form-control @error('gst_no') is-invalid @enderror"
                                           value="{{ old('gst_no', $customer->gst_no) }}">
                                    @error('gst_no') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Executive --}}
                                <div class="mb-3 col-md-4">
                                    <label for="user_id" class="form-label">Assign Person<span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id"
                                            class="form-select @error('user_id') is-invalid @enderror">
                                        <option value="">-- Select Executive --</option>
                                        @foreach ($executives as $executive)
                                            <option value="{{ $executive->id }}" {{ old('user_id', $customer->user_id) == $executive->id ? 'selected' : '' }}>
                                                {{ $executive->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Credit Limit --}}
                                <div class="mb-3 col-md-4">
                                    <label for="credit_limit" class="form-label">Credit Limit</label>
                                    <input type="text" name="credit_limit" id="credit_limit"
                                           class="form-control @error('credit_limit') is-invalid @enderror"
                                           value="{{ old('credit_limit', $customer->credit_limit) }}">
                                    @error('credit_limit') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Depo --}}
                                <div class="mb-3 col-md-4">
                                    <label for="depo_id" class="form-label">Depo<span class="text-danger">*</span></label>
                                    <select name="depo_id" id="depo_id"
                                            class="form-select @error('depo_id') is-invalid @enderror">
                                        <option value="">-- Select Depo --</option>
                                        @foreach ($depos as $depo)
                                            <option value="{{ $depo->id }}" {{ old('depo_id', $customer->depo_id) == $depo->id ? 'selected' : '' }}>
                                                {{ $depo->depo_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('depo_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Party Active Since --}}
                                <div class="mb-3 col-md-4">
                                    <label for="party_active_since" class="form-label">Party Active Since<span class="text-danger">*</span></label>
                                    <input type="date" name="party_active_since" id="party_active_since"
                                           class="form-control @error('party_active_since') is-invalid @enderror"
                                           value="{{ old('party_active_since', $customer->party_active_since) }}"
                                           max="{{ date('Y-m-d') }}">
                                    @error('party_active_since') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Status --}}
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Status</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_active" value="1"
                                            {{ old('is_active', $customer->is_active) == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_active" value="0"
                                            {{ old('is_active', $customer->is_active) == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label">Inactive</label>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">Update Customer</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.mobile_no').on('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });

    // Load Districts dynamically
    $('#state_id').on('change', function() {
        let stateId = $(this).val();
        $('#district_id').html('<option>Loading...</option>');
        $('#tehsil_id').html('<option>-- Select Tehsil --</option>');

        if (!stateId) {
            $('#district_id').html('<option>-- Select District --</option>');
            return;
        }

        $.get("{{ route('depos.get-districts') }}", { state_id: stateId }, function(data) {
            let html = '<option value="">-- Select District --</option>';
            $.each(data, function(i, d) {
                html += `<option value="${d.id}">${d.name}</option>`;
            });
            $('#district_id').html(html);
        });
    });

    // Load Tehsils dynamically
    $('#district_id').on('change', function() {
        let districtId = $(this).val();
        $('#tehsil_id').html('<option>Loading...</option>');
        if (!districtId) {
            $('#tehsil_id').html('<option>-- Select Tehsil --</option>');
            return;
        }

        $.get("{{ route('depos.get-tehsils') }}", { district_id: districtId }, function(data) {
            let html = '<option value="">-- Select Tehsil --</option>';
            $.each(data, function(i, t) {
                html += `<option value="${t.id}">${t.name}</option>`;
            });
            $('#tehsil_id').html(html);
        });
    });
});
</script>
@endpush
