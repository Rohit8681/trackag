@extends('admin.layout.layout')

@section('content')
    <main class="app-main">

        <div class="app-content py-4">
            <div class="container-fluid">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-map-marked-alt me-2 text-primary"></i>Trip Route Map
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <!-- Larger Map Section -->
                        <div id="map" class="w-100" style="height: 800px; border-radius: 0 0 6px 6px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pass logs to JS -->
        <script>
            // Trip logs data available to JS
            window.tripLogs = @json($tripLogs);
        </script>

    </main>
@endsection
