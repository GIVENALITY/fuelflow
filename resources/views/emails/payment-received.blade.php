@extends('emails.layout')

@section('title', 'Payment Received')
@section('header-title', 'Payment Received')
@section('header-subtitle', 'Your payment has been successfully processed')

@section('content')
    <div>
        <h2>Payment received successfully!</h2>

        <p>Thank you! Your payment of <strong>TZS {{ number_format($payment->amount, 2) }}</strong> has been received and
            processed successfully.</p>

        <div class="highlight-box">
            <h3>💰 Payment Confirmation</h3>
            <p>Here are the details of your processed payment:</p>
        </div>

        <table class="details-table">
            <tr>
                <th>Payment ID</th>
                <td>#{{ $payment->id }}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><strong>${{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td>
                    <span class="status-badge status-approved">
                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Reference Number</th>
                <td>{{ $payment->reference_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <th>Processed At</th>
                <td>{{ $payment->created_at->format('M d, Y \a\t g:i A') }}</td>
            </tr>
            @if($receipt)
                <tr>
                    <th>Related Receipt</th>
                    <td>#{{ $receipt->receipt_number }}</td>
                </tr>
            @endif
            <tr>
                <th>Status</th>
                <td>
                    <span class="status-badge status-approved">
                        Completed
                    </span>
                </td>
            </tr>
        </table>

        @if($payment->notes)
            <div class="highlight-box">
                <h3>📝 Payment Notes</h3>
                <p>{{ $payment->notes }}</p>
            </div>
        @endif

        <div class="highlight-box">
            <h3>📊 Account Impact</h3>
            <p>This payment has been applied to your account and will be reflected in your next statement. Your available
                credit has been updated accordingly.</p>
        </div>

        <div class="highlight-box">
            <h3>📄 Receipt & Records</h3>
            <p>Please keep this email as confirmation of your payment. You can also download a receipt from your account
                dashboard for your records.</p>
        </div>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ config('app.url') }}/payments" class="button">
                View Payment History
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size: 14px; color: #6b7280;">
            <strong>Thank you for your payment!</strong> If you have any questions about this transaction, please contact
            our support team.
        </p>
    </div>
@endsection