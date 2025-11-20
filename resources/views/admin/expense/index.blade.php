@extends('admin.layout.layout')
@section('title', 'Expense List | Trackag')
@push('styles')
<style>
    .action-buttons {
        display: flex;
        gap: 5px;
        align-items: center;
        white-space: nowrap;
    }

    .action-buttons .btn-sm {
        padding: 2px 6px;
        font-size: 11px;
    }
</style>
@endpush
@section('content')
<main class="app-main">

    <!-- Header Section -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Expenses</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Expense Management</a></li>
                        <li class="breadcrumb-item active">All Expenses</li>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Expense List</h5>
                    
                </div>

                <!-- Card Body -->
                <div class="card-body table-responsive">

                    <!-- 🔍 Filter Form -->
                    <form action="{{ route('expense.index') }}" method="GET" class="row g-3 mb-3">
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">State</label>
                            <select name="state_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Employee Name</label>
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($employees as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Bill Type</label>
                            <select name="bill_type" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="Petrol" {{ request('bill_type')=='Petrol'?'selected':'' }}>Petrol</option>
                                <option value="Food" {{ request('bill_type')=='Food'?'selected':'' }}>Food</option>
                                <option value="Accommodation" {{ request('bill_type')=='Accommodation'?'selected':'' }}>Accommodation</option>
                                <option value="Travel" {{ request('bill_type')=='Travel'?'selected':'' }}>Travel</option>
                                <option value="Courier" {{ request('bill_type')=='Courier'?'selected':'' }}>Courier</option>
                                <option value="Others" {{ request('bill_type')=='Others'?'selected':'' }}>Others</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Approval Status</label>
                            <select name="approval_status" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="Pending" {{ request('approval_status')=='Pending'?'selected':'' }}>Pending</option>
                                <option value="Approved" {{ request('approval_status')=='Approved'?'selected':'' }}>Approved</option>
                                <option value="Rejected" {{ request('approval_status')=='Rejected'?'selected':'' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-sm btn-primary me-2">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('expense.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </form>
                    <!-- 🔍 End Filter Form -->

                    <!-- 📋 Expense Table -->
                    <table id="expenses-table" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Agent Name</th>
                                <th>Mobile</th>
                                <th>Bill Date</th>
                                <th>Bill Type</th>
                                <th>Bill Title</th>
                                <th>Bill Details</th>
                                <th>Travel Mode</th>
                                <th>Amount</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $key => $expense)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ optional($expense->user)->name ?? '-' }}</td>
                                    <td>{{ optional($expense->user)->mobile ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->bill_date)->format('d M Y') }}</td>
                                    <td>{{ $expense->bill_type }}</td>
                                    <td>{{ $expense->bill_title ?? '-' }}</td>
                                    <td>{{ $expense->bill_details_description ?? '-' }}</td>
                                    <td>{{ $expense->travel_mode ?? '-' }}</td>
                                    <td>₹{{ number_format($expense->amount, 2) }}</td>
                                    <td><a href="{{ asset('storage/expenses/'.$expense->image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-image"></i> View
                              </a></td>
                                    <td>
                                        <span class="badge 
                                            @if($expense->approval_status == 'Approved') bg-success 
                                            @elseif($expense->approval_status == 'Rejected') bg-danger 
                                            @else bg-warning text-dark 
                                            @endif">
                                            {{ $expense->approval_status ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td calss="action-buttons">
                                        @if($expense->approval_status != 'Approved' && $expense->approval_status != 'Rejected')
                                        <a href="{{ route('expense.edit', $expense->id) }}" class="text-warning me-2" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif

                                        <form action="{{ route('expense.destroy', $expense->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this expense?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                         @if($expense->approval_status != 'Approved')
                                            <form action="{{ route('expense.approve', $expense->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success me-1"
                                                        onclick="return confirm('Approve this expense?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Reject -->
                                        @if($expense->approval_status != 'Rejected')
                                            <form action="{{ route('expense.reject', $expense->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger me-1"
                                                        onclick="return confirm('Reject this expense?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No expenses found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.app-content -->
</main>
@endsection


@push('scripts')
<script>
$(document).ready(function() {
    var expenses = @json($expenses->count());
    if (expenses > 0) {
        $('#expenses-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: -1 } // Action column not sortable
            ]
        });
    }
});
</script>
@endpush
