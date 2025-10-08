<?php

namespace App\Http\Controllers;

use App\Models\FuelRequest;
use App\Models\Vehicle;
use App\Models\Station;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientOrderController extends Controller
{
    public function create()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        // Get client's vehicles
        $vehicles = $client->vehicles;
        
        // Get available stations
        $stations = Station::where('status', 'active')->get();
        
        // Get client's credit info
        $creditLimit = $client->credit_limit ?? 0;
        $currentBalance = $client->current_balance ?? 0;
        $availableCredit = $creditLimit - $currentBalance;

        return view('client.orders.create', compact('vehicles', 'stations', 'creditLimit', 'currentBalance', 'availableCredit'));
    }

    public function store(Request $request)
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        $validated = $request->validate([
            'driver_name' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'station_id' => 'required|exists:stations,id',
            'fuel_type' => 'required|in:diesel,petrol',
            'quantity_requested' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
        ]);

        // Get fuel price (simplified for demo)
        $pricePerLiter = 3000; // Default price - should come from fuel_prices table
        $totalAmount = $validated['quantity_requested'] * $pricePerLiter;
        
        // Check if within credit limit
        $availableCredit = ($client->credit_limit ?? 0) - ($client->current_balance ?? 0);
        
        $needsApproval = false;
        if ($totalAmount > $availableCredit) {
            $needsApproval = true;
        }

        // Create fuel request
        $fuelRequest = FuelRequest::create([
            'client_id' => $client->id,
            'vehicle_id' => $validated['vehicle_id'],
            'station_id' => $validated['station_id'],
            'fuel_type' => $validated['fuel_type'],
            'quantity_requested' => $validated['quantity_requested'],
            'amount' => $totalAmount,
            'driver_name' => $validated['driver_name'],
            'status' => $needsApproval ? 'pending_approval' : 'approved', // Auto-approve if within credit limit
            'request_date' => now(),
            'notes' => $validated['notes'],
        ]);

        // Auto-assign to pumper@fuelflow.co.tz if order is approved
        if (!$needsApproval) {
            $pumper = \App\Models\User::where('email', 'pumper@fuelflow.co.tz')->first();
            
            if ($pumper) {
                $fuelRequest->update([
                    'assigned_to' => $pumper->id,
                    'assigned_at' => now(),
                    'status' => 'in_progress'
                ]);
            }
        }

        if ($needsApproval) {
            return redirect()->route('client.orders.index')
                ->with('warning', 'Order created but requires approval as it exceeds your available credit limit.');
        }

        return redirect()->route('client.orders.index')
            ->with('success', 'Fuel order created, approved, and assigned successfully!');
    }

    public function index()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        $orders = FuelRequest::where('client_id', $client->id)
            ->with(['vehicle', 'station'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('client.orders.index', compact('orders'));
    }

    public function bulkUpload()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        // Get client's vehicles for the template
        $vehicles = $client->vehicles;
        $stations = Station::where('status', 'active')->get();

        return view('client.orders.bulk-upload', compact('vehicles', 'stations'));
    }

    public function storeBulk(Request $request)
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        $validated = $request->validate([
            'orders' => 'required|array|min:1',
            'orders.*.driver_name' => 'required|string',
            'orders.*.vehicle_id' => 'required|exists:vehicles,id',
            'orders.*.station_id' => 'required|exists:stations,id',
            'orders.*.fuel_type' => 'required|string',
            'orders.*.quantity_requested' => 'required|numeric|min:1',
        ]);

        $created = 0;
        $pricePerLiter = 3000; // Default price

        foreach ($validated['orders'] as $orderData) {
            $totalAmount = $orderData['quantity_requested'] * $pricePerLiter;
            
            FuelRequest::create([
                'client_id' => $client->id,
                'vehicle_id' => $orderData['vehicle_id'],
                'station_id' => $orderData['station_id'],
                'fuel_type' => $orderData['fuel_type'],
                'quantity_requested' => $orderData['quantity_requested'],
                'amount' => $totalAmount,
                'driver_name' => $orderData['driver_name'],
                'status' => 'pending',
                'request_date' => now(),
            ]);
            
            $created++;
        }

        return redirect()->route('client.orders.index')
            ->with('success', "{$created} fuel orders created successfully!");
    }
}
