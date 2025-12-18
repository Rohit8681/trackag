@extends('admin.layout.layout')
<style>
    .trip-type-group {
    display: flex;
    gap: 12px;
    margin-top: 10px;
}

/* Base button look */
.trip-type-btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: 2px solid transparent;
    cursor: pointer;
    font-weight: 600;
    background: #e9e9e9;
    transition: 0.25s ease-in-out;
    user-select: none;
}

/* Hide radio */
.trip-type-btn input {
    display: none;
}

/* FULL DAY – Green */
.trip-type-btn.full {
    border-color: #28a745;
}
.trip-type-btn.full input:checked + span,
.trip-type-btn.full input:checked {
    background: #28a745;
    color: #fff;
    border-radius: 8px;
    padding: 10px 20px;
}

/* HALF DAY – Yellow */
.trip-type-btn.half {
    border-color: #ffc107;
}
.trip-type-btn.half input:checked + span,
.trip-type-btn.half input:checked {
    background: #ffc107;
    color: #000;
    border-radius: 8px;
    padding: 10px 20px;
}

/* Hover effect */
.trip-type-btn:hover {
    background: #f1f1f1;
}
</style>
@section('content')
<main class="app-main">

    {{-- Header Section --}}
    <div class="app-content-header py-4 bg-light border-bottom">
        <div class="container-fluid px-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h3 class="fw-bold mb-0 text-primary">
                        <i class="fas fa-route me-2 text-secondary"></i>Trips
                    </h3>
                    <p class="text-muted small mb-0">Monitor and manage all trip records</p>
                </div>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Trip Management</a></li>
                    <li class="breadcrumb-item active">All Trips</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="app-content py-4">
        <div class="container-fluid px-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex align-items-center justify-content-between border-0 py-3">
                    <h5 class="card-title mb-0 fw-semibold text-dark">
                        <i class="fas fa-list-ul me-2 text-primary"></i>Trip List
                    </h5>
                    {{-- Uncomment if needed --}}
                    {{-- <a href="{{ route('trips.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Add Trip</a> --}}
                </div>

                <div class="card-body">
                    {{-- 🔹 Filter Section --}}
                    <form method="GET" action="{{ route('trips.index') }}" class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">From Date</label>
                                <input type="date" name="from_date" value="{{ $from_date }}" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">To Date</label>
                                <input type="date" name="to_date" value="{{ $to_date }}" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">State</label>
                                <select name="state" class="form-select">
                                    <option value="">All</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ request('state') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Employee Name</label>
                                <select name="employee" class="form-select">
                                    <option value="">All</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ request('employee') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Approval Status</label>
                                <select name="approval_status" class="form-select">
                                    <option value="">All</option>
                                    <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="denied" {{ request('approval_status') == 'denied' ? 'selected' : '' }}>Denied</option>
                                </select>
                            </div>

                            
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i>
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('trips.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-undo me-1"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                    {{-- 🔹 End Filter Section --}}
                    
                    @can('view_all_trip')
                    
                        <div class="table-responsive">
                            <table id="trips-table" class="table table-hover align-middle table-bordered text-nowrap">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Agent</th>
                                        <th>Day Start / End</th>
                                        <th>Vehicle & Type</th>
                                        <th>Places</th>
                                        <th>Customers</th>
                                        <th>KM Images</th>
                                        <th>KM Info</th>
                                        <th>Map Tracking</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trips as $trip)
                                        <tr>
                                            <td class="text-center fw-semibold text-secondary">{{ $loop->iteration }}</td>

                                            <td>
                                                <span class="fw-semibold text-dark">{{ $trip->user->name ?? 'N/A' }}</span><br>
                                                {{-- <small class="text-muted">{{ $trip->company->name ?? '' }}</small> --}}
                                            </td>
                                            <td>
                                                 <div>
                                                    <strong>Start:</strong>
                                                    @php $startTime = $trip->start_time; @endphp
                                                    <span class="text-success">
                                                        {{ $startTime ? \Carbon\Carbon::parse($startTime)->format('H:i a') : '-' }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>End:</strong>
                                                    @if ($trip->status === 'completed')
                                                        @php $endTime = $trip->end_time; @endphp
                                                        <span class="text-danger">
                                                            {{ $endTime ? \Carbon\Carbon::parse($endTime)->format('d-m-Y H:i a') : '-' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Running</span>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- <td>
                                                <div>
                                                    <strong>Start:</strong>
                                                    @php $startTime = $trip->tripLogs->min('recorded_at'); @endphp
                                                    <span class="text-success">
                                                        {{ $startTime ? \Carbon\Carbon::parse($startTime)->format('d-m-Y H:i a') : '-' }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>End:</strong>
                                                    @if ($trip->status === 'completed')
                                                        @php $endTime = $trip->tripLogs->max('recorded_at'); @endphp
                                                        <span class="text-danger">
                                                            {{ $endTime ? \Carbon\Carbon::parse($endTime)->format('d-m-Y H:i a') : '-' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Running</span>
                                                    @endif
                                                </div>
                                            </td> --}}


                                            <td>
                                                <span class="badge bg-info text-dark px-3 py-2">
                                                    {{ $trip->travelMode->name ?? '-' }}
                                                </span><br><br>
                                                <span class="badge bg-info text-dark px-3 py-2">
                                                    {{ $trip->tourType->name ?? "-" }}
                                                </span>
                                            </td>

                                            <td>{{ $trip->place_to_visit ?? '-' }}</td>

                                            <td>
                                                @forelse ($trip->customers as $customer)
                                                    <span class="badge bg-light text-dark border mb-1">
                                                        <i class="fas fa-user me-1"></i>{{ $customer->agro_name ?? '-'}}
                                                    </span><br>
                                                @empty
                                                    <span class="text-muted">-</span>
                                                @endforelse
                                            </td>

                                            
                                            <td class="text-center">
                                                @if ($trip->start_km_photo || $trip->end_km_photo)
                                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kmImagesModal{{ $trip->id }}">
                                                        <i class="fas fa-image me-1"></i> View
                                                    </button>
                                                @else
                                                    <span class="text-muted">No Images</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div><strong>Start:</strong> {{ $trip->starting_km ?? '-' }}</div>
                                                <div><strong>End:</strong> {{ $trip->end_km ?? '-' }}</div>
                                                <div><strong>Diff:</strong>
                                                    @if (!is_null($trip->starting_km) && !is_null($trip->end_km))
                                                        <span class="fw-semibold text-primary">
                                                            {{ (float) $trip->end_km - (float) $trip->starting_km }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                                <div><strong>GPS (km):</strong> {{ $trip->total_distance_km }}</div>
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('trips.show', $trip) }}" target="_blank" class="text-primary small" title="view map">
                                                    View Map
                                                </a>
                                                <br>
                                                {{-- @can('view_trip_logs') --}}
                                                    <span class="badge bg-info mt-2">{{ $trip->tripLogs->count() }} logs</span><br>
                                                    <a href="#" class="text-primary small" data-bs-toggle="modal" title="view log" data-bs-target="#logsModal{{ $trip->id }}">View Logs</a>
                                                {{-- @endcan --}}
                                            </td>

                                            <td class="text-center">
                                                @if($trip->approval_status == "pending")
                                                <button class="btn btn-outline-secondary btn-sm mb-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editKmModal{{ $trip->id }}">
                                                    <i class="fas fa-edit me-1"></i> Edit KM
                                                </button>
                                                @endif
                                                {{-- @if (auth()->user()->can('approvals_all_trip') && $trip->approval_status === 'pending') --}}
                                                @if ($trip->approval_status === 'pending')
                                                    <div class="dropdown">
                                                        <button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            Pending
                                                        </button>
                                                        <ul class="dropdown-menu shadow-sm">
                                                             <li>
                                                                <a href="#" class="dropdown-item text-success" data-bs-toggle="modal"
                                                                data-bs-target="#approveModal{{ $trip->id }}">
                                                                    <i class="fas fa-check me-1"></i> Approve
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal"
                                                                   data-bs-target="#denyModal{{ $trip->id }}">
                                                                   <i class="fas fa-times me-1"></i> Deny
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @else
                                                    <span class="badge 
                                                        @if($trip->approval_status == 'approved') bg-success 
                                                        @elseif($trip->approval_status == 'denied') bg-danger 
                                                        @else bg-secondary @endif">
                                                        {{ ucfirst($trip->approval_status) }}
                                                    </span>
                                                    @if ($trip->approval_status === 'denied' && $trip->approval_reason)
                                                        <br><small class="text-muted">Reason: {{ $trip->approval_reason }}</small>
                                                    @endif
                                                    @if ($trip->approval_status === 'denied' && $trip->approvedByUser)
                                                        <br><small class="text-muted">By: {{ $trip->approvedByUser->name }}</small>
                                                    @endif
                                                @endif

                                                {{-- @can('delete_all_trip') --}}
                                                @if ($trip->approval_status === 'pending')
                                                <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link p-0 text-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                {{-- @endcan --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-4">No trips found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- Deny Modals --}}
    @foreach ($trips as $trip)
        {{-- @can('approvals_all_trip') --}}
            @if ($trip->approval_status === 'pending')
                <div class="modal fade" id="denyModal{{ $trip->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('trips.approve', $trip->id) }}" class="modal-content border-0 shadow">
                            @csrf
                            <input type="hidden" name="status" value="denied">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Deny Trip #{{ $trip->id }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Date:</strong> {{ $trip->trip_date }}</p>
                                <p><strong>Distance:</strong> {{ $trip->total_distance_km }} km</p>
                                <p><strong>Created by:</strong> {{ $trip->user->name ?? 'N/A' }}</p>
                                <p><strong>Company:</strong> {{ $trip->company->name ?? 'N/A' }}</p>
                                <div class="mb-3">
                                    <label for="reason-{{ $trip->id }}" class="form-label">Reason for Denial</label>
                                    <textarea name="reason" id="reason-{{ $trip->id }}" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Submit Denial</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        {{-- @endcan --}}
    @endforeach

    {{-- Logs Modals --}}
    @foreach ($trips as $trip)
        {{-- @can('logs_all_trip') --}}
            <div class="modal fade" id="logsModal{{ $trip->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content border-0 shadow-sm">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title mb-0">Trip Logs #{{ $trip->id }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @if ($trip->tripLogs->count())
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm align-middle text-nowrap">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Battery</th>
                                                <th>GPS</th>
                                                <th>Recorded At</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trip->tripLogs as $index => $log)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $log->latitude }}</td>
                                                    <td>{{ $log->longitude }}</td>
                                                    <td>{{ $log->battery_percentage ?? 'N/A' }}%</td>
                                                    <td>
                                                        @if ($log->gps_status == 1)
                                                            <span class="badge bg-success">On</span>
                                                        @else
                                                            <span class="badge bg-danger">Off</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($log->recorded_at)->format('d-m-Y H:i:s a') }}</td>
                                                    <td>{{ $log->created_at->format('d-m-Y H:i:s a') }}</td>
                                                    <td>{{ $log->updated_at->format('d-m-Y H:i:s a') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center">No logs available for this trip.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        {{-- @endcan --}}
        <div class="modal fade" id="editKmModal{{ $trip->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('trips.updateKm', $trip->id) }}" class="modal-content border-0 shadow">
                @csrf
                @method('PUT')
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">Edit KM for Trip #{{ $trip->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Starting KM</label>
                        <input type="number" name="starting_km"
                            class="form-control"
                            min="0"
                            oninput="if(this.value < 0) this.value = '';"
                            value="{{ $trip->starting_km ?? '' }}" required
                            >
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ending KM</label>
                        <input type="number" name="end_km"
                            class="form-control"
                            value="{{ $trip->end_km ?? '' }}"
                            placeholder="Enter end KM"
                            min="0"
                            oninput="if(this.value < 0) this.value = '';"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Diff</label>
                        <input type="number" name="diff"
                            class="form-control"
                            value="{{ (float) $trip->end_km - (float) $trip->starting_km }}"
                            placeholder="Enter end KM"
                            min="0"
                            oninput="if(this.value < 0) this.value = '';"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">gps</label>
                        <input type="number" name="gps"
                            class="form-control"
                            value="{{ $trip->total_distance_km ?? '' }}"
                            placeholder="Enter end KM"
                            min="0"
                            oninput="if(this.value < 0) this.value = '';"
                            readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        </div>

        
        <div class="modal fade" id="approveModal{{ $trip->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $trip->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    
                    <form id="approveForm{{ $trip->id }}" method="POST" action="{{ route('trips.approve', $trip->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="approved">

                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="approveModalLabel{{ $trip->id }}">Approve Trip</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p class="fw-semibold mb-2">Select Trip Type:</p>

                            <div class="trip-type-group">

                                <label class="trip-type-btn full">
                                    <input type="radio" name="trip_type" value="full" data-form="approveForm{{ $trip->id }}">
                                    <span>Full Day</span>
                                </label>

                                <label class="trip-type-btn half">
                                    <input type="radio" name="trip_type" value="half" data-form="approveForm{{ $trip->id }}">
                                    <span>Half Day</span>
                                </label>

                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endforeach

    <!-- KM Images Modal -->
    @foreach ($trips as $trip)
    <div class="modal fade" id="kmImagesModal{{ $trip->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title mb-0">Trip #{{ $trip->id }} - KM Images</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="fw-semibold text-success">Opening</h6>
                            @if ($trip->start_km_photo)
                                <a href="{{ asset('storage/' . $trip->start_km_photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $trip->start_km_photo) }}" class="img-fluid rounded shadow-sm border" alt="Opening KM">
                                </a>
                            @else
                                <p class="text-muted mb-0">No Image</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold text-danger">Closing</h6>
                            @if ($trip->end_km_photo)
                                <a href="{{ asset('storage/' . $trip->end_km_photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $trip->end_km_photo) }}" class="img-fluid rounded shadow-sm border" alt="Closing KM">
                                </a>
                            @else
                                <p class="text-muted mb-0">No Image</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</main>
@endsection
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".trip-type-btn input").forEach(input => {
        input.addEventListener("change", function () {
            const formId = this.getAttribute("data-form");
            document.getElementById(formId).submit();
        });
    });
});

$(document).ready(function () {
    var tripsCount = @json($trips->count());
    if (tripsCount > 0) {
        $('#trips-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: -1 } 
            ]
        });
    }
});
</script>