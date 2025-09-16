@extends('emails.layout')

@section('title', 'Request Completed')
@section('header-title', 'Request Completed')
@section('header-subtitle', 'Your fuel request has been fully processed')

@section('content')
    <div>
        <h2>Request completed successfully!</h2>

        <p>Your fuel request for <strong>{{ $vehicle->plate_number }}</strong> has been fully processed and completed. All
            documentation and payments have been finalized.</p>

        <div class="highlight-box">
            <h3>✅ Completion Summary</h3>
            <p>Here's a summary of your completed fuel request:</p>
        </div>

        <table class="details-table">
            <tr>
                <th>Request ID</th>
                <td>#{{ $fuelRequest->id }}</td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td>{{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})</td>
            </tr>
            <tr>
                <th>Fuel Type</th>
                <td>
                    <span class="status-badge status-approved">
                        {{ ucfirst($fuelRequest->fuel_type) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Quantity Dispensed</th>
                <td><strong>{{ number_format($fuelRequest->quantity_dispensed, 2) }} L</strong></td>
            </tr>
            <tr>
                <th>Station</th>
                <td>{{ $station->name }}</td>
            </tr>
            <tr>
                <th>Completed At</th>
                <td>{{ $fuelRequest->updated_at->format('M d, Y \a\t g:i A') }}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td><strong>${{ number_format($fuelRequest->total_amount, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="status-badge status-completed">
                        Completed
                    </span>
                </td>
            </tr>
        </table>

        <div class="highlight-box">
            <h3>📊 Account Summary</h3>
            <p>This transaction has been added to your account. You can view your complete transaction history and account
                balance in your dashboard.</p>
        </div>

        <div class="highlight-box">
            <h3>💳 Payment Information</h3>
            <p>The amount of <strong>${{ number_format($fuelRequest->total_amount, 2) }}</strong> has been charged to your
                account. Your new balance will be reflected in your next statement.</p>
        </div>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ config('app.url') }}/fuel-requests" class="button">
                View All Requests
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size: 14px; color: #6b7280;">
            <strong>Thank you for choosing FuelFlow!</strong> We appreciate your business and look forward to serving you
            again.
        </p>
    </div>
@endsection