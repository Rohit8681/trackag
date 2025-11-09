@extends('admin.layout.layout')

@section('title', 'Add TA-DA Slab | Trackag')

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

                        {{-- ==================== GENERAL DETAILS ==================== --}}
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
                                <input type="number" name="km" class="form-control @error('km') is-invalid @enderror"
                                    value="{{ old('km', $slab->km ?? '') }}">
                                @error('km')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Approved Bills in DA</label>
                                <select name="approved_bills_in_da[]" class="form-select select2 @error('approved_bills_in_da') is-invalid @enderror" multiple>
                                    @foreach(['Petrol','Food','Accomodation','Travel','Courier','Hotel','Others'] as $opt)
                                        <option value="{{ $opt }}"
                                            {{ (is_array(old('approved_bills_in_da', $slab->approved_bills_in_da ?? [])) && in_array($opt, old('approved_bills_in_da', $slab->approved_bills_in_da ?? []))) ? 'selected':'' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('approved_bills_in_da')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ==================== INDIVIDUAL SLAB SECTION ==================== --}}
                        <div class="row g-4 mb-4">
                            <h5 class="text-primary fw-bold">Individual Slab</h5>

                            {{-- Travel Modes --}}
                            <div class="col-md-6">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-light fw-bold">Travel Modes (Individual)</div>
                                    <div class="card-body p-0">
                                        <table class="table table-bordered mb-0 text-center">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Travel Mode</th>
                                                    <th>Travelling Allow per KM</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($travelModes as $index => $tm)
                                                    @php
                                                        $value = $individualVehicleSlabs->where('travel_mode_id', $tm->id)->first()->travelling_allow_per_km ?? '';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $tm->name }}</td>
                                                        <td>
                                                            <input type="hidden" name="travel_mode_id[]" value="{{ $tm->id }}">
                                                            <input type="number" step="0.01" name="travelling_allow_per_km[]" class="form-control"
                                                                value="{{ old('travelling_allow_per_km')[$index] ?? $value }}">
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
                                    <div class="card-header bg-light fw-bold">Tour Type (Individual)</div>
                                    <div class="card-body p-0">
                                        <table class="table table-bordered mb-0 text-center">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Tour Type</th>
                                                    <th>D.A. Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tourTypes as $index => $tt)
                                                    @php
                                                        $value = $individualTourSlabs->where('tour_type_id', $tt->id)->first()->da_amount ?? '';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $tt->name }}</td>
                                                        <td>
                                                            <input type="hidden" name="tour_type_id[]" value="{{ $tt->id }}">
                                                            <input type="number" step="0.01" name="da_amount[]" class="form-control"
                                                                value="{{ old('da_amount')[$index] ?? $value }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        {{-- ==================== SLAB-WISE (DESIGNATION-WISE) ==================== --}}
                        <div class="row g-4 mt-4">
                            <h5 class="text-primary fw-bold">Slab-wise (Designation-wise Rates)</h5>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Approved Bills in DA</label>
                                <select name="approved_bills_in_da_slab_wise[]" class="form-select select2 @error('approved_bills_in_da_slab_wise') is-invalid @enderror" multiple>
                                    @foreach(['Petrol','Food','Accomodation','Travel','Courier','Hotel','Others'] as $opt)
                                        <option value="{{ $opt }}"
                                            {{ (is_array(old('approved_bills_in_da_slab_wise', $slab->approved_bills_in_da_slab_wise ?? [])) && in_array($opt, old('approved_bills_in_da_slab_wise', $slab->approved_bills_in_da_slab_wise ?? []))) ? 'selected':'' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('approved_bills_in_da')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <ul class="nav nav-tabs" id="designationTabs" role="tablist">
                                @foreach($designations as $i => $d)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $i==0 ? 'active' : '' }}" id="tab-{{ $d->id }}" data-bs-toggle="tab"
                                            data-bs-target="#designation-{{ $d->id }}" type="button" role="tab">
                                            {{ $d->name }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content mt-3">
                                @foreach($designations as $i => $d)
                                    @php
                                        $vehSlabs = $slabWiseVehicleSlabs[$d->id] ?? collect();
                                        $tourSlabs = $slabWiseTourSlabs[$d->id] ?? collect();
                                    @endphp

                                    <div class="tab-pane fade {{ $i==0 ? 'show active' : '' }}" id="designation-{{ $d->id }}" role="tabpanel">
                                        <input type="hidden" name="designation_ids[]" value="{{ $d->id }}">

                                        <div class="row">
                                            {{-- Travel Mode --}}
                                            <div class="col-md-6">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-header bg-light fw-bold">Travel Mode ({{ $d->name }})</div>
                                                    <div class="card-body p-0">
                                                        <table class="table table-bordered mb-0 text-center">
                                                            <thead class="table-secondary">
                                                                <tr>
                                                                    <th>Travel Mode</th>
                                                                    <th>Travelling Allow per KM</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($travelModes as $tm)
                                                                    @php
                                                                        $value = optional($vehSlabs->where('travel_mode_id', $tm->id)->first())->travelling_allow_per_km;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $tm->name }}</td>
                                                                        <td>
                                                                            <input type="hidden" name="slab_travel_mode[{{ $d->id }}][]" value="{{ $tm->id }}">
                                                                            <input type="number" step="0.01" name="slab_travel_amount[{{ $d->id }}][]" class="form-control"
                                                                                value="{{ $value }}">
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Tour Type --}}
                                            <div class="col-md-6">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-header bg-light fw-bold">Tour Type ({{ $d->name }})</div>
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
                                                                    @php
                                                                        $value = optional($tourSlabs->where('tour_type_id', $tt->id)->first())->da_amount;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $tt->name }}</td>
                                                                        <td>
                                                                            <input type="hidden" name="slab_tour_type[{{ $d->id }}][]" value="{{ $tt->id }}">
                                                                            <input type="number" step="0.01" name="slab_tour_amount[{{ $d->id }}][]" class="form-control"
                                                                                value="{{ $value }}">
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
                                @endforeach
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
    $('.select2').select2({
        placeholder: "Select Approved Bills",
        width: '100%'
    });
});
</script>
@endpush
