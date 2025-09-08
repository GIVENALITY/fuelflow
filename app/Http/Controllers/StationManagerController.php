<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StationManagerController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $managers = User::where('role', User::ROLE_STATION_MANAGER)
            ->with(['station'])
            ->get();

        return view('station-managers.index', compact('managers'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $stations = Station::active()->get();
        return view('station-managers.create', compact('stations'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
            'station_id' => 'nullable|exists:stations,id',
            'status' => 'required|in:active,inactive'
        ]);

        $manager = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_STATION_MANAGER,
            'station_id' => $request->station_id,
            'status' => $request->status,
            'onboarding_completed' => false,
        ]);

        return redirect()->route('station-managers.index')
            ->with('success', 'Station manager created successfully.');
    }

    public function show(User $stationManager)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($stationManager->role !== User::ROLE_STATION_MANAGER) {
            return redirect()->route('station-managers.index')
                ->with('error', 'User is not a station manager.');
        }

        $stationManager->load(['station']);
        return view('station-managers.show', compact('stationManager'));
    }

    public function edit(User $stationManager)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($stationManager->role !== User::ROLE_STATION_MANAGER) {
            return redirect()->route('station-managers.index')
                ->with('error', 'User is not a station manager.');
        }

        $stations = Station::active()->get();
        return view('station-managers.edit', compact('stationManager', 'stations'));
    }

    public function update(Request $request, User $stationManager)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($stationManager->role !== User::ROLE_STATION_MANAGER) {
            return redirect()->route('station-managers.index')
                ->with('error', 'User is not a station manager.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $stationManager->id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'station_id' => 'nullable|exists:stations,id',
            'status' => 'required|in:active,inactive'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'station_id' => $request->station_id,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $stationManager->update($updateData);

        return redirect()->route('station-managers.index')
            ->with('success', 'Station manager updated successfully.');
    }

    public function destroy(User $stationManager)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($stationManager->role !== User::ROLE_STATION_MANAGER) {
            return redirect()->route('station-managers.index')
                ->with('error', 'User is not a station manager.');
        }

        // Check if manager is assigned to any station
        if ($stationManager->station) {
            return redirect()->route('station-managers.index')
                ->with('error', 'Cannot delete station manager who is assigned to a station. Please reassign the station first.');
        }

        $stationManager->delete();

        return redirect()->route('station-managers.index')
            ->with('success', 'Station manager deleted successfully.');
    }

    public function assignStation(Request $request, User $stationManager)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($stationManager->role !== User::ROLE_STATION_MANAGER) {
            return redirect()->route('station-managers.index')
                ->with('error', 'User is not a station manager.');
        }

        $request->validate([
            'station_id' => 'required|exists:stations,id'
        ]);

        // Check if station already has a manager
        $existingManager = User::where('station_id', $request->station_id)
            ->where('role', User::ROLE_STATION_MANAGER)
            ->where('id', '!=', $stationManager->id)
            ->first();

        if ($existingManager) {
            return redirect()->back()
                ->with('error', 'This station already has a manager assigned.');
        }

        $stationManager->update(['station_id' => $request->station_id]);

        return redirect()->back()
            ->with('success', 'Station manager assigned to station successfully.');
    }

    public function unassignStation(User $stationManager)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($stationManager->role !== User::ROLE_STATION_MANAGER) {
            return redirect()->route('station-managers.index')
                ->with('error', 'User is not a station manager.');
        }

        $stationManager->update(['station_id' => null]);

        return redirect()->back()
            ->with('success', 'Station manager unassigned from station successfully.');
    }
}
