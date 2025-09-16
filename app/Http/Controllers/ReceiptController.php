<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\FuelRequest;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReceiptController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isTreasury()) {
            $receipts = Receipt::with(['client', 'fuelRequest', 'station', 'uploadedBy'])
                ->latest()
                ->paginate(20);
        } elseif ($user->isStationManager()) {
            $receipts = Receipt::with(['client', 'fuelRequest', 'station', 'uploadedBy'])
                ->where('station_id', $user->station_id)
                ->latest()
                ->paginate(20);
        } elseif ($user->isFuelPumper()) {
            // Fuel pumpers see receipts for requests they were assigned to
            $receipts = Receipt::with(['client', 'fuelRequest', 'station', 'uploadedBy'])
                ->whereHas('fuelRequest', function ($query) use ($user) {
                    $query->where('assigned_pumper_id', $user->id);
                })
                ->latest()
                ->paginate(20);
        } else {
            // For other roles (like clients), show their receipts
            if ($user->client) {
                $receipts = Receipt::with(['fuelRequest', 'station', 'uploadedBy'])
                    ->where('client_id', $user->client->id)
                    ->latest()
                    ->paginate(20);
            } else {
                // If no client relationship, return empty collection
                $receipts = collect()->paginate(20);
            }
        }

        return view('receipts.index', compact('receipts'));
    }

    public function pending()
    {
        $user = Auth::user();

        // Only Treasury can access pending receipts (per TOR)
        if (!$user->isTreasury()) {
            abort(403, 'Unauthorized access. Only Treasury can verify receipts.');
        }

        $receipts = Receipt::with(['client', 'fuelRequest', 'station', 'uploadedBy'])
            ->where('status', Receipt::STATUS_PENDING)
            ->latest()
            ->paginate(20);

        return view('receipts.pending', compact('receipts'));
    }

    public function create()
    {
        $user = Auth::user();

        // Only Station Managers and Fuel Pumpers can upload receipts (per TOR)
        if ($user->isStationManager()) {
            $fuelRequests = FuelRequest::where('station_id', $user->station_id)
                ->where('status', FuelRequest::STATUS_DISPENSED)
                ->whereNull('receipt_id')
                ->with(['vehicle', 'station', 'client'])
                ->get();
        } elseif ($user->isFuelPumper()) {
            $fuelRequests = FuelRequest::where('assigned_pumper_id', $user->id)
                ->where('status', FuelRequest::STATUS_DISPENSED)
                ->whereNull('receipt_id')
                ->with(['vehicle', 'station', 'client'])
                ->get();
        } else {
            abort(403, 'Unauthorized action. Only Station Managers and Fuel Pumpers can upload receipts.');
        }

        return view('receipts.create', compact('fuelRequests'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Only Station Managers and Fuel Pumpers can upload receipts (per TOR)
        if (!$user->isStationManager() && !$user->isFuelPumper()) {
            abort(403, 'Unauthorized action. Only Station Managers and Fuel Pumpers can upload receipts.');
        }

        $validated = $request->validate([
            'fuel_request_id' => 'required|exists:fuel_requests,id',
            'amount' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'fuel_type' => 'required|in:diesel,petrol',
            'receipt_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'notes' => 'nullable|string|max:500'
        ]);

        // Verify the fuel request based on user role (only Station Managers and Fuel Pumpers)
        $fuelRequest = null;

        if ($user->isStationManager()) {
            $fuelRequest = FuelRequest::where('id', $validated['fuel_request_id'])
                ->where('station_id', $user->station_id)
                ->firstOrFail();
        } elseif ($user->isFuelPumper()) {
            $fuelRequest = FuelRequest::where('id', $validated['fuel_request_id'])
                ->where('assigned_pumper_id', $user->id)
                ->firstOrFail();
        }

        // Handle file upload
        $file = $request->file('receipt_photo');
        $filename = 'receipts/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public', $filename);

        // Generate receipt number
        $receiptNumber = 'RCP-' . str_pad(Receipt::count() + 1, 6, '0', STR_PAD_LEFT);

        // Create receipt
        $receipt = Receipt::create([
            'fuel_request_id' => $validated['fuel_request_id'],
            'client_id' => $fuelRequest->client_id,
            'station_id' => $fuelRequest->station_id,
            'uploaded_by' => $user->id,
            'amount' => $validated['amount'],
            'quantity' => $validated['quantity'],
            'fuel_type' => $validated['fuel_type'],
            'receipt_number' => $receiptNumber,
            'file_path' => $filename,
            'status' => Receipt::STATUS_PENDING,
            'verification_notes' => $validated['notes']
        ]);

        // Update fuel request
        $fuelRequest->update(['receipt_id' => $receipt->id]);

        // Send notification to treasury team
        $this->notificationService->sendReceiptVerification($receipt, 'pending');

        return redirect()->route('receipts.index')
            ->with('success', 'Receipt uploaded successfully! It will be verified by our treasury team.');
    }

    public function show(Receipt $receipt)
    {
        $user = Auth::user();

        // Check permissions
        if (!$this->canViewReceipt($user, $receipt)) {
            abort(403, 'Unauthorized action.');
        }

        $receipt->load(['client', 'fuelRequest.vehicle', 'station', 'uploadedBy', 'verifiedBy']);

        return view('receipts.show', compact('receipt'));
    }

    public function verify(Request $request, Receipt $receipt)
    {
        $user = Auth::user();

        // Only Treasury can verify receipts (per TOR)
        if (!$user->isTreasury()) {
            abort(403, 'Unauthorized action. Only Treasury can verify receipts.');
        }

        $validated = $request->validate([
            'action' => 'required|in:verify,reject',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validated['action'] === 'verify') {
            $receipt->verify($user, $validated['notes']);
            $this->notificationService->sendReceiptVerification($receipt, 'verified');
        } else {
            $receipt->reject($user, $validated['notes']);
            $this->notificationService->sendReceiptVerification($receipt, 'rejected');
        }

        return redirect()->route('receipts.index')
            ->with('success', 'Receipt ' . $validated['action'] . 'd successfully!');
    }

    public function reject(Request $request, Receipt $receipt)
    {
        $user = Auth::user();

        // Only Treasury can reject receipts (per TOR)
        if (!$user->isTreasury()) {
            abort(403, 'Unauthorized action. Only Treasury can reject receipts.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $receipt->reject($user, $validated['notes'] ?? 'Receipt rejected');

        return redirect()->back()->with('success', 'Receipt rejected successfully!');
    }

    public function download(Receipt $receipt)
    {
        $user = Auth::user();

        if (!$this->canViewReceipt($user, $receipt)) {
            abort(403, 'Unauthorized action.');
        }

        if (!Storage::exists('public/' . $receipt->file_path)) {
            abort(404, 'Receipt file not found.');
        }

        return Storage::download('public/' . $receipt->file_path, $receipt->receipt_number . '.jpg');
    }

    private function canViewReceipt($user, $receipt)
    {
        if ($user->isAdmin() || $user->isTreasury()) {
            return true;
        }

        if ($user->isStationManager() && $receipt->station_id === $user->station_id) {
            return true;
        }

        if ($user->isClient() && $receipt->client_id === $user->client?->id) {
            return true;
        }

        return false;
    }
}