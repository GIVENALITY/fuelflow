<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use App\Models\User;
use App\Notifications\PaymentSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EnhancedPaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isClient()) {
            $payments = $user->client->payments()
                ->with(['receipt', 'submittedBy', 'verifiedBy'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } elseif ($user->isTreasury() || $user->isAdmin() || $user->isSuperAdmin()) {
            $payments = Payment::with(['client', 'receipt', 'submittedBy', 'verifiedBy'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $payments = collect()->paginate(20);
        }

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        if (!Auth::user()->isClient()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $client = Auth::user()->client;
        $receipts = $client->receipts()
            ->where('status', 'verified')
            ->whereDoesntHave('payments', function($query) {
                $query->where('status', '!=', 'rejected');
            })
            ->get();

        return view('payments.create', compact('receipts'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isClient()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'receipt_id' => 'required|exists:receipts,id',
            'amount' => 'required|numeric|min:0.01',
            'bank_name' => 'required|string|max:255',
            'payment_date' => 'required|date|before_or_equal:today',
            'proof_of_payment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);

        $client = Auth::user()->client;
        
        // Verify receipt belongs to client
        $receipt = $client->receipts()->findOrFail($request->receipt_id);

        try {
            // Upload proof of payment
            $filename = 'payment_proof_' . Str::random(10) . '.' . $request->file('proof_of_payment')->getClientOriginalExtension();
            $proofPath = $request->file('proof_of_payment')->storeAs('payments/proofs', $filename, 'public');

            // Create payment record
            $payment = Payment::create([
                'client_id' => $client->id,
                'receipt_id' => $request->receipt_id,
                'amount' => $request->amount,
                'payment_method' => 'bank_transfer',
                'bank_name' => $request->bank_name,
                'payment_date' => $request->payment_date,
                'proof_of_payment_path' => $proofPath,
                'submitted_by' => Auth::id(),
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Notify treasury users
            $treasuryUsers = User::where('role', User::ROLE_TREASURY)->get();
            foreach ($treasuryUsers as $treasuryUser) {
                $treasuryUser->notify(new PaymentSubmittedNotification($payment));
            }

            return redirect()->route('payments.index')
                ->with('success', 'Payment submitted successfully! It will be reviewed by the treasury team.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit payment. Please try again.');
        }
    }

    public function show(Payment $payment)
    {
        $user = Auth::user();
        
        // Check access permissions
        if ($user->isClient() && $payment->client_id !== $user->client->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        
        if (!$user->isClient() && !$user->isTreasury() && !$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $payment->load(['client', 'receipt', 'submittedBy', 'verifiedBy']);
        return view('payments.show', compact('payment'));
    }

    public function verify(Request $request, Payment $payment)
    {
        if (!Auth::user()->isTreasury() && !Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Update payment status to verified
        $payment->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update client balance - deduct the payment amount
        if ($payment->client) {
            $payment->client->decrement('current_balance', $payment->amount);
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment verified and approved successfully! Client balance has been updated.');
    }

    public function downloadProof(Payment $payment)
    {
        $user = Auth::user();
        
        // Check access permissions
        if ($user->isClient() && $payment->client_id !== $user->client->id) {
            abort(403);
        }
        
        if (!$user->isClient() && !$user->isTreasury() && !$user->isAdmin() && !$user->isSuperAdmin()) {
            abort(403);
        }

        if (!$payment->proof_of_payment_path || !Storage::disk('public')->exists($payment->proof_of_payment_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($payment->proof_of_payment_path);
    }

    public function pendingPayments()
    {
        if (!Auth::user()->isTreasury() && !Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $payments = Payment::where('status', 'pending')
            ->with(['client', 'receipt', 'submittedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payments.pending', compact('payments'));
    }
}
