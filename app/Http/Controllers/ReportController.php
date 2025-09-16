<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FuelRequest;
use App\Models\Client;
use App\Models\Station;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        $reports = $this->getDashboardReports($user, $dateFrom, $dateTo);

        return view('reports.index', compact('reports', 'dateFrom', 'dateTo'));
    }

    public function operational()
    {
        $user = Auth::user();
        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        $reports = $this->getOperationalReports($user, $dateFrom, $dateTo);

        return view('reports.operational', compact('reports', 'dateFrom', 'dateTo'));
    }

    public function financial()
    {
        $user = Auth::user();
        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        $reports = $this->getFinancialReports($user, $dateFrom, $dateTo);

        return view('reports.financial', compact('reports', 'dateFrom', 'dateTo'));
    }

    public function clientAnalytics()
    {
        $user = Auth::user();
        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        $reports = $this->getClientAnalytics($user, $dateFrom, $dateTo);

        return view('reports.client-analytics', compact('reports', 'dateFrom', 'dateTo'));
    }

    public function station()
    {
        $user = Auth::user();
        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        $reports = $this->getStationReports($user, $dateFrom, $dateTo);

        return view('reports.station', compact('reports', 'dateFrom', 'dateTo'));
    }

    public function myActivity()
    {
        $user = Auth::user();
        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        $reports = $this->getMyActivityReports($user, $dateFrom, $dateTo);

        return view('reports.my-activity', compact('reports', 'dateFrom', 'dateTo'));
    }

    public function system()
    {
        $user = Auth::user();

        // Only allow admins to access this
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        // Get system-wide reports (same as index for admin)
        $reports = $this->getDashboardReports($user, $dateFrom, $dateTo);

        return view('reports.index', compact('reports', 'dateFrom', 'dateTo'));
    }

    public function allStations()
    {
        $user = Auth::user();

        // Only allow admins to access this
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        // Get aggregated reports for all stations
        $reports = $this->getAllStationsReports($user, $dateFrom, $dateTo);
        $stations = Station::with('manager')->get();

        return view('reports.all-stations', compact('reports', 'stations', 'dateFrom', 'dateTo'));
    }

    public function stationDetail($stationId)
    {
        $user = Auth::user();
        $station = Station::findOrFail($stationId);

        // Check if user has access to this station
        if (!$user->isAdmin() && !(($user->isStationManager() || $user->isFuelPumper()) && $user->station_id == $station->id)) {
            abort(403, 'Unauthorized access to station reports.');
        }

        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        // Get reports for specific station
        $reports = $this->getStationSpecificReports($station, $dateFrom, $dateTo);

        return view('reports.station-detail', compact('reports', 'station', 'dateFrom', 'dateTo'));
    }

    public function clientReports()
    {
        $user = Auth::user();

        // Only allow clients to access this
        if (!$user->isClient()) {
            abort(403, 'Unauthorized access.');
        }

        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        // Get client-specific reports
        $reports = $this->getClientSpecificReports($user, $dateFrom, $dateTo);

        return view('reports.client', compact('reports', 'dateFrom', 'dateTo'));
    }

    private function getDashboardReports($user, $dateFrom, $dateTo)
    {
        $query = FuelRequest::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        // Role-based filtering
        if ($user->isStationManager()) {
            // Station managers see only their station's data
            $query->where('station_id', $user->station_id);
        } elseif ($user->isClient()) {
            // Clients see only their own requests
            if ($user->client) {
                $query->where('client_id', $user->client->id);
            } else {
                // If no client relationship, return empty data
                return [
                    'total_requests' => 0,
                    'total_revenue' => 0,
                    'avg_request_value' => 0,
                    'status_breakdown' => collect(),
                    'daily_trends' => collect()
                ];
            }
        } elseif ($user->isFuelPumper()) {
            // Fuel pumpers see only their assigned requests
            $query->where('assigned_pumper_id', $user->id);
        } elseif ($user->isTreasury()) {
            // Treasury sees all requests for financial analysis
            // No additional filtering needed
        } elseif ($user->isAdmin()) {
            // Admins see all data
            // No additional filtering needed
        }

        $totalRequests = $query->count();
        $totalCreditSales = $query->sum('total_amount');
        $totalActualRevenue = $query->where('payment_status', 'paid')->sum('amount_paid');
        $avgRequestValue = $totalRequests > 0 ? $totalCreditSales / $totalRequests : 0;

        $statusBreakdown = $query->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $dailyTrends = $query->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as requests'), DB::raw('sum(total_amount) as credit_sales'), DB::raw('sum(CASE WHEN payment_status = "paid" THEN amount_paid ELSE 0 END) as actual_revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total_requests' => $totalRequests,
            'total_credit_sales' => $totalCreditSales,
            'total_actual_revenue' => $totalActualRevenue,
            'avg_request_value' => $avgRequestValue,
            'status_breakdown' => $statusBreakdown,
            'daily_trends' => $dailyTrends
        ];
    }

    private function getOperationalReports($user, $dateFrom, $dateTo)
    {
        $query = FuelRequest::whereBetween('fuel_requests.created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        // Role-based filtering
        if ($user->isStationManager()) {
            $query->where('fuel_requests.station_id', $user->station_id);
        } elseif ($user->isClient()) {
            if ($user->client) {
                $query->where('fuel_requests.client_id', $user->client->id);
            } else {
                return [
                    'fuel_type_breakdown' => collect(),
                    'station_performance' => collect()
                ];
            }
        } elseif ($user->isFuelPumper()) {
            $query->where('fuel_requests.assigned_pumper_id', $user->id);
        }
        // Admins and Treasury see all data

        $fuelTypeBreakdown = $query->select('fuel_type', DB::raw('count(*) as count'), DB::raw('sum(quantity_dispensed) as total_quantity'))
            ->whereNotNull('quantity_dispensed')
            ->groupBy('fuel_type')
            ->get();

        $stationPerformance = $query->join('stations', 'fuel_requests.station_id', '=', 'stations.id')
            ->select('stations.name', DB::raw('count(*) as requests'), DB::raw('sum(fuel_requests.total_amount) as revenue'))
            ->groupBy('stations.id', 'stations.name')
            ->orderBy('revenue', 'desc')
            ->get();

        return [
            'fuel_type_breakdown' => $fuelTypeBreakdown,
            'station_performance' => $stationPerformance
        ];
    }

    private function getFinancialReports($user, $dateFrom, $dateTo)
    {
        $query = FuelRequest::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        // Role-based filtering
        if ($user->isStationManager()) {
            $query->where('station_id', $user->station_id);
        } elseif ($user->isClient()) {
            if ($user->client) {
                $query->where('client_id', $user->client->id);
            } else {
                return [
                    'total_revenue' => 0,
                    'total_payments' => 0,
                    'outstanding_balance' => 0
                ];
            }
        } elseif ($user->isFuelPumper()) {
            $query->where('assigned_pumper_id', $user->id);
        }
        // Admins and Treasury see all data

        $totalCreditSales = $query->sum('total_amount');
        $totalActualRevenue = $query->where('payment_status', 'paid')->sum('amount_paid');

        // Payment and balance queries also need role-based filtering
        $paymentQuery = Payment::whereBetween('payment_date', [$dateFrom, $dateTo]);
        $balanceQuery = Client::query();

        if ($user->isStationManager()) {
            // Station managers see payments related to their station's requests
            $paymentQuery->whereHas('fuelRequest', function ($q) use ($user) {
                $q->where('station_id', $user->station_id);
            });
            // For balance, they see clients who have used their station
            $balanceQuery->whereHas('fuelRequests', function ($q) use ($user) {
                $q->where('station_id', $user->station_id);
            });
        } elseif ($user->isClient()) {
            if ($user->client) {
                $paymentQuery->where('client_id', $user->client->id);
                $balanceQuery->where('id', $user->client->id);
            }
        } elseif ($user->isFuelPumper()) {
            // Fuel pumpers see payments for their assigned requests
            $paymentQuery->whereHas('fuelRequest', function ($q) use ($user) {
                $q->where('assigned_pumper_id', $user->id);
            });
        }

        $totalPayments = $paymentQuery->sum('amount');
        $outstandingBalance = $balanceQuery->sum('current_balance');

        return [
            'total_credit_sales' => $totalCreditSales,
            'total_actual_revenue' => $totalActualRevenue,
            'total_payments' => $totalPayments,
            'outstanding_balance' => $outstandingBalance
        ];
    }

    private function getClientAnalytics($user, $dateFrom, $dateTo)
    {
        $query = FuelRequest::whereBetween('fuel_requests.created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        // Role-based filtering
        if ($user->isStationManager()) {
            $query->where('fuel_requests.station_id', $user->station_id);
        } elseif ($user->isClient()) {
            if ($user->client) {
                $query->where('fuel_requests.client_id', $user->client->id);
            } else {
                return [
                    'client_performance' => collect()
                ];
            }
        } elseif ($user->isFuelPumper()) {
            $query->where('fuel_requests.assigned_pumper_id', $user->id);
        }
        // Admins and Treasury see all data

        $clientPerformance = $query->join('clients', 'fuel_requests.client_id', '=', 'clients.id')
            ->select(
                'clients.company_name',
                'clients.status',
                DB::raw('count(*) as total_requests'),
                DB::raw('sum(fuel_requests.total_amount) as total_revenue')
            )
            ->groupBy('clients.id', 'clients.company_name', 'clients.status')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return [
            'client_performance' => $clientPerformance
        ];
    }

    private function getStationReports($user, $dateFrom, $dateTo)
    {
        $query = FuelRequest::whereBetween('fuel_requests.created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        // Role-based filtering
        if ($user->isStationManager()) {
            $query->where('fuel_requests.station_id', $user->station_id);
        } elseif ($user->isClient()) {
            if ($user->client) {
                $query->where('fuel_requests.client_id', $user->client->id);
            } else {
                return [
                    'station_performance' => collect()
                ];
            }
        } elseif ($user->isFuelPumper()) {
            $query->where('fuel_requests.assigned_pumper_id', $user->id);
        }
        // Admins and Treasury see all data

        $stationPerformance = $query->join('stations', 'fuel_requests.station_id', '=', 'stations.id')
            ->select(
                'stations.name',
                'stations.status',
                DB::raw('count(*) as total_requests'),
                DB::raw('sum(fuel_requests.total_amount) as total_revenue')
            )
            ->groupBy('stations.id', 'stations.name', 'stations.status')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return [
            'station_performance' => $stationPerformance
        ];
    }

    private function getMyActivityReports($user, $dateFrom, $dateTo)
    {
        if ($user->isClient()) {
            if ($user->client) {
                $myRequests = FuelRequest::where('client_id', $user->client->id)
                    ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                    ->get();

                return [
                    'my_requests' => $myRequests,
                    'monthly_spending' => $myRequests->sum('total_amount')
                ];
            } else {
                return [
                    'my_requests' => collect(),
                    'monthly_spending' => 0
                ];
            }
        } elseif ($user->isStationManager()) {
            $myRequests = FuelRequest::where('station_id', $user->station_id)
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->get();

            return [
                'my_requests' => $myRequests,
                'monthly_spending' => $myRequests->sum('total_amount')
            ];
        } elseif ($user->isFuelPumper()) {
            $myRequests = FuelRequest::where('assigned_pumper_id', $user->id)
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->get();

            return [
                'my_requests' => $myRequests,
                'monthly_spending' => $myRequests->sum('total_amount')
            ];
        }

        return [
            'my_requests' => collect(),
            'monthly_spending' => 0
        ];
    }

    private function getClientSpecificReports($user, $dateFrom, $dateTo)
    {
        if (!$user->client) {
            return [
                'total_requests' => 0,
                'total_spending' => 0,
                'avg_request_value' => 0,
                'status_breakdown' => collect(),
                'monthly_trends' => collect(),
                'fuel_type_breakdown' => collect(),
                'credit_utilization' => 0
            ];
        }

        $query = FuelRequest::where('client_id', $user->client->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        $totalRequests = $query->count();
        $totalCreditSpending = $query->sum('total_amount');
        $totalActualPayments = $query->where('payment_status', 'paid')->sum('amount_paid');
        $avgRequestValue = $totalRequests > 0 ? $totalCreditSpending / $totalRequests : 0;

        $statusBreakdown = FuelRequest::where('client_id', $user->client->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $monthlyTrends = FuelRequest::where('client_id', $user->client->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as requests'), DB::raw('sum(total_amount) as spending'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $fuelTypeBreakdown = FuelRequest::where('client_id', $user->client->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('fuel_type', DB::raw('count(*) as count'), DB::raw('sum(quantity_requested) as total_quantity'))
            ->groupBy('fuel_type')
            ->get();

        $creditUtilization = $user->client->credit_limit > 0 ?
            (($user->client->current_balance / $user->client->credit_limit) * 100) : 0;

        return [
            'total_requests' => $totalRequests,
            'total_credit_spending' => $totalCreditSpending,
            'total_actual_payments' => $totalActualPayments,
            'avg_request_value' => $avgRequestValue,
            'status_breakdown' => $statusBreakdown,
            'monthly_trends' => $monthlyTrends,
            'fuel_type_breakdown' => $fuelTypeBreakdown,
            'credit_utilization' => $creditUtilization
        ];
    }

    private function getAllStationsReports($user, $dateFrom, $dateTo)
    {
        // Get aggregated data for all stations
        $stations = Station::with('manager')->get();
        $stationReports = [];

        foreach ($stations as $station) {
            $query = FuelRequest::where('station_id', $station->id)
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);

            $totalRequests = $query->count();
            $totalCreditSales = $query->sum('total_amount');
            $totalActualRevenue = $query->where('payment_status', 'paid')->sum('amount_paid');
            $avgRequestValue = $totalRequests > 0 ? $totalCreditSales / $totalRequests : 0;

            $statusBreakdown = $query->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status');

            $fuelTypeBreakdown = $query->select('fuel_type', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as revenue'))
                ->groupBy('fuel_type')
                ->get();

            $stationReports[] = [
                'station' => $station,
                'total_requests' => $totalRequests,
                'total_credit_sales' => $totalCreditSales,
                'total_actual_revenue' => $totalActualRevenue,
                'avg_request_value' => $avgRequestValue,
                'status_breakdown' => $statusBreakdown,
                'fuel_type_breakdown' => $fuelTypeBreakdown
            ];
        }

        // Calculate system totals
        $systemQuery = FuelRequest::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);
        $systemTotals = [
            'total_requests' => $systemQuery->count(),
            'total_credit_sales' => $systemQuery->sum('total_amount'),
            'total_actual_revenue' => $systemQuery->where('payment_status', 'paid')->sum('amount_paid'),
            'active_stations' => $stations->where('status', 'active')->count(),
            'total_stations' => $stations->count()
        ];

        return [
            'station_reports' => $stationReports,
            'system_totals' => $systemTotals,
            'stations' => $stations
        ];
    }

    private function getStationSpecificReports($station, $dateFrom, $dateTo)
    {
        $query = FuelRequest::where('station_id', $station->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        $totalRequests = $query->count();
        $totalCreditSales = $query->sum('total_amount');
        $totalActualRevenue = $query->where('payment_status', 'paid')->sum('amount_paid');
        $avgRequestValue = $totalRequests > 0 ? $totalCreditSales / $totalRequests : 0;

        $statusBreakdown = $query->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $fuelTypeBreakdown = $query->select('fuel_type', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as revenue'))
            ->groupBy('fuel_type')
            ->get();

        $dailyTrends = $query->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as requests'), DB::raw('sum(total_amount) as credit_sales'), DB::raw('sum(CASE WHEN payment_status = "paid" THEN amount_paid ELSE 0 END) as actual_revenue'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Get station staff
        $staff = User::where('station_id', $station->id)->get();

        return [
            'station' => $station,
            'total_requests' => $totalRequests,
            'total_credit_sales' => $totalCreditSales,
            'total_actual_revenue' => $totalActualRevenue,
            'avg_request_value' => $avgRequestValue,
            'status_breakdown' => $statusBreakdown,
            'fuel_type_breakdown' => $fuelTypeBreakdown,
            'daily_trends' => $dailyTrends,
            'staff' => $staff
        ];
    }
}