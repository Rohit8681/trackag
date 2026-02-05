<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Farmers List</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h3 align="center">Farmers List</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Farmer</th>
            <th>Sales Person</th>
            <th>Mobile</th>
            <th>State</th>
            <th>District</th>
            <th>Taluka</th>
            <th>City</th>
            <th>Land</th>
            <th>Irrigation</th>
            <th>Crops</th>
        </tr>
    </thead>
    <tbody>
        @foreach($farmers as $i => $farmer)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $farmer->created_at?->format('d-m-Y') }}</td>
            <td>{{ $farmer->farmer_name }}</td>
            <td>{{ $farmer->user->name ?? '-' }}</td>
            <td>{{ $farmer->mobile_no }}</td>
            <td>{{ $farmer->state->name ?? '-' }}</td>
            <td>{{ $farmer->district->name ?? '-' }}</td>
            <td>{{ $farmer->taluka->name ?? '-' }}</td>
            <td>{{ $farmer->village }}</td>

            <td>{{ $farmer->land_acr }}</td>
            <td>{{ $farmer->irrigation_type }}</td>
            <td>
                {{ $farmer->cropSowings->pluck('crop.name')->implode(', ') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
