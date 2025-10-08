@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Leave List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Leave Master</a></li>
                        <li class="breadcrumb-item active">Leaves</li>
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
                            <h3 class="card-title mb-0">Leave List</h3>
                            <a href="{{ route('leaves.create') }}" class="btn btn-sm btn-primary ms-auto">
                                <i class="fas fa-plus me-1"></i> Add Leave
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

                            {{-- 📋 Leave Table --}}
                            <div class="table-responsive" style="max-height: 600px;">
                                <table id="leaves-table" class="table table-bordered table-hover table-striped align-middle table-sm">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th style="width: 40px;">Sr. No.</th>
                                            <th>Leave Name</th>
                                            <th>Leave Code</th>
                                            <th>Paid</th>
                                            <th>Status</th>
                                            <th style="width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($leaves as $index => $leave)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $leave->leave_name }}</td>
                                                <td>{{ $leave->leave_code }}</td>
                                                <td>{{ $leave->is_paid }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status" type="checkbox"
                                                            data-id="{{ $leave->id }}"
                                                            {{ $leave->status ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('leaves.edit', $leave->id) }}"
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('leaves.destroy', $leave->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure to delete this leave?')">
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
                                                <td colspan="6" class="text-center text-muted">No leaves found.</td>
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
@push('scripts')
<script>
$(document).ready(function() {
    var leaves = @json($leaves->count());
    if (leaves > 0) {
        $('#leaves-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: -1 } // Actions column not orderable
            ]
        });
    }
});
$('.toggle-status').change(function () {
    let status = $(this).prop('checked') ? 1 : 0;
    let leave_id = $(this).data('id');

    $.ajax({
        url: "{{ route('leaves.toggle-status') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: leave_id,
            status: status
        },
        success: function (response) {
            Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: 'Status updated successfully',
              showConfirmButton: false,
              timer: 1500
            })
        },
        error: function () {
            alert('Something went wrong!');
        }
    });
});
</script>
@endpush
