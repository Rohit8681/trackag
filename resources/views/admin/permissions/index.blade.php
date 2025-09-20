@extends('admin.layout.layout')

@section('content')

    <main class="app-main">

        <!-- Page Header -->
        <div class="app-content-header py-3 mb-4 bg-light border-bottom">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Permissions</h3>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">User Management</a></li>
                            <li class="breadcrumb-item active">Permissions</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <div class="card mb-4">
                            {{-- <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Permission Control Panel</h5>
                            @can('create_permissions')
                                <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add New Permission
                                </a>
                            @endcan
                        </div> --}}


                            <div class="card-header">
                                <h3 class="card-title">Permission Control Panel</h3>
                                @can('create_permissions')
                                    <a href="{{ route('permissions.create') }}" class="btn btn-primary float-end"
                                        style="max-width: 150px;">
                                        Add Permission
                                    </a>
                                @endcan
                            </div>


                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success:</strong> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @can('view_permissions')
                                    <div class="table-responsive">
                                        <table id="permissions-table" class="table table-bordered table-striped align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Guard Name</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($permissions as $permission)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $permission->name }}</td>
                                                        <td>{{ $permission->guard_name }}</td>
                                                        <td>{{ $permission->created_at->format('Y-m-d') }}</td>
                                                        <td>
                                                            @can('view_permissions')
                                                                <a href="{{ route('permissions.show', $permission) }}"
                                                                    class="text-info me-2" title="View Permission">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endcan

                                                            @can('edit_permissions')
                                                                <a href="{{ route('permissions.edit', $permission) }}"
                                                                    class="text-warning me-2" title="Edit Permission">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endcan

                                                            @can('delete_permissions')
                                                                <form action="{{ route('permissions.destroy', $permission) }}"
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Are you sure to delete this permission?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-link text-danger p-0 m-0"
                                                                        title="Delete Permission">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">No permissions found.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endcan

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </main>

@endsection
