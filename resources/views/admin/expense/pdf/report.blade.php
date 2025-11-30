<!DOCTYPE html>
<html>
<head>
    <title>Expense Report PDF</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:6px; font-size:12px; }
        th { background:#f2f2f2; }
    </style>
</head>
<body>

<h3>Approved Expense Report</h3>

<table>
    <thead>
        <tr>
            <th>Sr</th>
            <th>Date</th>
            <th>User</th>
            <th>Tour Type</th>
            <th>Travel Mode</th>
            <th>KM</th>
            <th>Total Expense</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trips as $k => $t)
        <tr>
            <td>{{ $k+1 }}</td>
            <td>{{ $t->trip_date }}</td>
            <td>{{ $t->user->name }}</td>
            <td>{{ $t->tourType->name ?? '-' }}</td>
            <td>{{ $t->travelMode->name ?? '-' }}</td>
            <td>{{ $t->end_km - $t->starting_km }}</td>
            <td>{{ number_format($t->total_exp,2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
