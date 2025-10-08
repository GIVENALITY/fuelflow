<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FuelRequest;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Station;
use App\Models\FuelPrice;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class FuelRequestController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $requests = FuelRequest::with(['client', 'vehicle', 'station'])->latest()->paginate(10);
        } elseif ($user->isStationManager()) {
            $requests = FuelRequest::with(['client', 'vehicle'])
                ->where('station_id', $user->station_id)
                ->latest()
                ->paginate(10);
        } elseif ($user->isFuelPumper()) {
            $requests = FuelRequest::with(['client', 'vehicle'])
                ->where('assigned_pumper_id', $user->id)
                ->latest()
                ->paginate(10);
        } elseif ($user->isTreasury()) {
            $requests = FuelRequest::with(['client', 'vehicle', 'station'])->latest()->paginate(10);
        } else {
            // Client view
            $client = $user->client;
            if ($client) {
                $requests = FuelRequest::with(['vehicle', 'station'])
                    ->where('client_id', $client->id)
                    ->latest()
                    ->paginate(10);
            } else {
                $requests = collect();
            }
        }

        // Calculate summary statistics
        $totalRequests = FuelRequest::count();
        $pendingRequests = FuelRequest::where('status', FuelRequest::STATUS_PENDING)->count();
        $approvedRequests = FuelRequest::where('status', FuelRequest::STATUS_APPROVED)->count();
        $completedRequests = FuelRequest::where('status', FuelRequest::STATUS_COMPLETED)->count();

        return view('fuel-requests.index', compact(
            'requests',
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'completedRequests'
        ));
    }

    public function pending()
    {
        $user = Auth::user();

        if (!$user->isStationManager()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$user->station_id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not assigned to any station. Please contact an administrator.');
        }

        $requests = FuelRequest::with(['client', 'vehicle'])
            ->where('station_id', $user->station_id)
            ->where('status', FuelRequest::STATUS_PENDING)
            ->latest()
            ->paginate(10);

        return view('fuel-requests.pending', compact('requests'));
    }

    public function myAssignments()
    {
        $user = Auth::user();

        if (!$user->isFuelPumper()) {
            abort(403, 'Unauthorized action.');
        }

        $requests = FuelRequest::with(['client', 'vehicle', 'station'])
            ->where('assigned_pumper_id', $user->id)
            ->whereIn('status', [FuelRequest::STATUS_APPROVED, FuelRequest::STATUS_IN_PROGRESS])
            ->latest()
            ->paginate(10);

        return view('fuel-requests.my-assignments', compact('requests'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isClient()) {
            $client = $user->client;
            if (!$client) {
                return redirect()->route('client-portal.requests.index')
                    ->with('error', 'Your account is not linked to a client profile. Please contact support.');
            }
            $vehicles = Vehicle::where('client_id', $client->id)
                ->where('status', Vehicle::STATUS_ACTIVE)
                ->get();
            $stations = Station::where('status', Station::STATUS_ACTIVE)->get();
            $fuelPrices = FuelPrice::where('is_active', true)->get();

            return view('fuel-requests.create', compact('vehicles', 'stations', 'fuelPrices'));
        }

        abort(403, 'Unauthorized action.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isClient()) {
            abort(403, 'Unauthorized action.');
        }

        $client = $user->client;
        if (!$client) {
            return redirect()->route('client-portal.requests.index')
                ->with('error', 'Your account is not linked to a client profile. Please contact support.');
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'station_id' => 'required|exists:stations,id',
            'fuel_type' => 'required|in:diesel,petrol',
            'quantity_requested' => 'required|numeric|min:0',
            'preferred_date' => 'required|date|after:today',
            'special_instructions' => 'nullable|string'
        ]);

        // Get current fuel price
        $fuelPrice = FuelPrice::where('station_id', $validated['station_id'])
            ->where('fuel_type', $validated['fuel_type'])
            ->where('is_active', true)
            ->first();

        if (!$fuelPrice) {
            return back()->withErrors(['fuel_type' => 'No active price found for this fuel type at the selected station.']);
        }

        // Calculate total amount
        $totalAmount = $validated['quantity_requested'] * $fuelPrice->price;

        // Check credit limit
        if ($client->current_balance + $totalAmount > $client->credit_limit) {
            return back()->withErrors(['quantity_requested' => 'This request would exceed your credit limit.']);
        }

        // Create fuel request
        $fuelRequest = FuelRequest::create([
            'client_id' => $client->id,
            'vehicle_id' => $validated['vehicle_id'],
            'station_id' => $validated['station_id'],
            'fuel_type' => $validated['fuel_type'],
            'quantity_requested' => $validated['quantity_requested'],
            'unit_price' => $fuelPrice->price,
            'total_amount' => $totalAmount,
            'request_date' => now(),
            'preferred_date' => $validated['preferred_date'],
            'due_date' => $validated['preferred_date'],
            'status' => FuelRequest::STATUS_PENDING,
            'urgency_level' => 'standard',
            'special_instructions' => $validated['special_instructions'],
        ]);

        return redirect()->route('fuel-requests.index')
            ->with('success', 'Fuel request submitted successfully!');
    }

    public function show(FuelRequest $fuelRequest)
    {
        $user = Auth::user();

        // Check if user has permission to view this request
        if (!$this->canViewRequest($user, $fuelRequest)) {
            abort(403, 'Unauthorized action.');
        }

        $fuelRequest->load(['client', 'vehicle', 'station', 'approvedBy', 'assignedPumper', 'dispensedBy']);

        return view('fuel-requests.show', compact('fuelRequest'));
    }

    public function edit(FuelRequest $fuelRequest)
    {
        $user = Auth::user();

        // Only allow editing if request is pending and user is the requester
        if (
            $fuelRequest->status !== FuelRequest::STATUS_PENDING ||
            $fuelRequest->client_id !== $user->client?->id
        ) {
            abort(403, 'Unauthorized action.');
        }

        $vehicles = Vehicle::where('client_id', $user->client->id)
            ->where('status', Vehicle::STATUS_ACTIVE)
            ->get();
        $stations = Station::where('status', Station::STATUS_ACTIVE)->get();

        return view('fuel-requests.edit', compact('fuelRequest', 'vehicles', 'stations'));
    }

    public function update(Request $request, FuelRequest $fuelRequest)
    {
        $user = Auth::user();

        // Only allow updating if request is pending and user is the requester
        if (
            $fuelRequest->status !== FuelRequest::STATUS_PENDING ||
            $fuelRequest->client_id !== $user->client?->id
        ) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'station_id' => 'required|exists:stations,id',
            'fuel_type' => 'required|in:diesel,petrol',
            'quantity_requested' => 'required|numeric|min:0',
            'preferred_date' => 'required|date|after:today',
            'special_instructions' => 'nullable|string'
        ]);

        // Recalculate amount if needed
        $fuelPrice = FuelPrice::where('station_id', $validated['station_id'])
            ->where('fuel_type', $validated['fuel_type'])
            ->where('is_active', true)
            ->first();

        if ($fuelPrice) {
            $validated['unit_price'] = $fuelPrice->price;
            $validated['total_amount'] = $validated['quantity_requested'] * $fuelPrice->price;
        }

        $fuelRequest->update($validated);

        return redirect()->route('fuel-requests.index')
            ->with('success', 'Fuel request updated successfully!');
    }

    public function destroy(FuelRequest $fuelRequest)
    {
        $user = Auth::user();

        // Only allow deletion if request is pending and user is the requester
        if (
            $fuelRequest->status !== FuelRequest::STATUS_PENDING ||
            $fuelRequest->client_id !== $user->client?->id
        ) {
            abort(403, 'Unauthorized action.');
        }

        $fuelRequest->delete();

        return redirect()->route('fuel-requests.index')
            ->with('success', 'Fuel request cancelled successfully!');
    }

    public function approve(FuelRequest $fuelRequest)
    {
        $user = Auth::user();

        if (!$user->canApproveRequests()) {
            abort(403, 'Unauthorized action.');
        }

        $fuelRequest->approve($user);

        // Send notification
        $this->notificationService->sendFuelRequestApproved($fuelRequest);

        return redirect()->route('fuel-requests.index')
            ->with('success', 'Fuel request approved successfully!');
    }

    public function reject(Request $request, FuelRequest $fuelRequest)
    {
        $user = Auth::user();

        if (!$user->canApproveRequests()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $fuelRequest->reject($user, $validated['rejection_reason']);

        // Send notification
        $this->notificationService->sendFuelRequestRejected($fuelRequest, $validated['rejection_reason']);

        return redirect()->route('fuel-requests.index')
            ->with('success', 'Fuel request rejected successfully!');
    }

    public function assign(Request $request, FuelRequest $fuelRequest)
    {
        $user = Auth::user();

        if (!$user->isStationManager()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'pumper_id' => 'required|exists:users,id'
        ]);

        $pumper = User::findOrFail($validated['pumper_id']);

        // Verify the pumper belongs to the same station
        if ($pumper->station_id !== $user->station_id || $pumper->role !== 'station_attendant') {
            return redirect()->back()->with('error', 'Invalid fuel pumper selected.');
        }

        $fuelRequest->assignPumper($pumper);

        // Send notification
        $this->notificationService->sendFuelRequestAssigned($fuelRequest, $pumper);

        return redirect()->route('fuel-requests.index')
            ->with('success', 'Fuel request assigned to ' . $pumper->name . ' successfully!');
    }

    public function dispense(Request $request, FuelRequest $fuelRequest)
    {
        $user = Auth::user();

        if (!$user->isFuelPumper()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'quantity_dispensed' => 'required|numeric|min:0|max:' . $fuelRequest->quantity_requested,
            'notes' => 'nullable|string'
        ]);

        $fuelRequest->dispense($user, $validated['quantity_dispensed'], $validated['notes'] ?? null);

        // Send notification
        $this->notificationService->sendFuelDispensed($fuelRequest);

        return redirect()->route('fuel-requests.index')
            ->with('success', 'Fuel dispensed successfully!');
    }

    private function canViewRequest($user, $fuelRequest)
    {
        if ($user->isAdmin() || $user->isTreasury()) {
            return true;
        }

        if ($user->isStationManager() && $fuelRequest->station_id === $user->station_id) {
            return true;
        }

        if ($user->isFuelPumper() && $fuelRequest->assigned_pumper_id === $user->id) {
            return true;
        }

        if ($user->isClient() && $fuelRequest->client_id === $user->client?->id) {
            return true;
        }

        return false;
    }
}
