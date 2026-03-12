@extends('admin.layout.layout')

@section('title', 'Add TA-DA Slab | Trackag')

@section('content')
<style>
    /* Remove spinner arrows from number inputs for Chrome, Safari, Edge, Opera */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Remove spinner arrows from number inputs for Firefox */
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
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
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold d-flex align-items-center mb-3">
                                    <span>Approved Bills in DA</span>
                                    
                                    <div class="d-flex align-items-center ms-4">
                                        <div class="form-check mb-0 fw-normal">
                                            <input class="form-check-input global-travel-mode-checkbox" type="checkbox" name="travel_mode_enabled" value="1" id="global_travel_mode_enabled" {{ old('travel_mode_enabled', $slab->travel_mode_enabled ?? 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="global_travel_mode_enabled">Travel Mode Enable</label>
                                        </div>
                                        <div class="ms-2" id="travel_mode_limit_container" style="display: none;">
                                            <input type="number" step="0.01" name="travel_mode_limit" class="form-control form-control-sm travel-mode-limit" placeholder="Enter KM limit" value="{{ old('travel_mode_limit', $slab->travel_mode_limit) }}">
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center ms-4" style="display: none;">
                                        <div class="form-check mb-0 fw-normal">
                                            <input class="form-check-input global-tour-type-checkbox" type="checkbox" name="tour_type_enabled" value="1" id="global_tour_type_enabled" {{ old('tour_type_enabled', $slab->tour_type_enabled ?? 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="global_tour_type_enabled">Tour Type Enable</label>
                                        </div>
                                        <div class="ms-2" id="tour_type_limit_container" style="display: none;">
                                            <input type="number" step="0.01" name="tour_type_limit" class="form-control form-control-sm tour-type-limit" placeholder="Enter amount limit" value="{{ old('tour_type_limit', $slab->tour_type_limit) }}">
                                        </div>
                                    </div>
                                </label>

                                <div class="col-md-4">
                                    <select name="approved_bills_in_da_slab_wise[]" class="form-select select2 @error('approved_bills_in_da_slab_wise') is-invalid @enderror" multiple>
                                        @foreach(['Petrol','Food','Accomodation','Travel','Courier','Hotel','Others'] as $opt)
                                            <option value="{{ $opt }}"
                                                {{ (is_array(old('approved_bills_in_da_slab_wise', $slab->approved_bills_in_da_slab_wise ?? [])) && in_array($opt, old('approved_bills_in_da_slab_wise', $slab->approved_bills_in_da_slab_wise ?? []))) ? 'selected':'' }}>
                                                {{ $opt }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('approved_bills_in_da_slab_wise')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                                                    <div class="card-header bg-light fw-bold">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span>Travel Mode ({{ $d->name }})</span>
                                                        </div>
                                                    </div>
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
                                                    <div class="card-header bg-light fw-bold">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span>Tour Type ({{ $d->name }})</span>
                                                        </div>
                                                    </div>
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

    // Travel Mode Checkbox sync & disable limit logic
    function syncTravelMode() {
        let isChecked = $('.global-travel-mode-checkbox').is(':checked');
        $('.travel-mode-limit').prop('disabled', !isChecked);
        if (isChecked) {
            $('#travel_mode_limit_container').show();
        } else {
            $('#travel_mode_limit_container').hide();
        }
    }
    
    $(document).on('change', '.global-travel-mode-checkbox', function() {
        syncTravelMode();
    });

    $(document).on('input', '.travel-mode-limit', function() {
        $('.travel-mode-limit').val($(this).val());
    });

    // Tour Type Checkbox sync & disable limit logic
    function syncTourType() {
        let isChecked = $('.global-tour-type-checkbox').is(':checked');
        $('.tour-type-limit').prop('disabled', !isChecked);
        if (isChecked) {
            $('#tour_type_limit_container').show();
        } else {
            $('#tour_type_limit_container').hide();
        }
    }

    $(document).on('change', '.global-tour-type-checkbox', function() {
        syncTourType();
    });

    $(document).on('input', '.tour-type-limit', function() {
        $('.tour-type-limit').val($(this).val());
    });

    // Initial sync
    syncTravelMode();
    syncTourType();

    // Enable on submit so values are sent when dealing with possible disabled state
    $('form').on('submit', function() {
        $('.travel-mode-limit, .tour-type-limit').prop('disabled', false);
    });

    // Prevent Arrow keys and negative values on number inputs
    $(document).on('keydown', 'input[type="number"]', function(e) {
        // Prevent Up Arrow (38) and Down Arrow (40)
        if (e.which === 38 || e.which === 40) {
            e.preventDefault();
        }
        // Prevent minus sign (-) which is key code 189 or 109 (numpad)
        if (e.which === 189 || e.which === 109) {
            e.preventDefault();
        }
    });

    $(document).on('input', 'input[type="number"]', function(e) {
        // If a negative value is pasted or entered, remove the negative sign
        if ($(this).val() < 0) {
            $(this).val(Math.abs($(this).val()));
        }
    });
});
</script>
@endpush
