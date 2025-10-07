<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function pendingApplications()
    {
        $clients = Client::where('registration_status', Client::REGISTRATION_STATUS_PENDING)
            ->with(['user', 'vehicles', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.clients.pending-applications', compact('clients'));
    }

    public function showApplication(Client $client)
    {
        $client->load(['user', 'vehicles', 'approvedBy']);
        return view('admin.clients.show-application', compact('client'));
    }

    public function approveClient(Request $request, Client $client)
    {
        $request->validate([
            'credit_limit' => 'required|numeric|min:0',
            'approval_notes' => 'nullable|string|max:1000',
        ]);

        $client->update([
            'registration_status' => Client::REGISTRATION_STATUS_APPROVED,
            'status' => Client::STATUS_ACTIVE,
            'credit_limit' => $request->credit_limit,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
        ]);

        return redirect()->route('admin.clients.pending-applications')
            ->with('success', 'Client approved successfully.');
    }

    public function rejectClient(Request $request, Client $client)
    {
        $request->validate([
            'approval_notes' => 'required|string|max:1000',
        ]);

        $client->update([
            'registration_status' => Client::REGISTRATION_STATUS_REJECTED,
            'status' => Client::STATUS_INACTIVE,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
        ]);

        return redirect()->route('admin.clients.pending-applications')
            ->with('success', 'Client application rejected.');
    }

    public function markContractSent(Request $request, Client $client)
    {
        $request->validate([
            'contract_file' => 'required|file|mimes:pdf|max:5120',
        ]);

        $filename = 'contract_' . $client->id . '_' . time() . '.pdf';
        $path = $request->file('contract_file')->storeAs('contracts', $filename, 'public');

        $client->update([
            'contract_sent' => true,
            'contract_sent_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Contract marked as sent successfully.');
    }

    public function uploadSignedContract(Request $request, Client $client)
    {
        $request->validate([
            'signed_contract' => 'required|file|mimes:pdf|max:5120',
        ]);

        $filename = 'signed_contract_' . $client->id . '_' . time() . '.pdf';
        $path = $request->file('signed_contract')->storeAs('contracts/signed', $filename, 'public');

        $client->update([
            'signed_contract_path' => $path,
            'contract_signed_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Signed contract uploaded successfully.');
    }

    public function downloadDocument(Client $client, $type)
    {
        $validTypes = ['tin_document', 'brela_certificate', 'business_license', 'director_id'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $pathField = $type . '_path';
        $filePath = $client->$pathField;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('public')->download($filePath);
    }

    public function downloadVehicleDocument(Vehicle $vehicle, $type)
    {
        $validTypes = ['head_card', 'trailer_card'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $pathField = $type . '_path';
        $filePath = $vehicle->$pathField;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('public')->download($filePath);
    }
}
