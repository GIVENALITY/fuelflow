<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $payments = Payment::with(['client', 'receipt'])->latest()->get();
        } elseif ($user->isTreasury()) {
            $payments = Payment::with(['client', 'receipt'])->latest()->get();
        } elseif ($user->isStationManager()) {
            $payments = Payment::with(['client', 'receipt'])
                ->whereHas('receipt', function($query) use ($user) {
                    $query->where('station_id', $user->station_id);
                })->latest()->get();
        } else {
            $payments = collect();
        }

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        if (!Auth::user()->isTreasury()) {
            return redirect()->route('payments.index')->with('error', 'Unauthorized access.');
        }

        $clients = Client::where('status', Client::STATUS_ACTIVE)->get();
        $receipts = Receipt::where('status', Receipt::STATUS_PENDING)->get();
        
        return view('payments.create', compact('clients', 'receipts'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isTreasury()) {
            return redirect()->route('payments.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'receipt_id' => 'nullable|exists:receipts,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,check,mobile_money',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:pending,completed,failed'
        ]);

        Payment::create($request->all());

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['client', 'receipt']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        if (!Auth::user()->isTreasury()) {
            return redirect()->route('payments.index')->with('error', 'Unauthorized access.');
        }

        $clients = Client::where('status', Client::STATUS_ACTIVE)->get();
        $receipts = Receipt::where('status', Receipt::STATUS_PENDING)->get();
        
        return view('payments.edit', compact('payment', 'clients', 'receipts'));
    }

    public function update(Request $request, Payment $payment)
    {
        if (!Auth::user()->isTreasury()) {
            return redirect()->route('payments.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'receipt_id' => 'nullable|exists:receipts,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,check,mobile_money',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:pending,completed,failed'
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        if (!Auth::user()->isTreasury()) {
            return redirect()->route('payments.index')->with('error', 'Unauthorized access.');
        }

        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function approve(Payment $payment)
    {
        if (!Auth::user()->isTreasury()) {
            return redirect()->route('payments.index')->with('error', 'Unauthorized access.');
        }

        $payment->update(['status' => Payment::STATUS_COMPLETED]);

        return redirect()->route('payments.index')->with('success', 'Payment approved successfully.');
    }

    public function reject(Payment $payment)
    {
        if (!Auth::user()->isTreasury()) {
            return redirect()->route('payments.index')->with('error', 'Unauthorized access.');
        }

        $payment->update(['status' => Payment::STATUS_FAILED]);

        return redirect()->route('payments.index')->with('success', 'Payment rejected successfully.');
    }
}
