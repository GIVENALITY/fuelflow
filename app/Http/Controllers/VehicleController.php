<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $vehicles = Vehicle::with(['client', 'fuelRequests'])->get();
        } elseif ($user->isStationManager()) {
            $vehicles = Vehicle::with(['client', 'fuelRequests'])
                ->whereHas('fuelRequests', function($query) use ($user) {
                    $query->where('station_id', $user->station_id);
                })->get();
        } else {
            $vehicles = collect();
        }

        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('vehicles.index')->with('error', 'Unauthorized access.');
        }

        $clients = Client::where('status', Client::STATUS_ACTIVE)->get();
        return view('vehicles.create', compact('clients'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('vehicles.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'plate_number' => 'required|string|max:20|unique:vehicles',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'fuel_type' => 'required|in:petrol,diesel',
            'tank_capacity' => 'required|numeric|min:0',
            'current_fuel_level' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['client', 'fuelRequests.receipt']);
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('vehicles.index')->with('error', 'Unauthorized access.');
        }

        $clients = Client::where('status', Client::STATUS_ACTIVE)->get();
        return view('vehicles.edit', compact('vehicle', 'clients'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('vehicles.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number,' . $vehicle->id,
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'fuel_type' => 'required|in:petrol,diesel',
            'tank_capacity' => 'required|numeric|min:0',
            'current_fuel_level' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('vehicles.index')->with('error', 'Unauthorized access.');
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully.');
    }
}
