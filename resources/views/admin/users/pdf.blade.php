<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User List</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th {
            background-color: #f2a365;
        }
    </style>
</head>
<body>

<h3 style="text-align:center;">Employee User List</h3>

<table>
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Employee Name</th>
            <th>Mobile No</th>
            <th>Company Mobile</th>
            <th>State</th>
            <th>District</th>
            <th>Taluka</th>
            <th>User Code</th>
            <th>Designation</th>
            <th>Reporting To</th>
            <th>Headquarter</th>
            <th>DOJ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $index => $user)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->mobile ?? '-' }}</td>
            <td>{{ $user->company_mobile ?? '-' }}</td>
            <td>{{ $user->state->name ?? '-' }}</td>
            <td>{{ $user->district->name ?? '-' }}</td>
            <td>{{ $user->tehsil->name ?? '-' }}</td>
            <td>{{ $user->user_code ?? '-' }}</td>
            <td>{{ $user->designation->name ?? '-' }}</td>
            <td>{{ $user->reportingManager->name ?? '-' }}</td>
            <td>{{ $user->headquarter ?? '-' }}</td>
            <td>
                {{ $user->joining_date 
                    ? \Carbon\Carbon::parse($user->joining_date)->format('d-m-Y') 
                    : '-' 
                }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
