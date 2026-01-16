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
                    <form method="GET" class="row g-3 mb-3">

                        <div class="col-md-3">
                            <label class="form-label">Farmer Name</label>
                            <input type="text"
                                   name="farmer_name"
                                   class="form-control"
                                   placeholder="Enter farmer name"
                                   value="{{ request('farmer_name') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Mobile No</label>
                            <input type="text"
                                   name="mobile_no"
                                   class="form-control"
                                   placeholder="Enter mobile no"
                                   value="{{ request('mobile_no') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">State</label>
                            <select name="state_id" class="form-select">
                                <option value="">-- All State --</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary me-2">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('farmers.index') }}" class="btn btn-secondary">
                                Reset
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
                                    <th>Farmer Name</th>
                                    <th>Mobile</th>
                                    <th>Village</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <th>Taluka</th>
                                    <th>Crop Sowing</th>
                                    <th>Land (Acr)</th>
                                    <th>Added By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($farmers as $index => $farmer)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $farmer->farmer_name }}</td>
                                        <td>{{ $farmer->mobile_no }}</td>
                                        <td>{{ $farmer->village }}</td>
                                        <td>{{ $farmer->state->name ?? '-' }}</td>
                                        <td>{{ $farmer->district->name ?? '-' }}</td>
                                        <td>{{ $farmer->taluka->name ?? '-' }}</td>
                                        <td>{{ $farmer->cropSowing->name ?? '-' }}</td>
                                        <td>{{ $farmer->land_acr }}</td>
                                        <td>{{ $farmer->user->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
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
