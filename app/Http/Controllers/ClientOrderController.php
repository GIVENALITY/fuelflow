<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use App\Models\FuelRequest;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isClient()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $client = Auth::user()->client;
        $vehicles = $client->vehicles()->where('status', Vehicle::STATUS_ACTIVE)->get();
        $stations = Station::where('status', Station::STATUS_ACTIVE)->get();
        
        $recentOrders = $client->fuelRequests()
            ->with(['vehicle', 'station'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('client.orders.index', compact('vehicles', 'stations', 'recentOrders'));
    }

    public function create()
    {
        $client = Auth::user()->client;
        $vehicles = $client->vehicles()->where('status', Vehicle::STATUS_ACTIVE)->get();
        $stations = Station::where('status', Station::STATUS_ACTIVE)->get();
        
        return view('client.orders.create', compact('vehicles', 'stations'));
    }

    public function store(Request $request)
    {
        $client = Auth::user()->client;
        
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'station_id' => 'required|exists:stations,id',
            'driver_name' => 'required|string|max:255',
            'quantity_requested' => 'required|numeric|min:1|max:1000',
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        // Verify vehicle belongs to client
        $vehicle = $client->vehicles()->findOrFail($request->vehicle_id);
        
        // Get current fuel price
        $fuelPrice = $this->getCurrentFuelPrice($request->station_id, $vehicle->fuel_type);
        $totalAmount = $request->quantity_requested * $fuelPrice;

        // Check credit limit
        $availableCredit = $client->credit_limit - $client->current_balance;
        
        if ($totalAmount > $availableCredit) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Order amount exceeds available credit limit. Available credit: TZS ' . number_format($availableCredit, 2));
        }

        try {
            DB::beginTransaction();

            // Create fuel request
            $fuelRequest = FuelRequest::create([
                'client_id' => $client->id,
                'vehicle_id' => $request->vehicle_id,
                'station_id' => $request->station_id,
                'fuel_type' => $vehicle->fuel_type,
                'quantity_requested' => $request->quantity_requested,
                'unit_price' => $fuelPrice,
                'total_amount' => $totalAmount,
                'request_date' => now(),
                'preferred_date' => now()->addDay(),
                'due_date' => now()->addDays(7),
                'status' => FuelRequest::STATUS_PENDING,
                'urgency_level' => FuelRequest::URGENCY_STANDARD,
                'special_instructions' => $request->special_instructions,
            ]);

            // Update client balance
            $client->increment('current_balance', $totalAmount);

            DB::commit();

            return redirect()->route('client.orders.index')
                ->with('success', 'Order submitted successfully! Order ID: ' . $fuelRequest->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit order. Please try again.');
        }
    }

    public function requestSpecialApproval(Request $request)
    {
        $client = Auth::user()->client;
        
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'station_id' => 'required|exists:stations,id',
            'driver_name' => 'required|string|max:255',
            'quantity_requested' => 'required|numeric|min:1|max:1000',
            'special_instructions' => 'required|string|max:1000',
            'approval_reason' => 'required|string|max:1000',
        ]);

        // Verify vehicle belongs to client
        $vehicle = $client->vehicles()->findOrFail($request->vehicle_id);
        
        // Get current fuel price
        $fuelPrice = $this->getCurrentFuelPrice($request->station_id, $vehicle->fuel_type);
        $totalAmount = $request->quantity_requested * $fuelPrice;

        try {
            DB::beginTransaction();

            // Create fuel request with special approval flag
            $fuelRequest = FuelRequest::create([
                'client_id' => $client->id,
                'vehicle_id' => $request->vehicle_id,
                'station_id' => $request->station_id,
                'fuel_type' => $vehicle->fuel_type,
                'quantity_requested' => $request->quantity_requested,
                'unit_price' => $fuelPrice,
                'total_amount' => $totalAmount,
                'request_date' => now(),
                'preferred_date' => now()->addDay(),
                'due_date' => now()->addDays(7),
                'status' => FuelRequest::STATUS_PENDING,
                'urgency_level' => FuelRequest::URGENCY_PRIORITY,
                'special_instructions' => $request->special_instructions . "\n\nSpecial Approval Request: " . $request->approval_reason,
            ]);

            DB::commit();

            return redirect()->route('client.orders.index')
                ->with('success', 'Special approval request submitted successfully! Order ID: ' . $fuelRequest->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit special approval request. Please try again.');
        }
    }

    public function bulkUpload()
    {
        $client = Auth::user()->client;
        $vehicles = $client->vehicles()->where('status', Vehicle::STATUS_ACTIVE)->get();
        $stations = Station::where('status', Station::STATUS_ACTIVE)->get();
        
        return view('client.orders.bulk-upload', compact('vehicles', 'stations'));
    }

    public function processBulkUpload(Request $request)
    {
        $request->validate([
            'bulk_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        // Process bulk upload logic here
        // This would parse the CSV/Excel file and create multiple orders
        
        return redirect()->route('client.orders.index')
            ->with('success', 'Bulk orders processed successfully!');
    }

    public function downloadTemplate()
    {
        // Generate and download bulk order template
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bulk_order_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Vehicle Plate Number', 'Station ID', 'Driver Name', 'Quantity (Liters)', 'Special Instructions']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getCurrentFuelPrice($stationId, $fuelType)
    {
        // Get current fuel price for the station and fuel type
        // This would typically come from a fuel prices table
        return 2500; // Default price in TZS per liter
    }

    public function searchVehicles(Request $request)
    {
        $client = Auth::user()->client;
        $query = $request->get('q');
        
        $vehicles = $client->vehicles()
            ->where('status', Vehicle::STATUS_ACTIVE)
            ->where(function($q) use ($query) {
                $q->where('plate_number', 'like', "%{$query}%")
                  ->orWhere('make', 'like', "%{$query}%")
                  ->orWhere('model', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($vehicles);
    }
}
