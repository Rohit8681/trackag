@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Trip Management</h3>
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
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">Add New Trip</h5>
                </div>

                <!-- Flash & Validation Messages -->
                <div class="card-body">
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
                        <div class="mb-4 border-bottom pb-2">
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
                        <div class="mb-4 border-bottom pb-2">
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
                        <div class="mb-4 border-bottom pb-2">
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
                        <div class="mb-4 border-bottom pb-2">
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
                        <div class="mb-4 border-bottom pb-2">
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
                        <div class="mb-4">
                            <h5 class="mb-3">Map Preview</h5>
                            <div id="map" style="height: 400px; border: 1px solid #ccc;"></div>
                        </div>

                        <!-- Submit -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Save Trip</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
