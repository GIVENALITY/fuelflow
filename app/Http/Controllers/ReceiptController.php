<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with(['client', 'station', 'fuelRequest'])->latest()->paginate(10);
        return view('receipts.index', compact('receipts'));
    }

    public function pending()
    {
        $receipts = Receipt::pending()
            ->with(['client', 'station', 'fuelRequest', 'uploadedBy'])
            ->latest()
            ->paginate(10);
        
        return view('receipts.pending', compact('receipts'));
    }

    public function show(Receipt $receipt)
    {
        $receipt->load(['client', 'station', 'fuelRequest', 'uploadedBy', 'verifiedBy']);
        return view('receipts.show', compact('receipt'));
    }

    public function verify(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'verification_notes' => 'nullable|string|max:500'
        ]);

        $receipt->verify(Auth::id(), $validated['verification_notes'] ?? null);

        return redirect()->route('receipts.pending')
            ->with('success', 'Receipt verified successfully!');
    }

    public function reject(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'verification_notes' => 'required|string|max:500'
        ]);

        $receipt->reject(Auth::id(), $validated['verification_notes']);

        return redirect()->route('receipts.pending')
            ->with('success', 'Receipt rejected successfully!');
    }
}
