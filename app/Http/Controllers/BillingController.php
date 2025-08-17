<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\FuelType;

class BillingController extends Controller
{
    public function index()
    {
        $bills = Bill::with('customer')->latest()->paginate(10);
        
        // Calculate billing summary
        $totalBills = Bill::count();
        $paidAmount = Bill::where('status', 'paid')->sum('amount');
        $pendingAmount = Bill::where('status', 'pending')->sum('amount');
        $overdueAmount = Bill::where('status', 'overdue')->sum('amount');

        return view('billing.index', compact(
            'bills',
            'totalBills',
            'paidAmount',
            'pendingAmount',
            'overdueAmount'
        ));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $fuelTypes = FuelType::all();
        
        return view('billing.create', compact('customers', 'fuelTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'delivery_date' => 'required|date',
            'due_date' => 'required|date|after:delivery_date',
            'notes' => 'nullable|string'
        ]);

        // Calculate total amount
        $validated['amount'] = $validated['quantity'] * $validated['unit_price'];
        $validated['status'] = 'pending';

        $bill = Bill::create($validated);

        return redirect()->route('billing.index')
            ->with('success', 'Bill created successfully!');
    }

    public function show(Bill $bill)
    {
        $bill->load('customer', 'fuelType');
        return view('billing.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        $customers = Customer::where('status', 'active')->get();
        $fuelTypes = FuelType::all();
        
        return view('billing.edit', compact('bill', 'customers', 'fuelTypes'));
    }

    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'delivery_date' => 'required|date',
            'due_date' => 'required|date|after:delivery_date',
            'status' => 'required|in:pending,paid,overdue',
            'notes' => 'nullable|string'
        ]);

        // Recalculate amount if quantity or unit price changed
        $validated['amount'] = $validated['quantity'] * $validated['unit_price'];

        $bill->update($validated);

        return redirect()->route('billing.index')
            ->with('success', 'Bill updated successfully!');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();
        
        return redirect()->route('billing.index')
            ->with('success', 'Bill deleted successfully!');
    }

    public function export()
    {
        // Export billing data to CSV/Excel
        $bills = Bill::with('customer', 'fuelType')->get();
        
        // Implementation for export functionality
        return response()->download('bills_export.csv');
    }

    public function reports()
    {
        // Generate billing reports
        $monthlyRevenue = Bill::where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->get();

        $fuelTypeSales = Bill::with('fuelType')
            ->where('status', 'paid')
            ->selectRaw('fuel_type_id, SUM(quantity) as total_quantity, SUM(amount) as total_amount')
            ->groupBy('fuel_type_id')
            ->get();

        return view('billing.reports', compact('monthlyRevenue', 'fuelTypeSales'));
    }
}
