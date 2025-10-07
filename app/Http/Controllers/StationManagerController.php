<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Station;
use App\Models\FuelRequest;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StationManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isStationManager()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $station = Auth::user()->station;
        
        if (!$station) {
            return redirect()->route('dashboard')->with('error', 'No station assigned to you.');
        }

        // Get station statistics
        $stats = [
            'total_attendants' => $station->staff()->where('role', User::ROLE_STATION_ATTENDANT)->count(),
            'pending_orders' => $station->fuelRequests()->where('status', FuelRequest::STATUS_PENDING)->count(),
            'approved_orders' => $station->fuelRequests()->where('status', FuelRequest::STATUS_APPROVED)->count(),
            'completed_orders' => $station->fuelRequests()->where('status', FuelRequest::STATUS_COMPLETED)->count(),
            'today_orders' => $station->fuelRequests()->whereDate('created_at', today())->count(),
        ];

        // Get recent orders
        $recentOrders = $station->fuelRequests()
            ->with(['client', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get pending receipts
        $pendingReceipts = Receipt::where('station_id', $station->id)
            ->where('status', Receipt::STATUS_PENDING)
            ->with(['client', 'fuelRequest'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('station-manager.dashboard', compact('stats', 'recentOrders', 'pendingReceipts'));
    }

    public function manageAttendants()
    {
        $station = Auth::user()->station;
        
        if (!$station) {
            return redirect()->route('dashboard')->with('error', 'No station assigned to you.');
        }

        $attendants = $station->staff()
            ->where('role', User::ROLE_STATION_ATTENDANT)
            ->with(['fuelRequests' => function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->get();

        return view('station-manager.attendants.index', compact('attendants'));
    }

    public function addAttendant()
    {
        return view('station-manager.attendants.create');
    }

    public function storeAttendant(Request $request)
    {
        $station = Auth::user()->station;
        
        if (!$station) {
            return redirect()->route('dashboard')->with('error', 'No station assigned to you.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_STATION_ATTENDANT,
            'station_id' => $station->id,
            'phone' => $request->phone,
            'status' => User::STATUS_ACTIVE,
        ]);

        return redirect()->route('station-manager.attendants.index')
            ->with('success', 'Attendant added successfully.');
    }

    public function toggleAttendantStatus(User $attendant)
    {
        if ($attendant->station_id !== Auth::user()->station_id || $attendant->role !== User::ROLE_STATION_ATTENDANT) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $newStatus = $attendant->status === User::STATUS_ACTIVE ? User::STATUS_INACTIVE : User::STATUS_ACTIVE;
        $attendant->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', 'Attendant status updated successfully.');
    }

    public function orders()
    {
        $station = Auth::user()->station;
        
        if (!$station) {
            return redirect()->route('dashboard')->with('error', 'No station assigned to you.');
        }

        $orders = $station->fuelRequests()
            ->with(['client', 'vehicle', 'assignedPumper'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('station-manager.orders.index', compact('orders'));
    }

    public function approveOrder(Request $request, FuelRequest $fuelRequest)
    {
        if ($fuelRequest->station_id !== Auth::user()->station_id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'assigned_pumper_id' => 'required|exists:users,id',
        ]);

        // Verify the assigned user is an attendant at this station
        $attendant = User::where('id', $request->assigned_pumper_id)
            ->where('station_id', Auth::user()->station_id)
            ->where('role', User::ROLE_STATION_ATTENDANT)
            ->first();

        if (!$attendant) {
            return redirect()->back()->with('error', 'Invalid attendant selected.');
        }

        $fuelRequest->update([
            'status' => FuelRequest::STATUS_APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'assigned_pumper_id' => $request->assigned_pumper_id,
        ]);

        return redirect()->back()
            ->with('success', 'Order approved and assigned successfully.');
    }

    public function rejectOrder(Request $request, FuelRequest $fuelRequest)
    {
        if ($fuelRequest->station_id !== Auth::user()->station_id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $fuelRequest->update([
            'status' => FuelRequest::STATUS_REJECTED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()
            ->with('success', 'Order rejected successfully.');
    }

    public function receipts()
    {
        $station = Auth::user()->station;
        
        if (!$station) {
            return redirect()->route('dashboard')->with('error', 'No station assigned to you.');
        }

        $receipts = Receipt::where('station_id', $station->id)
            ->with(['client', 'fuelRequest', 'uploadedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('station-manager.receipts.index', compact('receipts'));
    }

    public function stationInventory()
    {
        $station = Auth::user()->station;
        
        if (!$station) {
            return redirect()->route('dashboard')->with('error', 'No station assigned to you.');
        }

        return view('station-manager.inventory.index', compact('station'));
    }

    public function updateInventory(Request $request)
    {
        $station = Auth::user()->station;
        
        if (!$station) {
            return redirect()->route('dashboard')->with('error', 'No station assigned to you.');
        }

        $request->validate([
            'current_diesel_level' => 'required|numeric|min:0|max:' . $station->capacity_diesel,
            'current_petrol_level' => 'required|numeric|min:0|max:' . $station->capacity_petrol,
        ]);

        $station->update([
            'current_diesel_level' => $request->current_diesel_level,
            'current_petrol_level' => $request->current_petrol_level,
        ]);

        return redirect()->back()
            ->with('success', 'Inventory updated successfully.');
    }

    public function reports()
    {
        $station = Auth::user()->station;
        
        if (!$station) {
            return redirect()->route('dashboard')->with('error', 'No station assigned to you.');
        }

        // Generate station-specific reports
        $monthlyOrders = $station->fuelRequests()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(quantity_dispensed) as total_liters')
            ->where('created_at', '>=', now()->subDays(30))
            ->whereNotNull('quantity_dispensed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $attendantPerformance = $station->staff()
            ->where('role', User::ROLE_STATION_ATTENDANT)
            ->withCount(['fuelRequests as completed_orders' => function($query) {
                $query->where('status', FuelRequest::STATUS_COMPLETED)
                      ->where('created_at', '>=', now()->subDays(30));
            }])
            ->get();

        return view('station-manager.reports.index', compact('monthlyOrders', 'attendantPerformance'));
    }
}