<?php

namespace App\Http\Controllers;

use App\Models\PricingStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PricingStrategyController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $strategies = PricingStrategy::orderBy('created_at', 'desc')->get();
        return view('pricing-strategies.index', compact('strategies'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('pricing-strategies.index')->with('error', 'Unauthorized access.');
        }

        return view('pricing-strategies.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('pricing-strategies.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'strategy_type' => 'required|in:percentage,fixed_amount,market_based,inflation_adjusted',
            'parameters' => 'required|array',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        // Validate parameters based on strategy type
        $this->validateParameters($request->strategy_type, $request->parameters);

        $strategy = PricingStrategy::create([
            'name' => $request->name,
            'description' => $request->description,
            'strategy_type' => $request->strategy_type,
            'parameters' => $request->parameters,
            'effective_from' => $request->effective_from,
            'effective_until' => $request->effective_until,
            'is_active' => true
        ]);

        return redirect()->route('pricing-strategies.index')
            ->with('success', 'Pricing strategy created successfully.');
    }

    public function show(PricingStrategy $pricingStrategy)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('pricing-strategies.index')->with('error', 'Unauthorized access.');
        }

        return view('pricing-strategies.show', compact('pricingStrategy'));
    }

    public function edit(PricingStrategy $pricingStrategy)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('pricing-strategies.index')->with('error', 'Unauthorized access.');
        }

        return view('pricing-strategies.edit', compact('pricingStrategy'));
    }

    public function update(Request $request, PricingStrategy $pricingStrategy)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('pricing-strategies.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'strategy_type' => 'required|in:percentage,fixed_amount,market_based,inflation_adjusted',
            'parameters' => 'required|array',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        // Validate parameters based on strategy type
        $this->validateParameters($request->strategy_type, $request->parameters);

        $pricingStrategy->update([
            'name' => $request->name,
            'description' => $request->description,
            'strategy_type' => $request->strategy_type,
            'parameters' => $request->parameters,
            'effective_from' => $request->effective_from,
            'effective_until' => $request->effective_until,
        ]);

        return redirect()->route('pricing-strategies.index')
            ->with('success', 'Pricing strategy updated successfully.');
    }

    public function destroy(PricingStrategy $pricingStrategy)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('pricing-strategies.index')->with('error', 'Unauthorized access.');
        }

        $pricingStrategy->delete();

        return redirect()->route('pricing-strategies.index')
            ->with('success', 'Pricing strategy deleted successfully.');
    }

    public function toggleActive(PricingStrategy $pricingStrategy)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('pricing-strategies.index')->with('error', 'Unauthorized access.');
        }

        $pricingStrategy->update(['is_active' => !$pricingStrategy->is_active]);

        $status = $pricingStrategy->is_active ? 'activated' : 'deactivated';
        return redirect()->route('pricing-strategies.index')
            ->with('success', "Pricing strategy {$status} successfully.");
    }

    public function preview(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'strategy_type' => 'required|in:percentage,fixed_amount,market_based,inflation_adjusted',
            'parameters' => 'required|array',
            'current_price' => 'required|numeric|min:0',
        ]);

        // Create a temporary strategy for preview
        $tempStrategy = new PricingStrategy([
            'strategy_type' => $request->strategy_type,
            'parameters' => $request->parameters,
            'is_active' => true,
            'effective_from' => Carbon::now()
        ]);

        $newPrice = $tempStrategy->calculateNewPrice($request->current_price);

        return response()->json([
            'current_price' => $request->current_price,
            'new_price' => $newPrice,
            'change' => $newPrice ? $newPrice - $request->current_price : 0,
            'change_percentage' => $newPrice ? (($newPrice - $request->current_price) / $request->current_price) * 100 : 0
        ]);
    }

    private function validateParameters($strategyType, $parameters)
    {
        switch ($strategyType) {
            case 'percentage':
                if (!isset($parameters['percentage']) || !is_numeric($parameters['percentage'])) {
                    throw new \InvalidArgumentException('Percentage parameter is required and must be numeric.');
                }
                break;

            case 'fixed_amount':
                if (!isset($parameters['amount']) || !is_numeric($parameters['amount'])) {
                    throw new \InvalidArgumentException('Amount parameter is required and must be numeric.');
                }
                break;

            case 'inflation_adjusted':
                if (!isset($parameters['inflation_rate']) || !is_numeric($parameters['inflation_rate'])) {
                    throw new \InvalidArgumentException('Inflation rate parameter is required and must be numeric.');
                }
                if (!isset($parameters['base_date']) || !Carbon::canParse($parameters['base_date'])) {
                    throw new \InvalidArgumentException('Base date parameter is required and must be a valid date.');
                }
                break;
        }
    }
}
