@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        {{-- Header Section --}}
        <div class="app-content-header py-3">
            <div class="container-fluid px-3">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Trips</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end mb-0">
                            <li class="breadcrumb-item"><a href="#">Trip Management</a></li>
                            <li class="breadcrumb-item active">All Trips</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Section --}}
        <div class="app-content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Trip List</h5>
                        {{-- @can('create_trips')
                            <a href="{{ route('trips.create') }}" class="btn btn-primary">Add Trip</a>
                        @endcan --}}
                    </div>

                    <div class="card-body table-responsive">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                <strong>Success:</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                <strong>Error:</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @can('view_trips')
                            <table id="trips-table" class="table table-hover table-bordered align-middle text-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>#ID</th>
                                        <th>Agent Name</th>
                                        <th>Day Start/End</th>
                                        <th>Vehicle & Tour Type</th>
                                        <th>Places</th>
                                        <th>Start/End KM Image</th>
                                        <th>Start/End KM</th>
                                        <th>Map Tracking</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trips as $trip)
                                        <tr>
                                            <!-- 1 -->
                                            <td>{{ $trip->id }}</td>
                                            <!-- 2 -->
                                            <td>{{ $trip->user->name ?? 'N/A' }}</td>
                                            <!-- 3 -->
                                            <td><span><b>Day Start :</b>
                                                @php
                                                    $startTime = $trip->tripLogs->min('recorded_at');
                                                @endphp
                                                {{ $startTime ? \Carbon\Carbon::parse($startTime)->format('d-m-Y H:i:s a') : '-' }}
                                                </span>
                                                <br>
                                                <span><b>Day End : </b>
                                                    @if ($trip->status === 'completed')
                                                        @php
                                                            $endTime = $trip->tripLogs->max('recorded_at');
                                                        @endphp
                                                        {{ $endTime ? \Carbon\Carbon::parse($endTime)->format('d-m-Y H:i:s a') : 'End time not available' }}
                                                    @else
                                                        <span class="text-warning fw-semibold">Trip is running...</span>
                                                    @endif
                                                </span>
                                            </td>
                                            {{-- 4 --}}
                                            <td></td>
                                            {{-- 5 --}}
                                             <td>{{ $trip->place_to_visit ?? '-' }}</td>
                                            {{-- 6 --}}
                                            <td> 
                                                <span><b>Opening KM :</b>
                                                @if ($trip->start_km_photo)
                                                    <a href="{{ asset('storage/' . $trip->start_km_photo) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $trip->start_km_photo) }}" alt="Start Photo"
                                                            class="img-thumbnail" width="50">
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                                </span>
                                                <br>
                                                <span><b> Closing KM :</b>
                                                @if ($trip->end_km_photo)
                                                    <a href="{{ asset('storage/' . $trip->end_km_photo) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $trip->end_km_photo) }}" alt="End Photo"
                                                            class="img-thumbnail" width="50">
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                                </span>
                                            </td>
                                            <!-- 7 -->
                                            <td><span><b>Start KM : </b>{{ $trip->starting_km ?? '-' }} </span><br>
                                                <span><b>End KM : </b>{{ $trip->end_km ?? '-' }} </span><br>
                                                <span><b>Difference : </b>@if (!is_null($trip->starting_km) && !is_null($trip->end_km))
                                                    {{ (float) $trip->end_km - (float) $trip->starting_km }}

                                                @else
                                                    -
                                                @endif </span>
                                                
                                            </td>
                                            <!-- 8 -->
                                            <td></td>  
                                            {{-- 9 --}}
                                            <td>
                                                @if (auth()->user()->can('trip_approvals') && $trip->approval_status === 'pending')
                                                    <div class="dropdown">
                                                        <button class="badge bg-warning text-dark dropdown-toggle border-0"
                                                            type="button" data-bs-toggle="dropdown">
                                                            Pending
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form method="POST" action="{{ route('trips.approve', $trip->id) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="approved">
                                                                    <button type="submit" class="dropdown-item text-success">
                                                                        <i class="fas fa-check-circle me-1"></i> Approve
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#denyModal{{ $trip->id }}">
                                                                    <i class="fas fa-times-circle me-1"></i> Deny
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @else
                                                    <span class="badge rounded-pill 
                                                                        @if ($trip->approval_status == 'approved') bg-success
                                                                        @elseif($trip->approval_status == 'denied') bg-danger
                                                                        @else bg-secondary @endif">
                                                        {{ ucfirst($trip->approval_status) }}
                                                    </span>
                                                    @if ($trip->approval_status === 'denied' && $trip->approval_reason)
                                                        <br><small class="text-muted">Reason:
                                                            {{ $trip->approval_reason }}</small>
                                                    @endif
                                                    @if ($trip->approval_status === 'denied' && $trip->approvedByUser)
                                                        <br><small class="text-muted">By:
                                                            {{ $trip->approvedByUser->name }}</small>
                                                    @endif
                                                @endif
                                                
                                                @can('delete_trips')
                                                        <form action="{{ route('trips.destroy', $trip) }}" method="POST"
                                                            class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link p-0 text-danger" title="Delete"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </form>
                                                    @endcan
                                            </td> 
                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="text-center">No trips found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Deny modals --}}
        @foreach ($trips as $trip)
            @can('trip_approvals')
                @if ($trip->approval_status === 'pending')
                    <div class="modal fade" id="denyModal{{ $trip->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('trips.approve', $trip->id) }}" class="modal-content">
                                @csrf
                                <input type="hidden" name="status" value="denied">
                                <div class="modal-header">
                                    <h5 class="modal-title">Deny Trip #{{ $trip->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body px-3">
                                    <p><strong>Date:</strong> {{ $trip->trip_date }}</p>
                                    <p><strong>Distance:</strong> {{ $trip->total_distance_km }} km</p>
                                    <p><strong>Created by:</strong> {{ $trip->user->name ?? 'N/A' }}</p>
                                    <p><strong>Company:</strong> {{ $trip->company->name ?? 'N/A' }}</p>
                                    <div class="mb-3">
                                        <label for="reason-{{ $trip->id }}" class="form-label">Reason for Denial</label>
                                        <textarea name="reason" id="reason-{{ $trip->id }}" class="form-control" rows="3"
                                            required></textarea>
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
            @endcan
        @endforeach

        {{-- Logs modals --}}
        @foreach ($trips as $trip)
            @can('view_trip_logs')
                <div class="modal fade" id="logsModal{{ $trip->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <div>
                                        <h5 class="modal-title mb-0">Trip Logs for Trip #{{ $trip->id }}</h5>
                                        @php
                                            $totalDistance = 0;
                                            $previousLat = null;
                                            $previousLng = null;

                                            foreach ($trip->tripLogs as $log) {
                                                if ($previousLat && $previousLng) {
                                                    // Haversine formula to calculate distance
                                                    $earthRadius = 6371; // Earth's radius in km
                                                    $latFrom = deg2rad($previousLat);
                                                    $lonFrom = deg2rad($previousLng);
                                                    $latTo = deg2rad($log->latitude);
                                                    $lonTo = deg2rad($log->longitude);

                                                    $latDelta = $latTo - $latFrom;
                                                    $lonDelta = $lonTo - $lonFrom;

                                                    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                                                        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                                                    $totalDistance += $angle * $earthRadius;
                                                }
                                                $previousLat = $log->latitude;
                                                $previousLng = $log->longitude;
                                            }
                                        @endphp
                                        <div class="text-muted small mt-1">
                                            <strong>Total Distance:</strong> {{ number_format($totalDistance, 3) }} km
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @if ($trip->tripLogs->count())
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped align-middle text-nowrap">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Latitude</th>
                                                    <th>Longitude</th>
                                                    <th>Battery (%)</th>
                                                    <th>GPS Status</th>
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
                                                        <td>
                                                            @if (!is_null($log->battery_percentage))
                                                                {{ number_format($log->battery_percentage, 2) }}%
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($log->gps_status)
                                                                <span class="badge bg-success">On</span>
                                                            @else
                                                                <span class="badge bg-danger">Off</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($log->recorded_at)->format('d-m-Y H:i:s a') }}
                                                        </td>
                                                        <td>{{ $log->created_at->format('d-m-Y H:i:s a') }}</td>
                                                        <td>{{ $log->updated_at->format('d-m-Y H:i:s a') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>No logs available for this trip.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        @endforeach
    </main>
@endsection