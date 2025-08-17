<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use App\Models\FuelRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $clients = Client::with(['vehicles', 'fuelRequests'])->get();
        } elseif ($user->isStationManager()) {
            $clients = Client::with(['vehicles', 'fuelRequests'])
                ->whereHas('fuelRequests', function($query) use ($user) {
                    $query->where('station_id', $user->station_id);
                })->get();
        } else {
            $clients = collect();
        }

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('clients.index')->with('error', 'Unauthorized access.');
        }

        return view('clients.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('clients.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'contact_person' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'credit_limit' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        $client->load(['vehicles', 'fuelRequests.receipt', 'payments']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('clients.index')->with('error', 'Unauthorized access.');
        }

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('clients.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'contact_person' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'credit_limit' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('clients.index')->with('error', 'Unauthorized access.');
        }

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    public function requests(Client $client)
    {
        $requests = $client->fuelRequests()->with(['vehicle', 'station'])->latest()->get();
        return view('clients.requests', compact('client', 'requests'));
    }

    public function payments(Client $client)
    {
        $payments = $client->payments()->with('receipt')->latest()->get();
        return view('clients.payments', compact('client', 'payments'));
    }

    public function vehicles(Client $client)
    {
        $vehicles = $client->vehicles()->with('fuelRequests')->get();
        return view('clients.vehicles', compact('client', 'vehicles'));
    }

    public function overdue()
    {
        $clients = Client::where('current_balance', '>', 0)
            ->where('status', Client::STATUS_ACTIVE)
            ->orderBy('current_balance', 'desc')
            ->get();
        
        return view('clients.overdue', compact('clients'));
    }

    // Vehicle management for clients
    public function addVehicle(Request $request, Client $client)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('clients.show', $client)->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'fuel_type' => 'required|in:petrol,diesel',
            'tank_capacity' => 'required|numeric|min:0',
            'current_fuel_level' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $client->vehicles()->create($request->all());

        return redirect()->route('clients.vehicles', $client)->with('success', 'Vehicle added successfully.');
    }

    public function updateVehicle(Request $request, Client $client, Vehicle $vehicle)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('clients.show', $client)->with('error', 'Unauthorized access.');
        }

        $request->validate([
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

        return redirect()->route('clients.vehicles', $client)->with('success', 'Vehicle updated successfully.');
    }

    public function deleteVehicle(Client $client, Vehicle $vehicle)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('clients.show', $client)->with('error', 'Unauthorized access.');
        }

        $vehicle->delete();

        return redirect()->route('clients.vehicles', $client)->with('success', 'Vehicle deleted successfully.');
    }
}
