@extends('admin.layout.layout')
@section('title', 'Party Payment List | Trackag')
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
                    <h3 class="mb-0">Party Payment</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Party Payment</a></li>
                        <li class="breadcrumb-item active">Party Payment</li>
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
                    <h5 class="card-title mb-0">Party Payment List</h5>
                    
                </div>

                <!-- Card Body -->
                <div class="card-body table-responsive">

                    <!-- 🔍 Filter Form -->
                    <form action="{{ route('party-payment') }}" method="GET" class="row g-3 mb-3">
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
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="pending" {{ request('payment_status')=='pending'?'selected':'' }}>Pending</option>
                                <option value="payment received" {{ request('payment_status')=='payment received'?'selected':'' }}>Payment Received</option>
                            </select>
                        </div>

                        {{-- <div class="col-12 d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-sm btn-primary me-2">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('party-payment') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div> --}}
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>

                            <a href="{{ route('party-payment') }}" class="btn btn-sm btn-secondary w-100">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </form>
                    <!-- 🔍 End Filter Form -->

                    <!-- 📋 Expense Table -->
                    <table id="party-payment-table" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Entry Date</th>
                                <th>Payment Date</th>
                                <th>Agro Name</th>
                                <th>Sales Person Name</th>
                                <th>Payment Info</th>
                                <th>Amount</th>
                                <th>Image</th>
                                <th>Clear/Return Date</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key => $val)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($val->created_at)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($val->payment_date)->format('d M Y') }}</td>
                                    <td>{{ optional($val->customer)->agro_name ?? '-' }}</td>
                                    <td>{{ optional($val->user)->name ?? '-' }}</td>
                                    <td>
                                        <span>Mode: {{ $val->payment_mode ?? "-" }}</span><br>
                                        <span>Ref No: -</span><br>
                                        <span>Bank: {{ $val->bank_name }}</span><br>
                                        <span>Branch: {{ $val->branch_name }}</span>
                                    </td>
                                    <td>{{ $val->amount }}</td>
                                    <td>
                                        <a href="{{ asset('storage/party-payments/'.$val->image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-image"></i> View
                                        </a>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($val->clear_return_date)->format('d M Y') }}</td>

                                    <td><span class="badge 
                                            @if($val->status == 'payment received') bg-success 
                                            @elseif($val->status == 'pending') bg-danger 
                                            @else bg-warning text-dark 
                                            @endif">
                                            {{ $val->status ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($val->status == 'pending')
                                            <button 
                                                class="btn btn-sm btn-success open-clear-modal"
                                                data-id="{{ $val->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#clearReturnModal"
                                            >
                                                <i class="fas fa-check"></i> 
                                            </button>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No Party Payment found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.app-content -->
    <div class="modal fade" id="clearReturnModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('party-payment.clear-return') }}">
            @csrf
            <input type="hidden" name="id" id="payment_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Clear Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Clear / Return Date</label>
                        <input type="date" name="clear_return_date" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
</main>
@endsection


@push('scripts')
<script>
$(document).on('click', '.open-clear-modal', function () {
    let id = $(this).data('id');
    $('#payment_id').val(id);
});
$(document).ready(function() {
    var party_payment = @json($data->count());
    if (party_payment > 0) {
        $('#party-payment-table').DataTable({
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
