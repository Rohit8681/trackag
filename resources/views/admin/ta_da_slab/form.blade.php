@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add TA-DA Slab</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('ta-da-slab.form') }}">TA-DA Slab</a></li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">New TA-DA Slab</h4>
                </div>

                <form action="{{ route('ta-da-slab.save') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        {{-- General Details --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Max Monthly Travel K.M.</label>
                                <select name="max_monthly_travel" class="form-select @error('max_monthly_travel') is-invalid @enderror">
                                    <option value="">-- Select --</option>
                                    <option value="yes" {{ old('max_monthly_travel', $slab->max_monthly_travel ?? '')=='yes'?'selected':'' }}>Yes</option>
                                    <option value="no" {{ old('max_monthly_travel', $slab->max_monthly_travel ?? '')=='no'?'selected':'' }}>No</option>
                                </select>
                                @error('max_monthly_travel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">KM</label>
                                <input type="number" name="km" class="form-control @error('km') is-invalid @enderror" value="{{ old('km', $slab->km ?? '') }}">
                                @error('km')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Approved Bills in DA</label>
                                <select name="approved_bills_in_da[]" class="form-select @error('approved_bills_in_da') is-invalid @enderror" multiple>
                                    @foreach(['Petrol','Food','Accomodation','Travel','Courier','Hotel','Others'] as $opt)
                                        <option value="{{ $opt }}"
                                            {{ (is_array(old('approved_bills_in_da',$slab->approved_bills_in_da ?? [])) && in_array($opt, old('approved_bills_in_da',$slab->approved_bills_in_da ?? []))) ? 'selected':'' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('approved_bills_in_da')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Vehicle & Tour Slabs --}}
                        <div class="row g-4">
                            {{-- Vehicle Types --}}
                            <div class="col-md-6">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-light fw-bold">Vehicle Type Slab Details</div>
                                    <div class="card-body p-0">
                                        <table class="table table-bordered mb-0 text-center">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Vehicle Type</th>
                                                    <th>Travelling Allow per KM</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($vehicleTypes as $vt)
                                                <tr>
                                                    <td class="align-middle">
                                                        <input type="hidden" name="vehicle_type_id[]" value="{{ $vt->id }}">
                                                        <strong>{{ $vt->vehicle_type }}</strong>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="travelling_allow_per_km[]" 
                                                            class="form-control @error('travelling_allow_per_km.'.$loop->index) is-invalid @enderror"
                                                            value="{{ old('travelling_allow_per_km')[$loop->index] ?? $slab->vehicleSlabs[$loop->index]->travelling_allow_per_km ?? '' }}"
                                                            placeholder="Enter amount">
                                                        @error('travelling_allow_per_km.'.$loop->index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Tour Types --}}
                            <div class="col-md-6">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-light fw-bold">Tour Type Slab Details</div>
                                    <div class="card-body p-0">
                                        <table class="table table-bordered mb-0 text-center">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Tour Type</th>
                                                    <th>D.A. Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tourTypes as $tt)
                                                <tr>
                                                    <td class="align-middle">
                                                        <input type="hidden" name="tour_type_id[]" value="{{ $tt->id }}">
                                                        <strong>{{ $tt->name }}</strong>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="da_amount[]" 
                                                            class="form-control @error('da_amount.'.$loop->index) is-invalid @enderror"
                                                            value="{{ old('da_amount')[$loop->index] ?? $slab->tourSlabs[$loop->index]->da_amount ?? '' }}"
                                                            placeholder="Enter amount">
                                                        @error('da_amount.'.$loop->index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Second Vehicle & Tour Section (Duplicate as per your code) --}}
                        <div class="row g-4 mt-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Designation</label>
                                <select name="designation_id" class="form-select">
                                    <option value="">-- Select --</option>
                                    @foreach($designations as $d)
                                        <option value="{{ $d->id }}" {{ old('designation_id', $slab->designation_id ?? '') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Vehicle Types --}}
                            <div class="col-md-4">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-light fw-bold">Vehicle Type Slab Details</div>
                                    <div class="card-body p-0">
                                        <table class="table table-bordered mb-0 text-center">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Vehicle Type</th>
                                                    <th>Travelling Allow per KM</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($vehicleTypes as $vt)
                                                <tr>
                                                    <td class="align-middle">
                                                        <input type="hidden" name="slab_wise_vehicle_type_id[]" value="{{ $vt->id }}">
                                                        <strong>{{ $vt->vehicle_type }}</strong>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="slab_wise_travelling_allow_per_km[]" 
                                                            class="form-control @error('travelling_allow_per_km.'.$loop->index) is-invalid @enderror"
                                                            value="{{ old('travelling_allow_per_km')[$loop->index] ?? $slab->vehicleSlabs[$loop->index]->travelling_allow_per_km ?? '' }}"
                                                            placeholder="Enter amount">
                                                        @error('travelling_allow_per_km.'.$loop->index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Tour Types --}}
                            <div class="col-md-4">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-light fw-bold">Tour Type Slab Details</div>
                                    <div class="card-body p-0">
                                        <table class="table table-bordered mb-0 text-center">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Tour Type</th>
                                                    <th>D.A. Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tourTypes as $tt)
                                                <tr>
                                                    <td class="align-middle">
                                                        <input type="hidden" name="slab_wise_tour_type_id[]" value="{{ $tt->id }}">
                                                        <strong>{{ $tt->name }}</strong>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="slab_wise_da_amount[]" 
                                                            class="form-control @error('da_amount.'.$loop->index) is-invalid @enderror"
                                                            value="{{ old('da_amount')[$loop->index] ?? $slab->tourSlabs[$loop->index]->da_amount ?? '' }}"
                                                            placeholder="Enter amount">
                                                        @error('da_amount.'.$loop->index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer text-end bg-light">
                        <a href="{{ route('ta-da-slab.form') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('select[name="approved_bills_in_da[]"]').select2({
        placeholder: "Select Approved Bills",
        width: '100%'
    });
});
</script>
@endpush
