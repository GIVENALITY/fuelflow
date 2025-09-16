<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $stations = Station::with(['manager', 'location'])->get();
        } elseif ($user->isStationManager()) {
            $stations = Station::where('id', $user->station_id)->with(['manager', 'location'])->get();
        } else {
            $stations = collect();
        }

        return view('stations.index', compact('stations'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $locations = Location::active()->get();
        return view('stations.create', compact('locations'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:stations,code',
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,inactive'
        ]);

        Station::create($request->all());

        return redirect()->route('stations.index')->with('success', 'Station created successfully.');
    }

    public function show(Station $station)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $user->station_id !== $station->id) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $station->load(['manager', 'location', 'fuelRequests', 'receipts']);

        return view('stations.show', compact('station'));
    }

    public function edit(Station $station)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $locations = Location::active()->get();
        return view('stations.edit', compact('station', 'locations'));
    }

    public function update(Request $request, Station $station)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:stations,code,' . $station->id,
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,inactive'
        ]);

        $station->update($request->all());

        return redirect()->route('stations.index')->with('success', 'Station updated successfully.');
    }

    public function destroy(Station $station)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $station->delete();

        return redirect()->route('stations.index')->with('success', 'Station deleted successfully.');
    }

    public function inventory(Station $station)
    {
        $user = Auth::user();

        // Check if user has access to this station
        if (!$user->isAdmin() && !(($user->isStationManager() || $user->isFuelPumper()) && $user->station_id == $station->id)) {
            abort(403, 'Unauthorized access to station inventory.');
        }

        // Get the actual station manager (user with station_manager role assigned to this station)
        $stationManager = User::where('station_id', $station->id)
            ->where('role', 'station_manager')
            ->first();

        // Generate unique inventory data for each station based on station ID
        // In a real app, this would come from a fuel inventory table
        $baseDieselLevel = 10000 + ($station->id * 500); // Vary by station ID
        $basePetrolLevel = 5000 + ($station->id * 300);  // Vary by station ID

        $dieselCapacity = 20000 + ($station->id * 1000); // Vary capacity too
        $petrolCapacity = 15000 + ($station->id * 500);

        // Add some randomness to make it more realistic
        $dieselVariation = rand(-2000, 2000);
        $petrolVariation = rand(-1500, 1500);

        $dieselLevel = max(0, $baseDieselLevel + $dieselVariation);
        $petrolLevel = max(0, $basePetrolLevel + $petrolVariation);

        // Determine status based on percentage
        $dieselPercentage = ($dieselLevel / $dieselCapacity) * 100;
        $petrolPercentage = ($petrolLevel / $petrolCapacity) * 100;

        $inventory = [
            'diesel' => [
                'current_level' => $dieselLevel,
                'capacity' => $dieselCapacity,
                'last_updated' => now()->subHours(rand(1, 6)),
                'status' => $dieselPercentage > 20 ? 'good' : 'low'
            ],
            'petrol' => [
                'current_level' => $petrolLevel,
                'capacity' => $petrolCapacity,
                'last_updated' => now()->subHours(rand(1, 6)),
                'status' => $petrolPercentage > 20 ? 'good' : 'low'
            ]
        ];

        // Get all stations for admin dropdown
        $allStations = $user->isAdmin() ? Station::all() : collect();

        return view('stations.inventory', compact('station', 'inventory', 'allStations', 'stationManager'));
    }
}
