@extends('admin.layout.layout')

<style>
    .trip-form-page {
        background: #f4f7fb;
        min-height: calc(100vh - 57px);
    }

    .trip-form-hero {
        background: linear-gradient(135deg, #123c69 0%, #1d6fa5 52%, #1b8a5a 100%);
        border-radius: 8px;
        color: #fff;
        padding: 22px 24px;
        box-shadow: 0 14px 34px rgba(18, 60, 105, 0.22);
    }

    .trip-form-hero .breadcrumb a,
    .trip-form-hero .breadcrumb-item,
    .trip-form-hero .breadcrumb-item.active {
        color: rgba(255,255,255,0.86);
    }

    .trip-form-card {
        border: 1px solid #e5edf5;
        border-radius: 8px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    }

    .trip-section {
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 8px;
        padding: 18px;
        margin-bottom: 18px;
    }

    .trip-section h5 {
        color: #17395c;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .trip-section .form-label {
        color: #40556c;
        font-size: 13px;
        font-weight: 600;
    }

    .trip-section .form-control,
    .trip-section .form-select {
        border-color: #d8e2ec;
        border-radius: 7px;
    }

    .trip-action-bar {
        background: #f8fafc;
        border: 1px solid #e6edf5;
        border-radius: 8px;
        padding: 14px;
    }
</style>

@section('content')
<main class="app-main trip-form-page">
    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="trip-form-hero d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1 fw-bold"><i class="fas fa-route me-2"></i>Trip Management</h3>
                    <div class="small opacity-75">Add trip details, odometer readings and customer visits</div>
                </div>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('trips.index') }}">Trips</a></li>
                    <li class="breadcrumb-item active">Add Trip</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="card trip-form-card">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="card-title">Add New Trip</h5>
                </div>

                <!-- Flash & Validation Messages -->
                <div class="card-body px-4 pb-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success:</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endforeach
                    @endif

                    <!-- Trip Form -->
                    <form method="POST" action="{{ route('trips.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Trip Info -->
                        <div class="trip-section">
                            <h5 class="mb-3">Trip Details</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="trip_date" class="form-label">Trip Date</label>
                                    <input type="date" name="trip_date" class="form-control" value="{{ old('trip_date') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Travel Mode & Purpose -->
                        <div class="trip-section">
                            <h5 class="mb-3">Travel & Purpose</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="travel_mode" class="form-label">Travel Mode</label>
                                    <select name="travel_mode" class="form-select" required>
                                        <option value="">-- Select Mode --</option>
                                        @foreach ($travelModes as $mode)
                                            <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="tour_type" class="form-label">Tour Type</label>
                                    <select name="tour_type" class="form-select" required>
                                        <option value="">-- Select Type --</option>
                                        @foreach ($tourTypes as $mode)
                                            <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="purpose" class="form-label">Purpose</label>
                                    <select name="purpose" class="form-select" required>
                                        <option value="">-- Select Purpose --</option>
                                        @foreach ($purposes as $mode)
                                            <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Locations -->
                        <div class="trip-section">
                            <h5 class="mb-3">Locations & Distances</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Start Location</label>
                                    <div class="input-group">
                                        <input type="text" name="start_lat" class="form-control me-2" placeholder="Latitude" required>
                                        <input type="text" name="start_lng" class="form-control" placeholder="Longitude" required>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="setCurrentLocation('start')">Use Current Location</button>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">End Location</label>
                                    <div class="input-group">
                                        <input type="text" name="end_lat" class="form-control me-2" placeholder="Latitude" required>
                                        <input type="text" name="end_lng" class="form-control" placeholder="Longitude" required>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="setCurrentLocation('end')">Use Current Location</button>
                                </div>
                                <div class="col-md-6">
                                    <label for="place_to_visit" class="form-label">Place To Visit</label>
                                    <input type="text" name="place_to_visit" class="form-control" value="{{ old('place_to_visit') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="total_distance_km" class="form-label">Calculated Distance (km)</label>
                                    <input type="text" name="total_distance_km" class="form-control" value="{{ old('total_distance_km') }}">
                                </div>
                            </div>
                        </div>

                        <!-- KM Logs -->
                        <div class="trip-section">
                            <h5 class="mb-3">Odometer Readings</h5>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="starting_km" class="form-label">Opening (km)</label>
                                    <input type="text" name="starting_km" class="form-control" value="{{ old('starting_km') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="start_km_photo" class="form-label">Opening KM Image</label>
                                    <input type="file" name="start_km_photo" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_km" class="form-label">End (km)</label>
                                    <input type="text" name="end_km" class="form-control" value="{{ old('end_km') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_km_photo" class="form-label">End KM Image</label>
                                    <input type="file" name="end_km_photo" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- Customers -->
                        <div class="trip-section">
                            <h5 class="mb-3">Customers</h5>
                            <label for="customer_ids" class="form-label">Select Customers</label>
                            <select name="customer_ids[]" class="form-select" multiple required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl / Command to select multiple</small>
                        </div>

                        <!-- Map -->
                        <div class="trip-section">
                            <h5 class="mb-3">Map Preview</h5>
                            <div id="map" style="height: 400px; border: 1px solid #d8e2ec; border-radius: 8px;"></div>
                        </div>

                        <!-- Submit -->
                        <div class="trip-action-bar text-end">
                            <button type="submit" class="btn btn-success px-4"><i class="fas fa-save me-1"></i> Save Trip</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
