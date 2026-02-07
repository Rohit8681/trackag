@extends('admin.layout.layout')
@section('title', 'Farmer List | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Farmers</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="app-content">
        <div class="container-fluid">

            <div class="card card-primary card-outline">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Farmer List</h3>
                </div>

                <div class="card-body">

                    {{-- 🔍 Filter Section --}}
                    <form method="GET" class="row g-2 mb-3">

    {{-- From Date --}}
                        <div class="col-md-2">
                            <label class="form-label small mb-1">From Date</label>
                            <input type="date"
                                name="from_date"
                                class="form-control form-control-sm"
                                value="{{ request('from_date') }}">
                        </div>

                        {{-- To Date --}}
                        <div class="col-md-2">
                            <label class="form-label small mb-1">To Date</label>
                            <input type="date"
                                name="to_date"
                                class="form-control form-control-sm"
                                value="{{ request('to_date') }}">
                        </div>

                        {{-- Farmer Name --}}
                        <div class="col-md-2">
                            <label class="form-label small mb-1">Farmer Name</label>
                            <input type="text"
                                name="farmer_name"
                                class="form-control form-control-sm"
                                placeholder="Farmer name"
                                value="{{ request('farmer_name') }}">
                        </div>

                        {{-- Mobile No --}}
                        <div class="col-md-2">
                            <label class="form-label small mb-1">Mobile No</label>
                            <input type="text"
                                name="mobile_no"
                                class="form-control form-control-sm"
                                placeholder="Mobile no"
                                value="{{ request('mobile_no') }}">
                        </div>

                        {{-- State --}}
                        <div class="col-md-2">
                            <label class="form-label small mb-1">State</label>
                            <select name="state_id" class="form-select form-select-sm">
                                <option value="">All State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sales Person --}}
                        <div class="col-md-2">
                            <label class="form-label small mb-1">Sales Person</label>
                            <input type="text"
                                name="sales_person"
                                class="form-control form-control-sm"
                                placeholder="Sales person"
                                value="{{ request('sales_person') }}">
                        </div>

                        {{-- Crop Name --}}
                        <div class="col-md-2">
                            <label class="form-label small mb-1">Crop Name</label>
                            <input type="text"
                                name="crop_name"
                                class="form-control form-control-sm"
                                placeholder="Crop name"
                                value="{{ request('crop_name') }}">
                        </div>

                        {{-- Buttons --}}
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('farmers.index') }}" class="btn btn-secondary btn-sm">
                                Reset
                            </a>
                            <a href="{{ route('farmers.pdf', request()->query()) }}"
                            class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>

                    </form>

                    {{-- 📋 Farmers Table --}}
                    <div class="table-responsive" style="max-height:600px;">
                        <table id="farmer-table"
                               class="table table-bordered table-hover table-striped align-middle table-sm">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Date</th>
                                    <th>Farmer Name</th>
                                    <th>Sales Person</th>
                                    <th>Mobile</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <th>Taluka</th>
                                    <th>City</th>
                                    <th>Farmer Land Area</th>
                                    <th>Irrigation Type</th>
                                    <th>Crop Name</th>
                                    <th>Action</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($farmers as $index => $farmer)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $farmer->created_at ? $farmer->created_at->format('d-m-Y') : '-' }}</td>
                                        <td>{{ $farmer->farmer_name }}</td>
                                        <td>{{ $farmer->user->name ?? '-' }}</td>
                                        <td>{{ $farmer->mobile_no }}</td>
                                        <td>{{ $farmer->state->name ?? '-' }}</td>
                                        <td>{{ $farmer->district->name ?? '-' }}</td>
                                        <td>{{ $farmer->taluka->name ?? '-' }}</td>
                                        <td>{{ $farmer->village }}</td>
                                        <td>
                                            {{ (isset($farmer->land_acr_size) ? $farmer->land_acr_size : '') . ' ' . (isset($farmer->land_acr) ? $farmer->land_acr : '') }}
                                        </td>
                                        <td>{{ $farmer->irrigation_type ?? '-' }}</td>
                                        <td>
                                            @if($farmer->cropSowings->count())
                                                {{ $farmer->cropSowings->pluck('crop.name')->implode(', ') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('farmers.farm-visits', $farmer->id) }}"
                                            class="btn btn-sm btn-primary">
                                                View Farm Visits
                                            </a>
                                        </td>
                                        
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-muted">
                                            No farmers found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let total = {{ $farmers->count() }};
    if (total > 0) {
        $('#farmer-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50]
        });
    }
});
</script>
@endpush
