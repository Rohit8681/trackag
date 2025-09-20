@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <!-- Header Section -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Customers</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Customer Management</a></li>
                            <li class="breadcrumb-item active">All Customers</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="app-content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <!-- Card Header -->
                    <div class="card-header">
                        <h5 class="card-title">Customer List</h5>
                        @can('create_customers')
                            <a href="{{ route('customers.create') }}" class="btn btn-primary float-end">Add Customer</a>
                        @endcan
                    </div>

                    <!-- Card Body -->
                    <div class="card-body table-responsive">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                <strong>Success:</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                <strong>Error:</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @can('view_customers')
                        <table id="customers-table" class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Company</th>
                                    <th>Executive</th>
                                    @can('toggle_customers')
                                        <th>Status</th>
                                    @endcan
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->id }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ optional($customer->company)->name ?? 'Demo Company' }}</td>
                                        <td>{{ optional($customer->user)->name ?? 'Executive User' }}</td>

                                        @can('toggle_customers')
                                            <td>
                                                <form action="{{ route('customers.toggle', $customer->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="badge {{ $customer->is_active ? 'bg-success' : 'bg-danger' }}"
                                                        onclick="return confirm('Are you sure you want to {{ $customer->is_active ? 'deactivate' : 'activate' }} this customer?')">
                                                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                        @endcan

                                        <td>
                                            <a href="{{ route('customers.show', $customer) }}" class="text-info me-2" title="View">
                                                <i class="fas fa-eye"></i></a>
                                            
                                            @can('edit_customers')
                                                <a href="{{ route('customers.edit', $customer) }}" class="text-warning me-2" title="Edit">
                                                    <i class="fas fa-edit"></i></a>
                                            @endcan

                                            @can('delete_customers')
                                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this customer?')">
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
                                        <td colspan="9" class="text-center">No customers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @endcan

                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div> <!-- /.container-fluid -->
        </div> <!-- /.app-content -->
    </main>
@endsection
