@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <!-- Page Header -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Vehicle Type List</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Vehicle Master</a></li>
                            <li class="breadcrumb-item active">Vehicle Types</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card card-primary card-outline">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title mb-0">Vehicle Types</h3>
                                <a href="{{ route('vehicle-types.create') }}" class="btn btn-sm btn-primary ms-auto">
                                    <i class="fas fa-plus me-1"></i> Add Vehicle Type
                                </a>
                            </div>

                            <div class="card-body">
                                {{-- ✅ Success Message --}}
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success:</strong> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                {{-- 🚗 Vehicle Types Table --}}
                                <div class="table-responsive" style="max-height: 600px;">
                                    <table class="table table-bordered table-hover table-striped align-middle table-sm">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="width: 40px;">No</th>
                                                <th>Vehicle Type</th>
                                                <th style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($vehicleTypes as $index => $vehicleType)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $vehicleType->vehicle_type }}</td>
                                                    <td>
                                                        <a href="{{ route('vehicle-types.edit', $vehicleType->id) }}"
                                                           class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('vehicle-types.destroy', $vehicleType->id) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this Vehicle Type?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">No vehicle types found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
