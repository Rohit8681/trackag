@extends('admin.layout.layout')

@section('content')

<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Admin Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Admin Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="app-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card card-primary card-outline mb-4">
                        {{-- Card Header --}}
                        <div class="card-header">
                            <h5 class="card-title mb-0">Admin Details</h5>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body">
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#adminDetailsModal">
                                View Admin Details
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</main>

{{-- Modal --}}
<div class="modal fade" id="adminDetailsModal" tabindex="-1" aria-labelledby="adminDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminDetailsModalLabel">Admin Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td>{{ $user->mobile ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>User Type</th>
                            <td>{{ $user->user_type ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Designation</th>
                            <td>{{ $user->designation?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Company</th>
                            <td>{{ $user->company?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Reporting Manager</th>
                            <td>{{ $user->reportingManager?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Headquarter</th>
                            <td>{{ $user->headquarter ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $user->date_of_birth ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $user->gender ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Marital Status</th>
                            <td>{{ $user->marital_status ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Joining Date</th>
                            <td>{{ $user->joining_date ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Emergency Contact No</th>
                            <td>{{ $user->emergency_contact_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Is Self Sale</th>
                            <td>{{ $user->is_self_sale ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <th>Multi-Day Start/End Allowed</th>
                            <td>{{ $user->is_multi_day_start_end_allowed ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <th>Allow Tracking</th>
                            <td>{{ $user->is_allow_tracking ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $user->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td>{{ $user->state?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>District</th>
                            <td>{{ $user->district?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tehsil</th>
                            <td>{{ $user->tehsil?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $user->city?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pincode</th>
                            <td>{{ $user->pincode?->Pincode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Latitude</th>
                            <td>{{ $user->latitude ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Longitude</th>
                            <td>{{ $user->longitude ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Image</th>
                            <td>
                                @if($user->image)
                                    <img src="{{ asset('storage/'.$user->image) }}" alt="Admin Image" class="img-thumbnail" style="width: 100px; height: auto;">
                                @else
                                    <span class="text-muted">No image uploaded</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $user->created_at }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $user->updated_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i> Edit Admin
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
