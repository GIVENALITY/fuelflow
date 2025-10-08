<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TreasuryController extends Controller
{
    public function dashboard()
    {
        // Get pending payments
        $pendingPayments = Payment::where('status', 'pending_verification')
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get statistics
        $totalPendingAmount = Payment::where('status', 'pending_verification')->sum('amount');
        $verifiedToday = Payment::where('status', 'completed')
            ->whereDate('verified_at', today())
            ->count();
        $pendingCount = Payment::where('status', 'pending_verification')->count();

        return view('treasury.dashboard', compact(
            'pendingPayments',
            'totalPendingAmount',
            'verifiedToday',
            'pendingCount'
        ));
    }

    public function pendingPayments()
    {
        $payments = Payment::where('status', 'pending_verification')
            ->with(['client', 'submittedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('treasury.payments.pending', compact('payments'));
    }

    public function showPayment(Payment $payment)
    {
        $payment->load(['client', 'submittedBy']);

        return view('treasury.payments.show', compact('payment'));
    }

    public function approvePayment(Payment $payment)
    {
        $payment->update([
            'status' => 'completed',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Update client balance
        $client = $payment->client;
        if ($client) {
            $client->current_balance = max(0, $client->current_balance - $payment->amount);
            $client->save();
        }

        // Notify client
        if ($client && $client->user) {
            Notification::create([
                'user_id' => $client->user->id,
                'title' => 'Payment Approved',
                'message' => "Your payment of TZS " . number_format($payment->amount, 0) . " has been verified and applied to your account.",
                'type' => 'payment',
                'data' => json_encode(['payment_id' => $payment->id]),
                'read' => false,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Payment approved successfully. Client balance updated.');
    }

    public function rejectPayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Notify client
        $client = $payment->client;
        if ($client && $client->user) {
            Notification::create([
                'user_id' => $client->user->id,
                'title' => 'Payment Rejected',
                'message' => "Your payment of TZS " . number_format($payment->amount, 0) . " was rejected. Reason: " . $validated['rejection_reason'],
                'type' => 'payment',
                'data' => json_encode(['payment_id' => $payment->id]),
                'read' => false,
            ]);
        }

        return redirect()->back()
            ->with('warning', 'Payment rejected. Client has been notified.');
    }

    public function allPayments()
    {
        $payments = Payment::with(['client', 'verifiedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('treasury.payments.index', compact('payments'));
    }
}

