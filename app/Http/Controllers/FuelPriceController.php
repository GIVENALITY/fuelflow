<?php

namespace App\Http\Controllers;

use App\Models\FuelPrice;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuelPriceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $fuelPrices = FuelPrice::with('station')->latest('effective_date')->get();
        } elseif ($user->isStationManager()) {
            $fuelPrices = FuelPrice::with('station')
                ->where('station_id', $user->station_id)
                ->latest('effective_date')->get();
        } else {
            $fuelPrices = collect();
        }

        return view('fuel-prices.index', compact('fuelPrices'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('fuel-prices.index')->with('error', 'Unauthorized access.');
        }

        $stations = Station::where('status', Station::STATUS_ACTIVE)->get();
        return view('fuel-prices.create', compact('stations'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('fuel-prices.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'fuel_type' => 'required|in:petrol,diesel',
            'price' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string|max:500'
        ]);

        FuelPrice::create($request->all());

        return redirect()->route('fuel-prices.index')->with('success', 'Fuel price created successfully.');
    }

    public function show(FuelPrice $fuelPrice)
    {
        $fuelPrice->load('station');
        return view('fuel-prices.show', compact('fuelPrice'));
    }

    public function edit(FuelPrice $fuelPrice)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('fuel-prices.index')->with('error', 'Unauthorized access.');
        }

        $stations = Station::where('status', Station::STATUS_ACTIVE)->get();
        return view('fuel-prices.edit', compact('fuelPrice', 'stations'));
    }

    public function update(Request $request, FuelPrice $fuelPrice)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('fuel-prices.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'fuel_type' => 'required|in:petrol,diesel',
            'price' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string|max:500'
        ]);

        $fuelPrice->update($request->all());

        return redirect()->route('fuel-prices.index')->with('success', 'Fuel price updated successfully.');
    }

    public function destroy(FuelPrice $fuelPrice)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('fuel-prices.index')->with('error', 'Unauthorized access.');
        }

        $fuelPrice->delete();

        return redirect()->route('fuel-prices.index')->with('success', 'Fuel price deleted successfully.');
    }
}
