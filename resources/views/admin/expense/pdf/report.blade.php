<!DOCTYPE html>
<html>
<head>
    <title>Expense Report PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size:12px; }
        table { width: 100%; border-collapse: collapse; margin-top:10px; }
        th, td { border:1px solid #000; padding:5px; text-align:left; font-size:11px; }
        th { background:#f2f2f2; }
        .header-table td { border:none; padding:3px 5px; }
        .header-table tr td:first-child { width:150px; font-weight:bold; }
        h3 { margin-bottom:5px; }
    </style>
</head>
<body>

<h3>Approved Expense Report</h3>

<!-- Header Info -->
<table class="header-table">
    <tr>
        <td>Company Name:</td>
        <td>{{ $headerInfo['company_name'] ?? '-' }}</td>
        <td>Employee Name:</td>
        <td>{{ $headerInfo['employee_name'] ?? '-' }}</td>
    </tr>
    <tr>
        <td>Designation:</td>
        <td>{{ $headerInfo['designation'] ?? '-' }}</td>
        <td>Reporting To:</td>
        <td>{{ $headerInfo['reporting_to'] ?? '-' }}</td>
    </tr>
    <tr>
        <td>HQ:</td>
        <td>{{ $headerInfo['hq'] ?? '-' }}</td>
        <td>Duration:</td>
        <td>{{ $headerInfo['from_date'] ?? '-' }} - {{ $headerInfo['to_date'] ?? '-' }}</td>
    </tr>
</table>

<!-- Trips Table -->
<table>
    <thead>
        <tr>
            <th>Sr</th>
            <th>Name</th>
            <th>Date</th>
            <th>Tour Type</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Visit Places</th>
            <th>Travel Mode</th>
            <th>Start KM</th>
            <th>End KM</th>
            <th>Travel KM</th>
            <th>GPS KM</th>
            <th>KM diff</th>
            <th>TA EXP</th>
            <th>DA EXP</th>
            <th>Other EXP</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trips as $k => $t)
        <tr>
            <td>{{ $k+1 }}</td>
            <td>{{ $t->user->name ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($t->trip_date)->format('d M Y') }}</td>
            <td>{{ $t->tourType->name ?? '-' }}</td>
            <td>{{ $t->start_time ?? '-' }}</td>
            <td>{{ $t->end_time ?? '-' }}</td>
            <td>{{ $t->place_to_visit ?? '-' }}</td>
            <td>{{ $t->travelMode->name ?? '-' }}</td>
            <td>{{ $t->starting_km ?? 0 }}</td>
            <td>{{ $t->end_km ?? 0 }}</td>
            <td>{{ ($t->end_km ?? 0) - ($t->starting_km ?? 0) }}</td>
            <td>{{ $t->total_distance_km ?? 0 }}</td>
            <td>{{ ($t->end_km ?? 0) - ($t->starting_km ?? 0) }}</td>
            <td>{{ number_format($t->ta_exp ?? 0, 2) }}</td>
            <td>{{ number_format($t->da_exp ?? 0, 2) }}</td>
            <td>{{ number_format($t->other_exp ?? 0, 2) }}</td>
            <td>{{ number_format($t->total_exp ?? 0, 2) }}</td>
        </tr>
        @endforeach
        @if($trips->isEmpty())
        <tr>
            <td colspan="17" style="text-align:center;">No trips found.</td>
        </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="13" style="text-align:right;">TOTAL :</th>
            <th>{{ number_format($total_ta ?? 0, 2) }}</th>
            <th>{{ number_format($total_da ?? 0, 2) }}</th>
            <th>{{ number_format($total_other ?? 0, 2) }}</th>
            <th>{{ number_format($total_total ?? 0, 2) }}</th>
        </tr>
    </tfoot>
</table>

</body>
</html>
