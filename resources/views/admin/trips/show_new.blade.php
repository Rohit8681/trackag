@extends('admin.layout.layout')

@section('title', 'Trip Route Map | Trackag')

@section('content')
<main class="app-main">
    <div class="app-content py-4">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-start flex-wrap mb-4">

                <!-- LEFT INFO -->
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

                <!-- RIGHT SIDE (LEGEND + BACK) -->
                <div class="d-flex gap-3 align-items-start flex-wrap">

                    <!-- LEGEND -->
                    <div class="route-legend">
                        <div class="legend-title">Route Time Legend</div>

                        <div class="legend-item">
                            <span class="legend-dot green"></span>
                            <span>6:00 AM – 12:00 PM</span>
                        </div>

                        <div class="legend-item">
                            <span class="legend-dot blue"></span>
                            <span>12:00 PM – 6:00 PM</span>
                        </div>

                        <div class="legend-item">
                            <span class="legend-dot red"></span>
                            <span>6:00 PM – 11:59 PM</span>
                        </div>
                    </div>

                    <!-- BACK BUTTON -->
                    <a href="{{ route('trips.index') }}"
                       class="btn btn-outline-secondary d-flex align-items-center shadow-sm">
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

    <!-- Pass Data to JS -->
    <script>
        window.tripLogs   = @json($tripLogs);
        window.tripEnded = {{ $trip->end_time ? 'true' : 'false' }};
        window.partyVisits = @json($partyVisits);
    </script>

    <!-- CSS -->
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

        .gm-ui-hover-effect {
            display: none !important;
        }

        /* ---------- LEGEND ---------- */
        .route-legend {
            background: #ffffff;
            border-radius: 12px;
            padding: 14px 16px;
            min-width: 220px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
        }

        .legend-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
            color: #111827;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #374151;
            margin-bottom: 8px;
        }

        .legend-item:last-child {
            margin-bottom: 0;
        }

        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-block;
        }

        .legend-dot.green {
            background-color: #28a745;
        }

        .legend-dot.blue {
            background-color: rgb(0 45 255);
        }

        .legend-dot.red {
            background-color: #dc3545;
        }
    </style>
</main>
@endsection
