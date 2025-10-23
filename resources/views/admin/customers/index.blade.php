@extends('admin.layout.layout')
@section('title', 'List Customer | Trackag')

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
                <div class="card card-primary card-outline mb-4">
                    <!-- Card Header -->
                    <div class="card-header">
                        <h5 class="card-title">Customer List</h5>
                        @can('create_customers')
                            <a href="{{ route('customers.create') }}" class="btn btn-sm btn-primary float-end">Add Customer</a>
                            <button class="btn btn-sm btn-success float-end" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fas fa-file-import"></i> Import Customers
                            </button>
                        @endcan
                    </div>

                    <!-- Card Body -->
                    <div class="card-body table-responsive">

                        <!-- 🔍 Filter Form -->
                        <form action="{{ route('customers.index') }}" method="GET" class="row g-3 mb-3">
                            <div class="col-md-2">
                                <label class="form-label">Financial Year</label>
                                <select name="financial_year" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    @foreach($financialYears as $year)
                                        <option value="{{ $year }}" {{ request('financial_year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Party Code</label>
                                <input type="text" name="party_code" value="{{ request('party_code') }}"
                                    class="form-control form-control-sm" placeholder="Enter Party Code">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Agro Name</label>
                                <input type="text" name="agro_name" value="{{ request('agro_name') }}"
                                    class="form-control form-control-sm" placeholder="Enter Agro Name">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">State</label>
                                <select name="state_id" class="form-select form-select-sm">
                                    <option value="">All States</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person" value="{{ request('contact_person') }}"
                                    class="form-control form-control-sm" placeholder="Enter Name">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-12 d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-sm btn-primary me-2">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('customers.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>
                        </form>
                        <!-- 🔍 End Filter Form -->

                        @can('view_customers')
                        <table id="customers-table" class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#ID</th>
                                    <th>Agro Name</th>
                                    <th>Party Code</th>
                                    <th>Address</th>
                                    <th>Mobile No</th>
                                    <th>Contact Person Name</th>
                                    <th>Employee Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->id }}</td>
                                        <td>{{ $customer->agro_name }}</td>
                                        <td>{{ $customer->party_code }}</td>
                                        <td>
                                            <span><b>State:</b> {{ optional($customer->state)->name ?? '' }}</span><br>
                                            <span><b>District:</b> {{ optional($customer->district)->name ?? '' }}</span><br>
                                            <span><b>Tehsil:</b> {{ optional($customer->tehsil)->name ?? '' }}</span><br>
                                            <span>{{ $customer->address }}</span>
                                        </td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->contact_person_name ?? '-' }}</td>
                                        <td>{{ optional($customer->user)->name ?? 'Executive User' }}</td>

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

                                        <td>
                                            @can('edit_customers')
                                                <a href="{{ route('customers.edit', $customer) }}" class="text-warning me-2" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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

       <!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">Import Customers</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Select File (.xlsx, .xls, .csv)</label>
                <input type="file" name="file" class="form-control" required accept=".xlsx,.xls,.csv">
            </div>

            <p class="small text-muted">
                <b>Required Columns:</b> agro_name, party_code, state, district, tehsil, address, phone, gst_no, contact_person_name, depo, credit_limit, party_active_since, status
            </p>
        </div>
        <div class="modal-footer d-flex justify-content-between">
            <a href="{{ route('customers.sample-download') }}" class="btn btn-info">
                <i class="fas fa-download"></i> Download Sample
            </a>
            <button type="submit" class="btn btn-success">Import</button>
        </div>
    </form>
  </div>
</div>


    </main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var customers = @json($customers->count());
    if (customers > 0) {
        $('#customers-table').DataTable({
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
