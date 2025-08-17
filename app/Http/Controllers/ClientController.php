<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\FuelRequest;
use App\Models\Payment;
use App\Models\Vehicle;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('user')->latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function show(Client $client)
    {
        $client->load(['user', 'vehicles', 'fuelRequests']);
        return view('clients.show', compact('client'));
    }

    public function requests(Client $client)
    {
        $requests = $client->fuelRequests()->with(['vehicle', 'station'])->latest()->paginate(10);
        return view('clients.requests', compact('client', 'requests'));
    }

    public function payments(Client $client)
    {
        $payments = $client->payments()->latest()->paginate(10);
        return view('clients.payments', compact('client', 'payments'));
    }

    public function vehicles(Client $client)
    {
        $vehicles = $client->vehicles()->latest()->paginate(10);
        return view('clients.vehicles', compact('client', 'vehicles'));
    }

    public function overdue()
    {
        $clients = Client::where('current_balance', '>', 0)
            ->where('status', Client::STATUS_ACTIVE)
            ->with('user')
            ->latest()
            ->paginate(10);
        
        return view('clients.overdue', compact('clients'));
    }
}
