<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $totalSales = Bill::where('status', 'paid')->sum('amount');
        $activeCustomers = Customer::where('status', 'active')->count();
        $todayDeliveries = Delivery::whereDate('delivery_date', today())->count();
        $pendingInvoices = Invoice::where('status', 'pending')->count();
        
        // Get recent deliveries for the dashboard
        $recentDeliveries = Delivery::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalSales',
            'activeCustomers', 
            'todayDeliveries',
            'pendingInvoices',
            'recentDeliveries'
        ));
    }
}
