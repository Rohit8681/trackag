<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Party List</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background: #f2f2f2; text-align: center; }
    </style>
</head>
<body>

<h3 align="center">New Party List</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Sales Person</th>
            <th>Shop Name</th>
            <th>Mobile</th>
            <th>Address</th>
            <th>Contact Person</th>
            <th>Working With</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customer as $i => $item)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $item->visit_date?->format('d-m-Y') }}</td>
            <td>{{ $item->user?->name }}</td>
            <td>{{ $item->agro_name }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ $item->address }}</td>
            <td>{{ $item->contact_person_name }}</td>
            <td>{{ $item->working_with }}</td>
            <td>{{ ucfirst($item->status ?? 'pending') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
