@extends('admin.layout.layout')
@section('title', 'Designations | Trackag')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Designations</h3>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('designations.create') }}" class="btn btn-primary">Add New Designation</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="designation-table" class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($designations as $key => $designation)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $designation->name }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-status" type="checkbox"
                                                    data-id="{{ $designation->id }}"
                                                    {{ $designation->status ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    {{ $designation->status ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('designations.edit', $designation->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('designations.destroy', $designation->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No designations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
<script>
$(document).ready(function () {
    var designationsCount = @json($designations->count());
    if (designationsCount > 0) {
        $('#designation-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: -1 } 
            ]
        });
    }

    $('.toggle-status').change(function () {
        let status = $(this).prop('checked') ? 1 : 0;
        let designation_id = $(this).data('id');

        $.ajax({
            url: "{{ route('designations.toggle-status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: designation_id,
                status: status
            },
            success: function (response) {
                // alert(response.message);
            },
            error: function () {
                alert('Something went wrong!');
            }
        });
    });
});
</script>
@endpush