<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientPaymentController extends Controller
{
    public function create()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        $currentBalance = $client->current_balance ?? 0;

        return view('client.payments.create', compact('currentBalance'));
    }

    public function store(Request $request)
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'proof_of_payment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Upload proof of payment
        $popPath = null;
        if ($request->hasFile('proof_of_payment')) {
            $popPath = $request->file('proof_of_payment')->store('payments/proofs', 'public');
        }

        // Create payment record
        $payment = Payment::create([
            'client_id' => $client->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => 'bank_transfer',
            'bank_name' => $validated['bank_name'],
            'reference_number' => $validated['reference_number'],
            'proof_of_payment' => $popPath,
            'status' => 'pending_verification',
            'notes' => $validated['notes'],
            'submitted_by' => Auth::id(),
        ]);

        // Notify treasury users
        $this->notifyTreasuryUsers($payment);

        return redirect()->route('payments.index')
            ->with('success', 'Payment submitted successfully! Awaiting treasury verification.');
    }

    public function index()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        $payments = Payment::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('client.payments.index', compact('payments'));
    }

    private function notifyTreasuryUsers($payment)
    {
        // Get all treasury users
        $treasuryUsers = User::where('role', 'treasury')->get();

        foreach ($treasuryUsers as $user) {
            // Create notification (simplified for demo)
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'New Payment Submission',
                'message' => "Payment of TZS " . number_format($payment->amount, 0) . " submitted by " . $payment->client->company_name,
                'type' => 'payment',
                'data' => json_encode(['payment_id' => $payment->id]),
                'read' => false,
            ]);
        }
    }
}

