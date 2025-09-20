@extends('admin.layout.layout')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Trip</h4>
                    <a href="{{ route('trips.index') }}" class="btn btn-sm btn-secondary">Back to Trips</a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Please fix the following errors:
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <p><strong>Created By:</strong> {{ $trip->user->name ?? 'N/A' }}</p>
                        <p><strong>Company:</strong> {{ $trip->company->name ?? 'N/A' }}</p>
                    </div>

                    <form action="{{ route('trips.update', $trip) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3">Trip Details</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label>Trip Date</label>
                                <input type="date" name="trip_date" class="form-control" value="{{ old('trip_date', $trip->trip_date) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label>Start Time</label>
                                <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $trip->start_time) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label>End Time</label>
                                <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $trip->end_time) }}">
                            </div>

                            <div class="col-md-4">
                                <label>Travel Mode</label>
                                <select name="travel_mode" class="form-select" required>
                                    <option value="">-- Select Mode --</option>
                                    @foreach ($travelModes as $mode)
                                        <option value="{{ $mode->id }}" @if($trip->travel_mode == $mode->id) selected @endif>{{ $mode->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Tour Type</label>
                                <select name="tour_type" class="form-select" required>
                                    <option value="">-- Select Type --</option>
                                    @foreach ($tourTypes as $mode)
                                        <option value="{{ $mode->id }}" @if($trip->tour_type == $mode->id) selected @endif>{{ $mode->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Purpose</label>
                                <select name="purpose" class="form-select" required>
                                    <option value="">-- Select Purpose --</option>
                                    @foreach ($purposes as $mode)
                                        <option value="{{ $mode->id }}" @if($trip->purpose == $mode->id) selected @endif>{{ $mode->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h5 class="mb-3">Locations & Distances</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label>Place To Visit</label>
                                <input type="text" name="place_to_visit" class="form-control" value="{{ old('place_to_visit', $trip->place_to_visit) }}">
                            </div>
                            <div class="col-md-6">
                                <label>Calculated Distance (km)</label>
                                <input type="text" name="total_distance_km" class="form-control" value="{{ old('total_distance_km', $trip->total_distance_km) }}">
                            </div>

                            <div class="col-md-3">
                                <label>Start Latitude</label>
                                <input type="text" name="start_lat" class="form-control" value="{{ old('start_lat', $trip->start_lat) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label>Start Longitude</label>
                                <input type="text" name="start_lng" class="form-control" value="{{ old('start_lng', $trip->start_lng) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label>End Latitude</label>
                                <input type="text" name="end_lat" class="form-control" value="{{ old('end_lat', $trip->end_lat) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label>End Longitude</label>
                                <input type="text" name="end_lng" class="form-control" value="{{ old('end_lng', $trip->end_lng) }}" required>
                            </div>
                        </div>

                        <h5 class="mb-3">Odometer Readings</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label>Opening (km)</label>
                                <input type="text" name="starting_km" class="form-control" value="{{ old('starting_km', $trip->starting_km) }}">
                            </div>
                            <div class="col-md-3">
                                <label>Opening KM Image</label>
                                <input type="file" name="start_km_photo" class="form-control" accept="image/*">
                                @if($trip->start_km_photo)
                                    <small class="d-block mt-1">Current: <a href="{{ asset('storage/'.$trip->start_km_photo) }}" target="_blank">View</a></small>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <label>End (km)</label>
                                <input type="text" name="end_km" class="form-control" value="{{ old('end_km', $trip->end_km) }}">
                            </div>
                            <div class="col-md-3">
                                <label>End KM Image</label>
                                <input type="file" name="end_km_photo" class="form-control" accept="image/*">
                                @if($trip->end_km_photo)
                                    <small class="d-block mt-1">Current: <a href="{{ asset('storage/'.$trip->end_km_photo) }}" target="_blank">View</a></small>
                                @endif
                            </div>
                        </div>

                        <h5 class="mb-3">Customers</h5>
                        <div class="mb-4">
                            <select name="customer_ids[]" class="form-select" multiple>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ in_array($customer->id, $trip->customers->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl (Cmd on Mac) to select multiple customers.</small>
                        </div>

                        <h5 class="mb-3">Approval</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label>Approval Status</label>
                                <select name="approval_status" class="form-select" required>
                                    <option value="pending" {{ old('approval_status', $trip->approval_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('approval_status', $trip->approval_status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="denied" {{ old('approval_status', $trip->approval_status) === 'denied' ? 'selected' : '' }}>Denied</option>
                                </select>
                            </div>
                            <div class="col-md-9" id="denial-reason-block" style="display: {{ old('approval_status', $trip->approval_status) === 'denied' ? 'block' : 'none' }};">
                                <label>Reason for Denial</label>
                                <textarea name="approval_reason" class="form-control" rows="3">{{ old('approval_reason', $trip->approval_reason) }}</textarea>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">Update Trip</button>
                            <a href="{{ route('trips.index') }}" class="btn btn-light">Cancel</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
