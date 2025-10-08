@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Holiday List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Holiday Master</a></li>
                        <li class="breadcrumb-item active">Holidays</li>
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
                            <h3 class="card-title mb-0">Holiday List</h3>
                            <a href="{{ route('holidays.create') }}" class="btn btn-sm btn-primary ms-auto">
                                <i class="fas fa-plus me-1"></i> Add Holiday
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

                            {{-- 📋 Holiday Table --}}
                            <div class="table-responsive" style="max-height: 600px;">
                                <table id="holidays-table" class="table table-bordered table-hover table-striped align-middle table-sm">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th style="width: 40px;">Sr. No.</th>
                                            <th>Holiday Date</th>
                                            <th>Holiday Name</th>
                                            <th>Holiday Type</th>
                                            <th>State</th>
                                            <th>Paid</th>
                                            <th>Status</th>
                                            <th style="width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($holidays as $index => $holiday)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $holiday->holiday_date }}</td>
                                                <td>{{ $holiday->holiday_name }}</td>
                                                <td>{{ $holiday->holiday_type }}</td>
                                                <td>{{ $holiday->state->name ?? 'All States' }}</td>
                                                <td>{{ $holiday->is_paid }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status" type="checkbox"
                                                            data-id="{{ $holiday->id }}"
                                                            {{ $holiday->status ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('holidays.edit', $holiday->id) }}"
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('holidays.destroy', $holiday->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure to delete this holiday?')">
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
                                                <td colspan="8" class="text-center text-muted">No holidays found.</td>
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
    var holidays = @json($holidays->count());
    if (holidays > 0) {
        $('#holidays-table').DataTable({
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
    let holiday_id = $(this).data('id');

    $.ajax({
        url: "{{ route('holidays.toggle-status') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: holiday_id,
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