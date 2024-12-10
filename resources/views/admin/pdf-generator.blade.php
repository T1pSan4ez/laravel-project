@extends('layouts.main')
@section('title', 'Generate PDF')
@section('content')
    <div class="container mt-4">

        <form method="GET" action="{{ route('pdf.generator') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <div class="mb-4">
            <h3>Total Earnings: {{ number_format($totalEarnings, 2) }} UAH</h3>
        </div>

        <div class="mb-4">
            <h3>User Purchases</h3>
            <form method="POST" action="{{ route('generate.pdf') }}" target="_blank">
                @csrf
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Select</th>
                        <th>Purchase Code</th>
                        <th>User ID</th>
                        <th>Total Items</th>
                        <th>Total Cost</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($userPurchases as $purchase)
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_purchases[]" value="{{ $purchase->id }}">
                            </td>
                            <td>{{ $purchase->purchase_code }}</td>
                            <td>{{ $purchase->user_id }}</td>
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

        <div class="mb-4">
            <h3>Guest Purchases</h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Select</th>
                    <th>Purchase Code</th>
                    <th>Total Items</th>
                    <th>Total Cost</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($guestPurchases as $purchase)
                    <tr>
                        <td>
                            <input type="checkbox" name="selected_purchases[]" value="{{ $purchase->id }}">
                        </td>
                        <td>{{ $purchase->purchase_code }}</td>
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
        </div>

        <button type="submit" class="btn btn-primary">Generate PDF</button>
        </form>
    </div>
@endsection
