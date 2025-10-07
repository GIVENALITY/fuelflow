<?php

namespace App\Http\Controllers;

use App\Models\FuelRequest;
use App\Models\Receipt;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StationAttendantController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isStationAttendant()) {
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

        // Get assigned orders
        $assignedOrders = FuelRequest::where('assigned_pumper_id', Auth::id())
            ->where('status', FuelRequest::STATUS_APPROVED)
            ->with(['client', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get completed orders today
        $completedToday = FuelRequest::where('assigned_pumper_id', Auth::id())
            ->where('status', FuelRequest::STATUS_COMPLETED)
            ->whereDate('dispensed_at', today())
            ->count();

        // Get total liters dispensed today
        $litersToday = FuelRequest::where('assigned_pumper_id', Auth::id())
            ->where('status', FuelRequest::STATUS_COMPLETED)
            ->whereDate('dispensed_at', today())
            ->sum('quantity_dispensed');

        $stats = [
            'assigned_orders' => $assignedOrders->count(),
            'completed_today' => $completedToday,
            'liters_today' => $litersToday ?? 0,
        ];

        return view('station-attendant.dashboard', compact('assignedOrders', 'stats'));
    }

    public function searchVehicle(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20',
        ]);

        $vehicle = Vehicle::where('plate_number', 'like', '%' . $request->plate_number . '%')
            ->with(['client'])
            ->first();

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        return response()->json([
            'vehicle' => $vehicle,
            'client' => $vehicle->client,
        ]);
    }

    public function getOrderDetails(Request $request)
    {
        $request->validate([
            'vehicle_plate' => 'required|string|max:20',
        ]);

        $vehicle = Vehicle::where('plate_number', $request->vehicle_plate)->first();
        
        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        $order = FuelRequest::where('vehicle_id', $vehicle->id)
            ->where('assigned_pumper_id', Auth::id())
            ->where('status', FuelRequest::STATUS_APPROVED)
            ->with(['client', 'vehicle'])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'No approved order found for this vehicle'], 404);
        }

        return response()->json([
            'order' => $order,
            'client' => $order->client,
            'vehicle' => $order->vehicle,
        ]);
    }

    public function fillOrder(Request $request, FuelRequest $fuelRequest)
    {
        if ($fuelRequest->assigned_pumper_id !== Auth::id()) {
            return redirect()->back()->with('error', 'This order is not assigned to you.');
        }

        if ($fuelRequest->status !== FuelRequest::STATUS_APPROVED) {
            return redirect()->back()->with('error', 'This order is not approved for dispensing.');
        }

        $request->validate([
            'quantity_dispensed' => 'required|numeric|min:0.1|max:' . $fuelRequest->quantity_requested,
            'delivery_note' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'receipt_image' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Upload documents
            $deliveryNotePath = $this->uploadDocument($request->file('delivery_note'), 'delivery_notes');
            $receiptImagePath = $this->uploadDocument($request->file('receipt_image'), 'receipts');

            // Calculate actual amount based on dispensed quantity
            $actualAmount = $request->quantity_dispensed * $fuelRequest->unit_price;

            // Update fuel request
            $fuelRequest->update([
                'quantity_dispensed' => $request->quantity_dispensed,
                'total_amount' => $actualAmount,
                'status' => FuelRequest::STATUS_DISPENSED,
                'dispensed_by' => Auth::id(),
                'dispensed_at' => now(),
                'notes' => $request->notes,
            ]);

            // Create receipt record
            Receipt::create([
                'fuel_request_id' => $fuelRequest->id,
                'client_id' => $fuelRequest->client_id,
                'station_id' => $fuelRequest->station_id,
                'uploaded_by' => Auth::id(),
                'amount' => $actualAmount,
                'quantity' => $request->quantity_dispensed,
                'fuel_type' => $fuelRequest->fuel_type,
                'receipt_number' => 'RCP-' . str_pad($fuelRequest->id, 6, '0', STR_PAD_LEFT),
                'file_path' => $receiptImagePath,
                'status' => Receipt::STATUS_PENDING,
            ]);

            // Update station inventory
            $station = Auth::user()->station;
            $station->updateFuelLevel($fuelRequest->fuel_type, $request->quantity_dispensed, 'dispense');

            return redirect()->route('station-attendant.dashboard')
                ->with('success', 'Order completed successfully! Receipt created: RCP-' . str_pad($fuelRequest->id, 6, '0', STR_PAD_LEFT));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to complete order. Please try again.');
        }
    }

    public function myOrders()
    {
        $orders = FuelRequest::where('assigned_pumper_id', Auth::id())
            ->with(['client', 'vehicle', 'receipt'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('station-attendant.orders.index', compact('orders'));
    }

    public function orderHistory()
    {
        $orders = FuelRequest::where('assigned_pumper_id', Auth::id())
            ->where('status', FuelRequest::STATUS_COMPLETED)
            ->with(['client', 'vehicle', 'receipt'])
            ->orderBy('dispensed_at', 'desc')
            ->paginate(20);

        return view('station-attendant.orders.history', compact('orders'));
    }

    public function showOrder(FuelRequest $fuelRequest)
    {
        if ($fuelRequest->assigned_pumper_id !== Auth::id()) {
            return redirect()->route('station-attendant.dashboard')->with('error', 'Unauthorized access.');
        }

        $fuelRequest->load(['client', 'vehicle', 'receipt', 'station']);
        return view('station-attendant.orders.show', compact('fuelRequest'));
    }

    private function uploadDocument($file, $type)
    {
        $filename = $type . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/' . $type, $filename, 'public');
        return $path;
    }

    public function getStationInventory()
    {
        $station = Auth::user()->station;
        
        if (!$station) {
            return response()->json(['error' => 'No station assigned'], 404);
        }

        return response()->json([
            'diesel_level' => $station->current_diesel_level,
            'diesel_capacity' => $station->capacity_diesel,
            'petrol_level' => $station->current_petrol_level,
            'petrol_capacity' => $station->capacity_petrol,
            'diesel_utilization' => $station->diesel_utilization,
            'petrol_utilization' => $station->petrol_utilization,
        ]);
    }
}
