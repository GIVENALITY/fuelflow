<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isClient()) {
            $payments = Payment::where('client_id', $user->client->id)
                ->latest()
                ->paginate(20);
        } else {
            $payments = Payment::with('client')
                ->latest()
                ->paginate(20);
        }

        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $user = Auth::user();

        // Check permissions
        if ($user->isClient() && $payment->client_id !== $user->client->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('payments.show', compact('payment'));
    }
}