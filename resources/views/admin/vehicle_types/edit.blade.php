@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Edit Vehicle Type</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('vehicle-types.index') }}">Vehicle Type</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Update Vehicle Type</h3>
                    </div>

                    <form action="{{ route('vehicle-types.update', $vehicleType->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Type</label>
                                <input type="text" name="vehicle_type" class="form-control"
                                    value="{{ old('vehicle_type', $vehicleType->vehicle_type) }}" required>
                                @error('vehicle_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('vehicle-types.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
