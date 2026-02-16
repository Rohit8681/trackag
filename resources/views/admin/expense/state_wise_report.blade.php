@extends('admin.layout.layout')
@section('title', 'State Wise Expense Report')

@section('content')
<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">

            <div class="card card-primary card-outline">

                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">STATE WISE EXPENSE REPORT (YEAR WISE)</h5>

                    {{-- YEAR FILTER --}}
                    <form method="GET" class="d-flex align-items-center gap-2">
                        <label class="mb-0 fw-bold">Year:</label>
                        <input type="number"
                               name="year"
                               value="{{ $year }}"
                               min="2020"
                               max="{{ now()->year }}"
                               class="form-control form-control-sm"
                               style="width:100px"
                               onchange="this.form.submit()">
                    </form>
                </div>

                {{-- BODY --}}
                <div class="card-body table-responsive">
                    <table class="table table-bordered text-center align-middle">

                        {{-- TABLE HEADER --}}
                        <thead>
                            <tr>
                                <th rowspan="2" style="background:#9bb7e0;">MONTH</th>
                                @foreach($states as $state)
                                    <th colspan="4" style="background:#f4b183;">
                                        {{ strtoupper($state->name) }}
                                    </th>
                                @endforeach
                            </tr>

                            <tr>
                                @foreach($states as $state)
                                    <th style="background:#d5f5e3;">TA</th>
                                    <th style="background:#d5f5e3;">DA</th>
                                    <th style="background:#d5f5e3;">OTHER</th>
                                    <th style="background:#abebc6;">TOTAL</th>
                                @endforeach
                            </tr>
                        </thead>

                        {{-- TABLE BODY --}}
                        <tbody>
                            @forelse($finalReport as $row)
                                <tr>
                                    <td><strong>{{ $row['month'] }}</strong></td>

                                    @foreach($states as $state)
                                        <td>{{ number_format($row['states'][$state->id]['ta'], 2) }}</td>
                                        <td>{{ number_format($row['states'][$state->id]['da'], 2) }}</td>
                                        <td>{{ number_format($row['states'][$state->id]['other'], 2) }}</td>
                                        <td>
                                            <strong>
                                                {{ number_format($row['states'][$state->id]['total'], 2) }}
                                            </strong>
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ (count($states) * 4) + 1 }}">
                                        No data found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
