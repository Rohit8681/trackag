@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Companies</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Company Management</a></li>
                            <li class="breadcrumb-item active">Companies</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Company Control Panel</h3>
                                {{-- @can('create_companies') --}}
                                    <a href="{{ route('companies.create') }}" class="btn btn-primary float-end">
                                        Add New Company
                                    </a>
                                {{-- @endcan --}}
                            </div>

                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                        <strong>Success:</strong> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table id="companies-table" class="table table-bordered table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Assign User</th>
                                                <th>Comapny Url</th>
                                                <th>Password</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($companies as $company)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $company->name }}</td>
                                                    <td>{{ $company->code ?? '-' }}</td>
                                                    <td>{{ $company->start_date ? \Carbon\Carbon::parse($company->start_date)->format('m/d/Y') : '-' }}</td>
                                                    <td>{{ $company->validity_upto ? \Carbon\Carbon::parse($company->validity_upto)->format('m/d/Y') : '-' }}</td>

                                                    <td>{{ $company->user_assigned ?? '-' }}</td>
                                                    <td>{{ $company->subdomain }}</td>
                                                    <td>{{ $company->password }}</td>
                                                    <td>
                                                         @can('toggle_companies')
                                                        <form action="{{ route('companies.toggle', $company->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                class="badge {{ $company->is_active ? 'bg-success' : 'bg-danger' }}"
                                                                onclick="return confirm('Are you sure you want to {{ $company->is_active ? 'deactivate' : 'activate' }} this company?')">
                                                                {{ $company->is_active ? 'Active' : 'Inactive' }}
                                                            </button>
                                                        </form>
                                                         @else
                                                            {{ $company->is_active ? 'Active' : 'Inactive' }}
                                                        @endcan
                                                    </td>
                                                    
                                                    <td>{{ $company->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>{{ $company->updated_at->format('Y-m-d H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('companies.show', $company) }}" class="text-info me-2" title="View">
                                                            <i class="fas fa-eye"></i></a>&nbsp;&nbsp;

                                                        {{-- @can('edit_companies') --}}
                                                            <a href="{{ route('companies.edit', $company) }}" class="text-warning me-2" title="Edit">
                                                                <i class="fas fa-edit"></i></a>&nbsp;&nbsp;
                                                        {{-- @endcan --}}

                                                        @can('delete_companies')
                                                            <form action="{{ route('companies.destroy', $company) }}" method="POST" class="d-inline"
                                                                onsubmit="return confirm('Are you sure to delete this company?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link p-0 text-danger" title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">No companies found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{-- @endcan --}}

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
    var companiesCount = @json($companies->count());
    if (companiesCount > 0) {
        $('#companies-table').DataTable({
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
</script>
@endpush
