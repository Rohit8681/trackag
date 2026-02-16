@extends('admin.layout.layout')
@section('title', 'State Wise Expense Report')

@section('content')
    <main class="app-main">
        <div class="app-content">
            <div class="container-fluid">

                <div class="card card-primary card-outline">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">STATE WISE EXPENSE REPORT</h5>

                        <form method="GET">
                            <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm"
                                onchange="this.form.submit()">
                        </form>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered text-center align-middle">

                            <thead>
                                <tr>
                                    <th rowspan="2" style="background:#9bb7e0;">MONTH NAME</th>
                                    @foreach($states as $state)
                                        <th colspan="4" style="background:#f4b183;">
                                            {{ strtoupper($state->name) }}
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach($states as $state)
                                        <th style="background:#d5f5e3;">TA EXP</th>
                                        <th style="background:#d5f5e3;">DA EXP</th>
                                        <th style="background:#d5f5e3;">OTHER</th>
                                        <th style="background:#abebc6;">TOTAL</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $monthName = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
                                @endphp

                                <tr>
                                    <td><strong>{{ $monthName }}</strong></td>

                                    @foreach($states as $state)
                                        <td>{{ number_format($report[$state->id]['ta'], 2) }}</td>
                                        <td>{{ number_format($report[$state->id]['da'], 2) }}</td>
                                        <td>{{ number_format($report[$state->id]['other'], 2) }}</td>
                                        <td><strong>{{ number_format($report[$state->id]['total'], 2) }}</strong></td>
                                    @endforeach
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection