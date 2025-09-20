@extends('admin.layout.layout')

@section('content')
    <main class="app-main">

        <!-- Header Section -->
        <div class="app-content-header mb-3">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="app-title">Trip Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 m-0">
                            <li class="breadcrumb-item"><a href="{{ route('trips.index') }}"><i class="fas fa-route"></i>
                                    Trips</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Trip</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="app-content">
            <div class="container-fluid">
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i> Trip Information</h3>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">

                            <!-- Basic Details -->
                            <div class="col-md-4">
                                <strong>Trip Date:</strong><br>
                                <span class="text-muted">{{ $trip->trip_date }}</span>
                            </div>

                            <div class="col-md-4">
                                <strong>Travel Mode:</strong><br>
                                <span class="text-muted">{{ ucfirst($trip->travel_mode) }}</span>
                            </div>

                            <div class="col-md-4">
                                <strong>Status:</strong><br>
                                <span class="badge {{ $trip->status === 'completed' ? 'bg-success' : 'bg-warning' }} p-2">
                                    {{ ucfirst($trip->status) }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <strong>Approval Status:</strong><br>
                                <span
                                    class="badge 
                                @if ($trip->approval_status == 'approved') bg-success
                                @elseif($trip->approval_status == 'denied') bg-danger
                                @else bg-secondary @endif p-2">
                                    {{ ucfirst($trip->approval_status) ?? 'Pending' }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <strong>Purpose:</strong><br>
                                <span class="text-muted">{{ $trip->purpose }}</span>
                            </div>

                            <div class="col-md-4">
                                <strong>Tour Type:</strong><br>
                                <span class="text-muted">{{ $trip->tour_type }}</span>
                            </div>

                            <div class="col-md-12">
                                <strong>Place To Visit:</strong><br>
                                <span class="text-muted">{{ $trip->place_to_visit }}</span>
                            </div>

                            <!-- KM & Distance Section -->
                            <div class="col-md-3">
                                <strong>Starting KM:</strong><br>
                                <span class="text-muted">{{ $trip->starting_km }}</span>
                            </div>

                            <div class="col-md-3">
                                <strong>End KM:</strong><br>
                                <span class="text-muted">{{ $trip->end_km }}</span>
                            </div>



                            <div class="col-md-3">
                                <strong>Traveled Distance (KM Reading):</strong><br>
                                <span class="fw-bold">
                                    @if ($trip->starting_km !== null && $trip->end_km !== null)
                                        {{ $trip->end_km - $trip->starting_km }} km
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>

                            <div class="col-md-3">
                                <strong>Total Distance (GPS) (km):</strong><br>
                                <span class="fw-bold">{{ $trip->total_distance_km ?? 'Calculating...' }}</span>
                            </div>

                            <!-- Location Coordinates -->
                            <div class="col-md-6">
                                <strong>Start Location:</strong><br>
                                <span class="text-muted">{{ $trip->start_lat }}, {{ $trip->start_lng }}</span>
                            </div>

                            <div class="col-md-6">
                                <strong>End Location:</strong><br>
                                <span class="text-muted">{{ $trip->end_lat }}, {{ $trip->end_lng }}</span>
                            </div>

                            <!-- Map Section -->
                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold"><i class="fas fa-map-marked-alt me-2"></i>Trip Route
                                    Map</label>
                                <div class="border rounded shadow-sm" id="map" style="height: 500px;"></div>
                            </div>

                            <!-- Back Button -->
                            <div class="col-12 mt-4 text-end">
                                <a href="{{ route('trips.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Trips
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pass logs to JS -->
        <script>
            const tripLogs = @json($tripLogs);
        </script>

    </main>
@endsection
