@extends('admin.layout.layout')

@section('title', 'Trip Route Map | Trackag')

@section('content')
<main class="app-main">
    <div class="app-content py-4">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1 d-flex align-items-center">
                        <i class="fas fa-map-marked-alt text-primary me-2"></i> Trip Route Map
                    </h3>
                    <div class="text-muted">
                        <div><strong>Agent:</strong> {{ $trip->user->name ?? 'N/A' }}</div>
                        <div><strong>Place to Visit:</strong> {{ $trip->place_to_visit ?? 'N/A' }}</div>
                        <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($trip->trip_date)->format('d-m-Y') }}</div>
                    </div>
                </div>

                <div>
                    <a href="{{ route('trips.index') }}" class="btn btn-outline-secondary d-flex align-items-center shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Back to Trips
                    </a>
                </div>
            </div>

            <!-- Map Card -->
            <div class="card shadow border-0 rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <div id="map" class="w-100" style="height: 700px;"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- Pass tripLogs to JS -->
    <script>
        window.tripLogs = @json($tripLogs);
        window.tripEnded = {{ $trip->end_time ? 'true' : 'false' }};
    </script>

    <!-- Enhanced CSS -->
    <style>
        body {
            background-color: #f5f6fa;
        }

        .card {
            transition: all 0.2s ease-in-out;
            border-radius: 1rem;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .card-body {
            background: #fff;
        }

        .btn-outline-secondary {
            border-color: #ced4da;
            color: #495057;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-outline-secondary:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        #map {
            border-top: 1px solid #dee2e6;
        }

        h3 i {
            font-size: 1.2rem;
        }

        .fw-bold {
            font-weight: 600 !important;
        }
    </style>
</main>
@endsection
