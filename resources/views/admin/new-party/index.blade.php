@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header py-3 bg-light border-bottom">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <h3 class="fw-bold mb-0 text-primary">
                        <i class="fas fa-store me-2 text-secondary"></i> New Party List
                    </h3>
                    <p class="text-muted small mb-0">Manage newly added parties and approval status</p>
                </div>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">New Party</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="app-content py-4">
        <div class="container-fluid px-4">

            {{-- 🔶 Filter Section (Yellow Header Style) --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3 bg-warning bg-opacity-25 rounded-3">
                    <form class="row g-3 align-items-end" method="GET" action="{{ url('admin/new-party') }}">

                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark">Financial Year</label>
                        <select class="form-select" name="financial_year">
                            <option value="">Select</option>
                            <option value="2024-2025" {{ request('financial_year')=='2024-2025'?'selected':'' }}>2024-2025</option>
                            <option value="2023-2024" {{ request('financial_year')=='2023-2024'?'selected':'' }}>2023-2024</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark">From Date</label>
                        <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark">To Date</label>
                        <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark">State</label>
                        <select class="form-select" name="state_id">
                            <option value="">Select State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ request('state_id')==$state->id?'selected':'' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark">Employee Name</label>
                        <select class="form-select" name="user_id">
                            <option value="">Select Employee</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id')==$user->id?'selected':'' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark">Agro Name</label>
                        <input type="text" name="agro_name" class="form-control" value="{{ request('agro_name') }}">
                    </div>

                    <div class="col-md-2 mt-3">
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>

                    <div class="col-md-2 mt-3">
                        <a href="{{ url('admin/new-party') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>

                </form>
                </div>
            </div>

            {{-- 📋 New Party List Table --}}
            <div class="card shadow-sm border-0">
                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle text-nowrap">
                        <thead class="text-center align-middle" style="background-color: #FFD966;">
                            
                            <tr>
                                <th rowspan="2">Sr. No.</th>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Sales Person Name</th>
                                <th rowspan="2">Shop Name</th>
                                <th rowspan="2">Mobile No</th>
                                <th rowspan="2">Address</th>
                                <th rowspan="2">Contact Person</th>
                                <th rowspan="2">Working With</th>
                                <th rowspan="2">Party Document</th>
                                <th colspan="2">Status</th>
                                <th rowspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customer as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->visit_date?->format('d-m-Y') }}</td>
                                <td>{{ $item->user?->name }}</td>
                                <td>{{ $item->agro_name }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->address }}</td>
                                <td>{{ $item->contact_person_name }}</td>
                                <td>{{ $item->working_with }}</td>

                                <td>
                                    @if ($item->party_documents)
                                        @foreach ($item->party_documents as $doc)
                                            <a href="{{ $doc }}" target="_blank" class="badge bg-info text-dark d-block my-1">
                                                View Document
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No Documents</span>
                                    @endif
                                </td>

                                <td>
                                    @if($item->status == 'approved')
                                        <span class="badge bg-success">Approved</span>

                                    @elseif($item->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>

                                    @elseif($item->status == 'hold')
                                        <span class="badge bg-warning text-dark">Hold</span>

                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                            Action
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="#" class="dropdown-item" 
                                                onclick="openStatusModal({{ $item->id }}, 'approved')">
                                                    Approve
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#" class="dropdown-item" 
                                                onclick="openStatusModal({{ $item->id }}, 'rejected')">
                                                    Reject
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#" class="dropdown-item" 
                                                onclick="openStatusModal({{ $item->id }}, 'hold')">
                                                    Hold
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-exclamation-circle me-2 text-warning"></i>
                                    <strong>No data found</strong>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ url('admin/new-party/status-update') }}">
            @csrf
            <input type="hidden" name="customer_id" id="customerId">
            <input type="hidden" name="status" id="statusType">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Remark <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="remark" rows="3" required></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
</main>
@endsection
@push('scripts')
<script>
function openStatusModal(id, status) {
    document.getElementById('customerId').value = id;
    document.getElementById('statusType').value = status;

    var modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}
</script>
@endpush
