@extends('admin.layout.layout')
@push('styles')
<style>
    .table-container {
        overflow-x: auto;
    }
    .sticky-table td {
        position: sticky !important;
        z-index: 5;
        background-color: inherit;
    }
    .sticky-col-1 { left: 0; min-width: 200px; width: 200px; }
    .sticky-col-2 { left: 200px; min-width: 100px; width: 100px; }
    .sticky-col-3 { left: 300px; min-width: 100px; width: 100px; }
    .sticky-col-4 { left: 400px; min-width: 100px; width: 100px; }

    /* Fix background colors for sticky cells */
    .sticky-table tbody tr {
        background-color: white;
    }
    .sticky-table tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    .sticky-table tr.product-header {
        background-color: #fce4d6 !important;
        z-index: 6;
    }
    .sticky-table tr.product-header td {
        background-color: #fce4d6 !important;
    }
</style>
@endpush

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Stock</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Stock
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
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('stock.index') }}" method="GET" class="mb-4">
                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label>Select Month</label>
                                            <input type="month" name="month" class="form-control" value="{{ $month }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>State</label>
                                            <select name="state_id" class="form-control select2">
                                                <option value="">All States</option>
                                                @foreach($states as $state)
                                                    <option value="{{ $state->id }}" {{ $state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Employee name</label>
                                            <select name="employee_id" class="form-control select2">
                                                <option value="">All Employees</option>
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}" {{ $user_id == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Party Name</label>
                                            <select name="party_id" class="form-control select2">
                                                <option value="">All Parties</option>
                                                @foreach($parties as $party)
                                                    <option value="{{ $party->id }}" {{ $party_id == $party->id ? 'selected' : '' }}>{{ $party->agro_name ?? $party->agro_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-success btn-block mt-4">GO</button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive table-container">
                                <table class="table table-bordered table-striped text-center text-nowrap sticky-table">
                                    <tbody>
                                        @foreach($reportData as $product)
                                            <tr class="product-header" style="background-color: #fce4d6; font-weight: bold;">
                                                <td class="text-start sticky-col-1">{{ $product['name'] }}</td>
                                                <td class="sticky-col-2">Opening</td>
                                                <td class="sticky-col-3">Closing</td>
                                                <td class="sticky-col-4">Total</td>
                                                @for($i = 1; $i <= $daysInMonth; $i++)
                                                    <td>{{ sprintf('%02d', $i) }}-{{ $startOfMonth->format('M') }}</td>
                                                @endfor
                                            </tr>
                                            @foreach($product['packings'] as $packing)
                                                <tr>
                                                    <td class="text-start sticky-col-1">{{ $packing['name'] }}</td>
                                                    <td class="sticky-col-2">{{ $packing['opening'] }}</td>
                                                    <td class="sticky-col-3">{{ $packing['closing'] }}</td>
                                                    <td class="sticky-col-4">{{ $packing['total'] }}</td>
                                                    @for($i = 1; $i <= $daysInMonth; $i++)
                                                        @php $day = sprintf('%02d', $i); @endphp
                                                        <td>{{ $packing['daily'][$day] ?? '' }}</td>
                                                    @endfor
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection