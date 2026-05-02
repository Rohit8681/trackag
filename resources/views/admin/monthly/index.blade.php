@extends('admin.layout.layout')

@push('styles')
<style>
    .card-premium {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        overflow: hidden;
    }
    .card-premium .card-header {
        border-bottom: none;
        padding: 1.25rem;
    }
    .table-premium thead th {
        background-color: #f8f9fa;
        color: #334155;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-premium tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #475569;
    }
    .product-row {
        background-color: #f1f5f9 !important;
        font-weight: 700;
        color: #1e293b;
    }
    .qty-link {
        color: #0d6efd;
        text-decoration: none;
        transition: all 0.2s;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .qty-link:hover {
        background-color: #e0f2fe;
        color: #0369a1;
        text-decoration: none;
    }
    .filter-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .btn-go {
        height: 38px;
        padding: 0 25px;
        font-weight: 600;
        border-radius: 8px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border: none;
        color: white;
    }
    .btn-go:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Monthly Sales Plan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Monthly Plan
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card filter-card border-0 mb-4">
                        <div class="card-body p-4">
                            <form action="{{ route('monthly.index') }}" method="GET">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label fw-600">Month</label>
                                        <select name="month" class="form-select select2">
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                                    {{ date("F", mktime(0, 0, 0, $i, 10)) }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-600">Employee</label>
                                        <select name="employee_id" class="form-select select2">
                                            <option value="">All Employees</option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-600">State</label>
                                        <select name="state_id" class="form-select select2">
                                            <option value="">All States</option>
                                            @foreach($states as $st)
                                                <option value="{{ $st->id }}" {{ request('state_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-600">Product</label>
                                        <select name="product_id" class="form-select select2">
                                            <option value="">All Products</option>
                                            @foreach($products as $prod)
                                                <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-go w-100">GO</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- State-Wise Summary Table -->
                <div class="col-lg-12">
                    <div class="card card-premium mb-4">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2"></i> Product & State Wise Plan Summary</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-premium table-hover text-center text-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Product Name</th>
                                            @foreach($uniqueStates as $stId => $stName)
                                                <th>{{ $stName }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reportData as $productId => $product)
                                            <tr class="product-row">
                                                <td class="text-start" colspan="{{ count($uniqueStates) + 1 }}">
                                                    <i class="bi bi-box-seam me-2"></i> {{ $product['name'] }}
                                                </td>
                                            </tr>
                                            @foreach($product['packings'] as $packing)
                                                <tr>
                                                    <td class="text-start ps-4">{{ $packing['name'] }}</td>
                                                    @foreach($uniqueStates as $stId => $stName)
                                                        @php
                                                            $qty = $packing['states'][$stId] ?? 0;
                                                        @endphp
                                                        <td>
                                                            @if($qty > 0)
                                                                <a href="javascript:void(0)" 
                                                                   onclick="loadEmployees('{{ $stId }}', '{{ $stName }}', '{{ $productId }}', '{{ $product['name'] }}', '{{ $month }}')"
                                                                   class="qty-link fw-bold">
                                                                    {{ number_format($qty) }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted opacity-50">-</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($uniqueStates) + 1 }}" class="text-center py-5 text-muted">
                                                    <i class="bi bi-inbox fa-3x d-block mb-3"></i>
                                                    No plan data found for the selected month.
                                                </td>
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

    <!-- Employee Details Modal -->
    <div class="modal fade" id="employeeDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-people me-2"></i> Employee Wise Breakdown
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="bg-light p-3 border-bottom">
                        <span id="detailsSubtitle" class="badge bg-white text-dark border fw-normal" style="font-size: 0.9rem;"></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-premium table-striped text-center text-nowrap mb-0" id="empTable">
                            <thead>
                                <tr id="empTableHeader">
                                    <!-- Dynamically populated via JS -->
                                </tr>
                            </thead>
                            <tbody id="empTableBody">
                                <!-- Dynamically populated via JS -->
                            </tbody>
                        </table>
                    </div>
                    <div id="empTableLoader" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-warning" role="status"></div>
                        <p class="mt-2 text-muted">Loading employee details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function loadEmployees(stateId, stateName, productId, productName, month) {
        // UI Updates
        $('#employeeDetailsModal').modal('show');
        $('#empTableLoader').show();
        $('#empTable').hide();
        $('#empTableHeader, #empTableBody').empty();
        $('#detailsSubtitle').text(`Product: ${productName} | State: ${stateName}`);

        $.ajax({
            url: "{{ route('monthly.state.employees') }}",
            type: "GET",
            data: {
                state_id: stateId,
                product_id: productId,
                month: month
            },
            success: function(response) {
                let packings = response.packings;
                let employees = response.employees;

                let packingIds = Object.keys(packings);

                // Build Header
                let headerHtml = `<th>Employee Name</th>`;
                packingIds.forEach(id => {
                    headerHtml += `<th>${packings[id]}</th>`;
                });
                $('#empTableHeader').html(headerHtml);

                // Build Body
                if(employees.length > 0) {
                    let bodyHtml = '';
                    employees.forEach(emp => {
                        bodyHtml += `<tr><td class="text-start">${emp.name}</td>`;
                        packingIds.forEach(id => {
                            let qty = emp.packings[id] !== undefined ? emp.packings[id] : 0;
                            bodyHtml += `<td>${qty > 0 ? qty : '-'}</td>`;
                        });
                        bodyHtml += `</tr>`;
                    });
                    $('#empTableBody').html(bodyHtml);
                    $('#empTable').show();
                } else {
                    $('#empTableBody').html(`<tr><td colspan="${packingIds.length + 1}">No employee details found.</td></tr>`);
                    $('#empTable').show();
                }
            },
            error: function() {
                alert('Error loading employee breakdown. Please try again.');
            },
            complete: function() {
                $('#empTableLoader').hide();
            }
        });
    }
</script>
@endpush