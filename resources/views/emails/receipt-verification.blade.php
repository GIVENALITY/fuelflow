@extends('emails.layout')

@section('title', 'Receipt Verification')
@section('header-title', $action === 'verified' ? 'Receipt Verified' : 'Receipt Rejected')
@section('header-subtitle', $action === 'verified' ? 'Your receipt has been approved' : 'Your receipt requires attention')

@section('content')
    <div>
        @if($action === 'verified')
            <h2>Receipt verified successfully!</h2>
            <p>Great news! Your receipt <strong>#{{ $receipt->receipt_number }}</strong> has been verified and approved by our
                treasury team.</p>
        @else
            <h2>Receipt verification failed</h2>
            <p>Unfortunately, your receipt <strong>#{{ $receipt->receipt_number }}</strong> could not be verified and has been
                rejected. Please review the details below.</p>
        @endif

        <div class="highlight-box"
            style="background-color: {{ $action === 'verified' ? '#d1fae5' : '#fef2f2' }}; border-left-color: {{ $action === 'verified' ? '#10b981' : '#ef4444' }};">
            <h3 style="color: {{ $action === 'verified' ? '#065f46' : '#991b1b' }};">
                {{ $action === 'verified' ? '✅ Receipt Status' : '❌ Verification Issue' }}
            </h3>
            <p style="color: {{ $action === 'verified' ? '#047857' : '#7f1d1d' }};">
                {{ $action === 'verified'
        ? 'Your receipt has been successfully verified and the transaction has been processed.'
        : 'There was an issue with your receipt verification. Please contact support for assistance.' 
                }}
            </p>
        </div>

        <table class="details-table">
            <tr>
                <th>Receipt Number</th>
                <td>#{{ $receipt->receipt_number }}</td>
            </tr>
            <tr>
                <th>Request ID</th>
                <td>#{{ $receipt->fuelRequest->id }}</td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td>{{ $receipt->fuelRequest->vehicle->plate_number }}</td>
            </tr>
            <tr>
                <th>Fuel Type</th>
                <td>
                    <span class="status-badge status-approved">
                        {{ ucfirst($receipt->fuel_type) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td>{{ number_format($receipt->quantity, 2) }} L</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><strong>${{ number_format($receipt->amount, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Station</th>
                <td>{{ $receipt->station->name }}</td>
            </tr>
            <tr>
                <th>Submitted At</th>
                <td>{{ $receipt->created_at->format('M d, Y \a\t g:i A') }}</td>
            </tr>
            <tr>
                <th>Verified At</th>
                <td>{{ $receipt->verified_at ? $receipt->verified_at->format('M d, Y \a\t g:i A') : 'Pending' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="status-badge {{ $action === 'verified' ? 'status-approved' : 'status-rejected' }}">
                        {{ $action === 'verified' ? 'Verified' : 'Rejected' }}
                    </span>
                </td>
            </tr>
        </table>

        @if($receipt->verification_notes)
            <div class="highlight-box">
                <h3>📝 Verification Notes</h3>
                <p>{{ $receipt->verification_notes }}</p>
            </div>
        @endif

        @if($action === 'verified')
            <div class="highlight-box">
                <h3>✅ Next Steps</h3>
                <p>Your receipt has been verified and the transaction is now complete. The amount has been processed and added
                    to your account balance.</p>
            </div>
        @else
            <div class="highlight-box">
                <h3>🔄 Required Actions</h3>
                <p>To resolve this issue, please:</p>
                <ul style="margin: 8px 0; padding-left: 20px; color: #4b5563;">
                    <li>Review the receipt image for clarity and completeness</li>
                    <li>Ensure all required information is visible</li>
                    <li>Contact support if you believe this is an error</li>
                    <li>Submit a new receipt if necessary</li>
                </ul>
            </div>
        @endif

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ config('app.url') }}/receipts/{{ $receipt->id }}" class="button">
                View Receipt Details
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size: 14px; color: #6b7280;">
            <strong>Need help?</strong> If you have questions about this receipt verification, please contact our treasury
            team or support staff.
        </p>
    </div>
@endsection