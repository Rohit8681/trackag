@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Budget Plan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Budget Plan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('budget.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label>FY Year</label>
                                <select name="financial_year" class="form-control select2">
                                    @php
                                        $currentYear = date('Y');
                                        $years = [];
                                        for($i = -1; $i <= 2; $i++) {
                                            $y = $currentYear + $i;
                                            $years[] = $y . '-' . substr($y + 1, 2);
                                        }
                                    @endphp
                                    @foreach($years as $fy)
                                        <option value="{{ $fy }}" {{ $financial_year == $fy ? 'selected' : '' }}>{{ $fy }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Select State</label>
                                <select name="state_id" class="form-control select2">
                                    <option value="">All States</option>
                                    @foreach($states as $st)
                                        <option value="{{ $st->id }}" {{ $state_id == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Select Emp</label>
                                <select name="employee_id" class="form-control select2">
                                    <option value="">All Employees</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ $employee_id == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-warning">GO</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Listing Page Section -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h3 class="card-title">Listing Page</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th rowspan="2" class="align-middle">Emp Name</th>
                                    @foreach($months as $monthName => $monthNum)
                                        <th colspan="3">{{ ucfirst($monthName) }}</th>
                                    @endforeach
                                </tr>
                                <tr class="bg-light">
                                    @foreach($months as $monthName => $monthNum)
                                        <th>Target</th>
                                        <th>Achive</th>
                                        <th>Ach %</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($budgets as $budget)
                                    <tr>
                                        <td class="text-start">
                                            <strong>{{ $budget->user->name }}</strong><br>
                                            <small class="text-muted">Target: {{ number_format($budget->total_target, 2) }}</small>
                                        </td>
                                        @foreach($months as $monthName => $monthNum)
                                            @php
                                                $target = $budget->$monthName ?? 0;
                                                $achive = $budget->achievements[$monthName] ?? 0;
                                                $percent = $target > 0 ? ($achive / $target) * 100 : 0;
                                            @endphp
                                            <td>{{ number_format($target, 0) }}</td>
                                            <td>{{ number_format($achive, 0) }}</td>
                                            <td class="{{ $percent >= 100 ? 'text-success' : 'text-danger' }} fw-bold">
                                                {{ number_format($percent, 1) }}%
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($months) * 3 + 1 }}" class="py-4 text-muted">No budget records found for selected filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Set Target Section -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">Set Target</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('budget.store') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label>Select State</label>
                                <select name="state_id" class="form-control select2" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Select Emp</label>
                                <select name="user_id" class="form-control select2" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>FY Year</label>
                                <select name="financial_year" class="form-control select2" required>
                                    @foreach($years as $fy)
                                        <option value="{{ $fy }}">{{ $fy }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>Month</th>
                                            <th>Target Amount</th>
                                            <th>% Share</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $monthList = ['april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december', 'january', 'february', 'march'];
                                        @endphp
                                        @foreach($monthList as $m)
                                            <tr>
                                                <td class="align-middle">{{ ucfirst($m) }}</td>
                                                <td>
                                                    <input type="number" name="monthly_targets[{{ $m }}]" class="form-control target-input" data-month="{{ $m }}" step="0.01" value="0">
                                                </td>
                                                <td class="align-middle">
                                                    <span class="share-percent" id="share-{{ $m }}">0%</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-light fw-bold">
                                            <td>Total</td>
                                            <td>
                                                <input type="text" id="total-target-display" class="form-control" readonly value="0.00">
                                            </td>
                                            <td>100%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <h4 class="mb-4">Total Budget</h4>
                                    <h1 class="display-4 text-info mb-4" id="total-budget-big">0.00</h1>
                                    <button type="submit" class="btn btn-success btn-lg px-5">Save Target</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.target-input').on('input', function() {
            calculateTotal();
        });

        function calculateTotal() {
            let total = 0;
            $('.target-input').each(function() {
                let val = parseFloat($(this).val()) || 0;
                total += val;
            });

            $('#total-target-display').val(total.toLocaleString('en-IN', {minimumFractionDigits: 2}));
            $('#total-budget-big').text(total.toLocaleString('en-IN', {minimumFractionDigits: 2}));

            // Calculate percentages
            $('.target-input').each(function() {
                let val = parseFloat($(this).val()) || 0;
                let month = $(this).data('month');
                let share = total > 0 ? (val / total * 100).toFixed(1) : 0;
                $(`#share-${month}`).text(share + '%');
            });
        }
    });
</script>
@endpush