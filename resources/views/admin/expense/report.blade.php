@extends('admin.layout.layout')
@section('title', 'Expense Report | Trackag')
@push('styles')
<style>
    .action-buttons {
        display: flex;
        gap: 5px;
        align-items: center;
        white-space: nowrap;
    }

    .action-buttons .btn-sm {
        padding: 2px 6px;
        font-size: 11px;
    }
</style>
@endpush
@section('content')
<main class="app-main">

    <!-- Header Section -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Expenses Report</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Expenses Report</a></li>
                        <li class="breadcrumb-item active">Expenses Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-primary card-outline mb-4">

                <!-- Card Header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Expenses Report List</h5>
                    
                </div>

                <!-- Card Body -->
                <div class="card-body table-responsive">

                    <!-- 🔍 Filter Form -->
                    {{-- <form action="{{ route('expense.index') }}" method="GET" class="row g-3 mb-3">
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">State</label>
                            <select name="state_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Employee Name</label>
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($employees as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Bill Type</label>
                            <select name="bill_type" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="Petrol" {{ request('bill_type')=='Petrol'?'selected':'' }}>Petrol</option>
                                <option value="Food" {{ request('bill_type')=='Food'?'selected':'' }}>Food</option>
                                <option value="Accommodation" {{ request('bill_type')=='Accommodation'?'selected':'' }}>Accommodation</option>
                                <option value="Travel" {{ request('bill_type')=='Travel'?'selected':'' }}>Travel</option>
                                <option value="Courier" {{ request('bill_type')=='Courier'?'selected':'' }}>Courier</option>
                                <option value="Others" {{ request('bill_type')=='Others'?'selected':'' }}>Others</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Approval Status</label>
                            <select name="approval_status" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="Pending" {{ request('approval_status')=='Pending'?'selected':'' }}>Pending</option>
                                <option value="Approved" {{ request('approval_status')=='Approved'?'selected':'' }}>Approved</option>
                                <option value="Rejected" {{ request('approval_status')=='Rejected'?'selected':'' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-sm btn-primary me-2">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('expense.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </form> --}}
                    <!-- 🔍 End Filter Form -->

                    <!-- 📋 Expense Table -->
                    <table id="expenses-report-table" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Date</th>
                                <th>Tour Type</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Visit Places</th>
                                <th>Travel Mode</th>
                                <th>Start KM</th>
                                <th>End KM</th>
                                <th>Travel KM</th>
                                <th>GPS KM</th>
                                <th>KM diff</th>
                                <th>TA EXP</th>
                                <th>DA EXP</th>
                                <th>Other EXP</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key => $report)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($report->trip_date)->format('d M Y') }}</td>
                                    <td>{{ $report->tourType->name ?? "-" }}</td>
                                    <td>{{ $report->start_time ?? "-" }}</td>
                                    <td>{{ $report->end_time ?? "-" }}</td>
                                    <td>{{ $report->place_to_visit ?? "-" }}</td>
                                    <td>{{ $report->travelMode->name ?? "-" }}</td>
                                    <td>{{ $report->starting_km ?? "-" }}</td>
                                    <td>{{ $report->end_km ?? "-" }}</td>
                                    <td>{{ $report->end_km - $report->starting_km }}</td>
                                    <td>{{ $report->total_distance_km ?? "-" }}</td>
                                    <td>{{ $report->end_km - $report->starting_km }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                               
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No expenses found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.app-content -->
</main>
@endsection


@push('scripts')
<script>
$(document).ready(function() {
    var data = @json($data->count());
    if (data > 0) {
        $('#expenses-report-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: -1 } // Action column not sortable
            ]
        });
    }
});
</script>
@endpush
