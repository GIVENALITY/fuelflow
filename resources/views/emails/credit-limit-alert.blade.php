@extends('emails.layout')

@section('title', 'Credit Limit Alert')
@section('header-title', 'Credit Limit Alert')
@section('header-subtitle', 'Your credit utilization is approaching the limit')

@section('content')
    <div>
        <h2>Credit limit alert for your account</h2>

        <p>Your account has reached <strong>{{ $percentage }}%</strong> of its credit limit. Please review your account
            status and consider making a payment to avoid service interruption.</p>

        <div class="highlight-box" style="background-color: #fef3c7; border-left-color: #f59e0b;">
            <h3 style="color: #92400e;">⚠️ Account Status</h3>
            <p style="color: #78350f;">Your credit utilization is at {{ $percentage }}% of your limit. Please take action to
                avoid service interruption.</p>
        </div>

        <table class="details-table">
            <tr>
                <th>Current Balance</th>
                <td><strong>${{ number_format($currentBalance, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Credit Limit</th>
                <td>${{ number_format($creditLimit, 2) }}</td>
            </tr>
            <tr>
                <th>Available Credit</th>
                <td><strong>${{ number_format($availableCredit, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Utilization</th>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="flex: 1; background-color: #e5e7eb; border-radius: 4px; height: 8px;">
                            <div
                                style="background-color: #f59e0b; height: 100%; width: {{ $percentage }}%; border-radius: 4px;">
                            </div>
                        </div>
                        <span style="font-weight: 600; color: #92400e;">{{ $percentage }}%</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="highlight-box">
            <h3>💳 Payment Options</h3>
            <p>To maintain uninterrupted service, please consider making a payment:</p>
            <ul style="margin: 8px 0; padding-left: 20px; color: #4b5563;">
                <li>Make an online payment through your account dashboard</li>
                <li>Contact your account manager to discuss payment terms</li>
                <li>Set up automatic payments to avoid future alerts</li>
            </ul>
        </div>

        <div class="highlight-box">
            <h3>📊 Account Management</h3>
            <p>You can monitor your account status and make payments at any time through your account dashboard. We
                recommend setting up payment alerts to stay informed about your account status.</p>
        </div>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ config('app.url') }}/payments" class="button">
                Make Payment
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size: 14px; color: #6b7280;">
            <strong>Need assistance?</strong> If you have questions about your account or need to discuss payment options,
            please contact your account manager or our support team.
        </p>
    </div>
@endsection