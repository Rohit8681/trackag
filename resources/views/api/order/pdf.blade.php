<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Details</title>
    <style>
        @page {
            margin: 24px;
        }

        body {
            margin: 0;
            background: #f4fbfc;
            color: #111111;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 14px;
        }

        .page {
            padding: 18px;
        }

        .logo-wrap {
            text-align: center;
            padding: 10px 0 24px;
        }

        .logo {
            width: 150px;
            height: auto;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 22px 24px;
            margin-bottom: 18px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .label {
            color: #777777;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .date-main {
            color: #0f6074;
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e5e5e5;
        }

        .transport {
            color: #0f6074;
            font-size: 21px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            width: 50%;
            padding: 6px 0;
            vertical-align: top;
        }

        .value {
            color: #111111;
            font-size: 16px;
        }

        .product-title {
            font-size: 21px;
            font-weight: 700;
            padding-bottom: 16px;
            border-bottom: 1px solid #e7e7e7;
            margin-bottom: 16px;
        }

        .row-table {
            width: 100%;
            border-collapse: collapse;
        }

        .row-table td {
            padding: 6px 0;
            font-size: 16px;
        }

        .row-table td:first-child {
            color: #777777;
        }

        .row-table td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .total-row td {
            border-top: 1px solid #e7e7e7;
            padding-top: 16px;
            font-size: 18px;
        }

        .total-row td:last-child {
            font-weight: 800;
        }

        .grand-total {
            text-align: right;
            font-size: 20px;
            font-weight: 800;
            color: #0f6074;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('img/TRACKAGLOGO.jpeg');
        $orderDate = optional($order->created_at)->format('jS F Y') ?? '-';
        $dispatchDate = !empty($dispatchInfo['dispatch_date'])
            ? \Carbon\Carbon::parse($dispatchInfo['dispatch_date'])->format('jS F Y')
            : '-';
    @endphp

    <div class="page">
        <div class="logo-wrap">
            @if(file_exists($logoPath))
                <img class="logo" src="{{ $logoPath }}" alt="Track-AG">
            @else
                <h1>TRACK-AG</h1>
            @endif
        </div>

        <div class="card">
            <div class="label">Order Date</div>
            <div class="date-main">{{ $orderDate }}</div>

            <div class="label">Transport Name</div>
            <div class="transport">{{ $dispatchInfo['transport_name'] ?: '-' }}</div>

            <table class="info-table">
                <tr>
                    <td>
                        <div class="label">Dispatch Date</div>
                        <div class="value">{{ $dispatchDate }}</div>
                    </td>
                    <td>
                        <div class="label">LR Number</div>
                        <div class="value">{{ $dispatchInfo['lr_number'] ?: '-' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Vehicle Number</div>
                        <div class="value">{{ $dispatchInfo['vehicle_no'] ?: '-' }}</div>
                    </td>
                    <td>
                        <div class="label">Order Number</div>
                        <div class="value">{{ $order->order_no ?: '-' }}</div>
                    </td>
                </tr>
            </table>
        </div>

        @foreach($items as $item)
            <div class="card">
                <div class="product-title">{{ $item['product_name'] }}</div>
                <table class="row-table">
                    <tr>
                        <td>Packing</td>
                        <td>{{ $item['packing'] }}</td>
                    </tr>
                    <tr>
                        <td>Price (Per Unit)</td>
                        <td>&#8377;{{ number_format($item['price'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Dispatch QTY</td>
                        <td>{{ $item['dispatch_qty'] }}</td>
                    </tr>
                    <tr>
                        <td>GST%</td>
                        <td>{{ $item['gst_percent'] }}%</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total Price</td>
                        <td>&#8377;{{ number_format($item['total_price'], 2) }}</td>
                    </tr>
                </table>
            </div>
        @endforeach

        <div class="card grand-total">
            Grand Total: &#8377;{{ number_format($grandTotal, 2) }}
        </div>
    </div>
</body>
</html>
