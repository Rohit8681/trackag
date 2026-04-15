@extends('admin.layout.layout')

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
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('monthly.index') }}" method="GET">
                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label>Select Month</label>
                                            <select name="month" class="form-control select2">
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ current($month) == $i || $month == $i ? 'selected' : '' }}>
                                                        {{ date("F", mktime(0, 0, 0, $i, 10)) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Employee Name</label>
                                            <select name="employee_id" class="form-control select2">
                                                <option value="">All Employees</option>
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>State</label>
                                            <select name="state_id" class="form-control select2">
                                                <option value="">All States</option>
                                                @foreach($states as $st)
                                                    <option value="{{ $st->id }}" {{ request('state_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Product</label>
                                            <select name="product_id" class="form-control select2">
                                                <option value="">All Products</option>
                                                @foreach($products as $prod)
                                                    <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-warning btn-block mt-4">GO</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- State-Wise Summary Table -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title text-white">Product & State Wise Plan</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center text-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            @foreach($uniqueStates as $stId => $stName)
                                                <th>{{ $stName }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reportData as $productId => $product)
                                            <tr style="background-color: #ffc107; font-weight: bold;">
                                                <td class="text-start">{{ $product['name'] }}</td>
                                                @foreach($uniqueStates as $stId => $stName)
                                                    <td></td> <!-- Empty for product header -->
                                                @endforeach
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
                                                                   class="text-decoration-underline fw-bold">
                                                                    {{ $qty }}
                                                                </a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($uniqueStates) + 1 }}" class="text-center py-4">No data found for the selected filters.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Wise Details Table -->
                <div class="col-lg-6">
                    <div class="card" id="employeeDetailsCard" style="display: none;">
                        <div class="card-header bg-warning">
                            <h3 class="card-title text-white">
                                Employee Wise Breakdown <br>
                                <small id="detailsSubtitle" class="fw-normal"></small>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center text-nowrap mb-0" id="empTable">
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
                        </div>
                        <div class="overlay" id="empTableLoader" style="display: none;">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                    </div>
                    <!-- Placeholder when no state is clicked -->
                    <div class="card" id="emptyDetailsCard">
                        <div class="card-body text-center py-5 text-muted">
                            <i class="fas fa-hand-pointer fa-3x mb-3"></i>
                            <h5>Click on a quantity in the left table to view employee details</h5>
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
    function loadEmployees(stateId, stateName, productId, productName, month) {
        // UI Updates
        $('#emptyDetailsCard').hide();
        $('#employeeDetailsCard').show();
        $('#empTableLoader').show();
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
                } else {
                    $('#empTableBody').html(`<tr><td colspan="${packingIds.length + 1}">No employee details found.</td></tr>`);
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