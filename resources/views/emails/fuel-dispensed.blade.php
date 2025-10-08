@extends('emails.layout')

@section('title', 'Fuel Dispensed')
@section('header-title', 'Fuel Dispensed')
@section('header-subtitle', 'Your fuel has been successfully dispensed')

@section('content')
    <div>
        <h2>Fuel has been successfully dispensed!</h2>

        <p>Great news! Your fuel request for <strong>{{ $vehicle->plate_number }}</strong> has been completed. The fuel has
            been dispensed and is ready for use.</p>

        <div class="highlight-box">
            <h3>⛽ Dispensing Summary</h3>
            <p>Here are the details of your completed fuel request:</p>
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
                <th>Quantity Requested</th>
                <td>{{ number_format($fuelRequest->quantity_requested, 2) }} L</td>
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
                <th>Dispensed By</th>
                <td>{{ $fuelRequest->dispensedBy->name ?? 'Station Staff' }}</td>
            </tr>
            <tr>
                <th>Dispensed At</th>
                <td>{{ $fuelRequest->dispensed_at ? $fuelRequest->dispensed_at->format('M d, Y \a\t g:i A') : 'Not specified' }}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td><strong>${{ number_format($fuelRequest->total_amount, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="status-badge status-completed">
                        Dispensed
                    </span>
                </td>
            </tr>
        </table>

        @if($fuelRequest->notes)
            <div class="highlight-box">
                <h3>📝 Dispensing Notes</h3>
                <p>{{ $fuelRequest->notes }}</p>
            </div>
        @endif

        <div class="highlight-box">
            <h3>📄 Receipt Information</h3>
            <p>Please keep your receipt for your records. If you need a digital copy, you can download it from your account
                dashboard.</p>
        </div>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ config('app.url') }}/fuel-requests/{{ $fuelRequest->id }}" class="button">
                View Request Details
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size: 14px; color: #6b7280;">
            <strong>Thank you for using FuelFlow!</strong> If you have any questions about this transaction, please contact
            our support team.
        </p>
    </div>
@endsection