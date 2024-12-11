<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 20px;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .filters {
            margin-bottom: 30px;
            font-size: 14px;
        }

        .footer {
            text-align: start;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>{{ $title }}</h1>
</div>

<div class="filters">
    <p><strong>Filters Applied:</strong></p>
    @if (!empty($startDate) && !empty($endDate))
        <p>Date Range: {{ $startDate }} to {{ $endDate }}</p>
    @endif
    @if (!empty($isUser))
        <p>User Type: {{ $isUser === 'user' ? 'Registered Users' : 'Guests' }}</p>
    @endif
</div>

<table>
    <thead>
    <tr>
        <th>№</th>
        <th>Purchase Code</th>
        <th>User ID</th>
        <th>Total Items</th>
        <th>Total Cost</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($purchases as $index => $purchase)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $purchase->purchase_code }}</td>
            <td>{{ $purchase->user_id ?? 'Guest' }}</td>
            <td>{{ $purchase->items->count() }}</td>
            <td>
                {{ number_format($purchase->items->sum(function ($item) {
                    return $item->quantity * $item->price;
                }), 2) }} UAH
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    <h3>Total Earnings: {{ isset($totalEarnings) ? number_format($totalEarnings, 2) : 'N/A' }} UAH</h3>
    <p>Date: {{ $date }}</p>
</div>
</body>
</html>
