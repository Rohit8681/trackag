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

                    <!-- LEGENDS WRAPPER -->
                    <div class="d-flex flex-wrap gap-3">
                        <!-- ROUTE TIME LEGEND -->
                        <div class="route-legend">
                            <div class="legend-title">Route Time Legend</div>
                            <div class="legend-row">
                                <div class="legend-item">
                                    <span class="legend-dot green"></span>
                                    <span>6 AM – 12 PM</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot blue"></span>
                                    <span>12 PM – 6 PM</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot red"></span>
                                    <span>6 PM – 12 AM</span>
                                </div>
                            </div>
                        </div>

                        <!-- MAP ICON LEGEND -->
                        <div class="route-legend">
                            <div class="legend-title">Map Icon Legend</div>
                            <div class="legend-row">
                                <div class="legend-item">
                                    <div class="legend-mini-marker" style="border-color: #10b981; color: #10b981;"><i class="fas fa-play"></i></div>
                                    <span>Start</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot blue"></span>
                                    <span>Path</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-mini-marker" style="border-color: #ef4444; color: #ef4444;"><i class="fas fa-store"></i></div>
                                    <span>Customer</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-agent-pulse"></div>
                                    <span>Agent</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-mini-marker" style="border-color: #ef4444; color: #ef4444;"><i class="fas fa-flag-checkered"></i></div>
                                    <span>End</span>
                                </div>
                            </div>
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
        window.farmers   = @json($farmers);
        window.farmVisits = @json($farmVisits);
        window.customers = @json($customers);
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

        /* ---------- CUSTOM HTML MARKERS ---------- */
        .modern-marker {
            width: 40px;
            height: 40px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 2px solid #e5e7eb;
            font-size: 16px;
            color: #4b5563;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
            cursor: pointer;
            z-index: 10;
        }

        .modern-marker:hover {
            transform: scale(1.15) translateY(-4px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.2);
            z-index: 100;
        }

        .modern-marker.start { border-color: #10b981; color: #10b981; }
        .modern-marker.end { border-color: #ef4444; color: #ef4444; }
        .modern-marker.party-visit { border-color: #f59e0b; color: #f59e0b; }
        .modern-marker.farmer { border-color: #10b981; color: #10b981; }
        .modern-marker.farm-visit { border-color: #3b82f6; color: #3b82f6; }
        .modern-marker.customer { border-color: #ef4444; color: #ef4444; }

        /* Small Dots for Path */
        .modern-marker.middle-green, 
        .modern-marker.middle-blue, 
        .modern-marker.middle-red {
            width: 16px;
            height: 16px;
            background: transparent;
            border: none;
            box-shadow: none;
        }

        .modern-marker .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            transition: transform 0.2s ease;
        }
        
        .modern-marker:hover .dot {
            transform: scale(1.5);
        }

        .green-dot { background: #10b981; }
        .blue-dot  { background: #3b82f6; }
        .red-dot   { background: #ef4444; }

        /* Pulse Animation */
        .pulse-marker {
            width: 24px;
            height: 24px;
            background: transparent;
            border: none;
            box-shadow: none;
            position: relative;
        }
        .pulse-marker:hover {
            transform: none;
            box-shadow: none;
        }
        .pulse-core {
            width: 16px;
            height: 16px;
            background: #3b82f6;
            border-radius: 50%;
            border: 2px solid #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            z-index: 2;
        }
        .pulse-ring {
            width: 40px;
            height: 40px;
            background: rgba(59, 130, 246, 0.4);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse-animation 2s infinite cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
        }
        @keyframes pulse-animation {
            0% { transform: translate(-50%, -50%) scale(0.5); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(2); opacity: 0; }
        }

        /* ---------- INFOWINDOW OVERRIDES ---------- */
        /* Hides default padding and borders to allow our custom card to shine */
        .gm-style-iw-c {
            padding: 0 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        }
        .gm-style-iw-d {
            overflow: hidden !important;
        }
        .modern-iw-content {
            font-family: 'Inter', -apple-system, sans-serif;
            min-width: 200px;
            color: #1f2937;
        }
        .modern-iw-content .iw-header {
            background: #f8fafc;
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
        }
        .modern-iw-content .iw-body {
            padding: 12px 16px;
            font-size: 13px;
        }
        .modern-iw-content .iw-body div {
            margin-bottom: 4px;
        }
        .modern-iw-content .iw-body span {
            color: #64748b;
            font-weight: 500;
        }

        /* ---------- LEGEND ---------- */
        .route-legend {
    background: #ffffff;
    border-radius: 12px;
    padding: 14px 16px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
}

.legend-mini-marker {
    width: 22px;
    height: 22px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    border: 2px solid #e5e7eb;
}
.legend-agent-pulse {
    width: 12px; height: 12px; background: #3b82f6; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4); margin: 0 4px;
}


        .legend-title {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 10px;
    color: #111827;
}

/* 🔥 ONE LINE ROW */
.legend-row {
    display: flex;
    align-items: center;
    gap: 20px;
    white-space: nowrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #374151;
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
