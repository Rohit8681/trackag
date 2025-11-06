@extends('admin.layout.layout')

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
                    {{-- Alerts --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-check-circle me-1"></i>Success:</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-exclamation-triangle me-1"></i>Error:</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
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
                                            <td class="text-center fw-semibold text-secondary">{{ $trip->id }}</td>

                                            <td>
                                                <span class="fw-semibold text-dark">{{ $trip->user->name ?? 'N/A' }}</span><br>
                                                {{-- <small class="text-muted">{{ $trip->company->name ?? '' }}</small> --}}
                                            </td>

                                            <td>
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
                                            </td>

                                            <td>
                                                <span class="badge bg-info text-dark px-3 py-2">
                                                    {{ $trip->travelMode->name ?? '-' }}
                                                </span>
                                                <span class="badge bg-info text-dark px-3 py-2">
                                                    {{ $trip->tourType->name ?? "-" }}
                                                </span>
                                            </td>

                                            <td>{{ $trip->place_to_visit ?? '-' }}</td>

                                            <td>
                                                @forelse ($trip->customers as $customer)
                                                    <span class="badge bg-light text-dark border mb-1">
                                                        <i class="fas fa-user me-1"></i>{{ $customer->name }}
                                                    </span><br>
                                                @empty
                                                    <span class="text-muted">-</span>
                                                @endforelse
                                            </td>

                                            {{-- <td class="text-center">
                                                <div class="mb-1">
                                                    <strong>Opening:</strong><br>
                                                    @if ($trip->start_km_photo)
                                                        <a href="{{ asset('storage/' . $trip->start_km_photo) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $trip->start_km_photo) }}" class="rounded shadow-sm" width="60">
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>Closing:</strong><br>
                                                    @if ($trip->end_km_photo)
                                                        <a href="{{ asset('storage/' . $trip->end_km_photo) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $trip->end_km_photo) }}" class="rounded shadow-sm" width="60">
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </td> --}}
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
                                                <a href="{{ route('trips.show', $trip) }}" class="btn btn-sm btn-outline-primary" title="View Trip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <br>
                                                {{-- @can('view_trip_logs') --}}
                                                    <span class="badge bg-info mt-2">{{ $trip->tripLogs->count() }} logs</span><br>
                                                    <a href="#" class="text-primary small" data-bs-toggle="modal" data-bs-target="#logsModal{{ $trip->id }}">View Logs</a>
                                                {{-- @endcan --}}
                                            </td>

                                            <td class="text-center">
                                                approvals_all_trip
                                                {{-- @if (auth()->user()->can('approvals_all_trip') && $trip->approval_status === 'pending') --}}
                                                @if ($trip->approval_status === 'pending')
                                                    <div class="dropdown">
                                                        <button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            Pending
                                                        </button>
                                                        <ul class="dropdown-menu shadow-sm">
                                                            <li>
                                                                <form method="POST" action="{{ route('trips.approve', $trip->id) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="approved">
                                                                    <button type="submit" class="dropdown-item text-success">
                                                                        <i class="fas fa-check me-1"></i> Approve
                                                                    </button>
                                                                </form>
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
                                                    <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-link p-0 text-danger" onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
                                                        @if ($log->gps_status)
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