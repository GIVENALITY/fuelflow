<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = AuditLog::with('user');

        // Apply filters
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        if (request('action')) {
            $query->where('action', request('action'));
        }

        if (request('model_type')) {
            $query->where('model_type', request('model_type'));
        }

        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        $auditLogs = $query->latest()->paginate(50);

        $users = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STATION_MANAGER, User::ROLE_TREASURY])
            ->get();

        $actions = AuditLog::select('action')->distinct()->pluck('action');
        $modelTypes = AuditLog::select('model_type')->distinct()->whereNotNull('model_type')->pluck('model_type');

        return view('audit-logs.index', compact('auditLogs', 'users', 'actions', 'modelTypes'));
    }

    public function show(AuditLog $auditLog)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $auditLog->load('user');

        return view('audit-logs.show', compact('auditLog'));
    }

    public function export()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = AuditLog::with('user');

        // Apply same filters as index
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        if (request('action')) {
            $query->where('action', request('action'));
        }

        if (request('model_type')) {
            $query->where('model_type', request('model_type'));
        }

        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        $auditLogs = $query->latest()->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($auditLogs) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'User',
                'Action',
                'Model Type',
                'Model ID',
                'Description',
                'IP Address',
                'User Agent',
                'URL',
                'Method',
                'Created At'
            ]);

            // CSV data
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'System',
                    $log->action_display_name,
                    $log->model_display_name,
                    $log->model_id,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->url,
                    $log->method,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function statistics()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        $query = AuditLog::whereBetween('created_at', [$dateFrom, $dateTo]);

        // Total activities
        $totalActivities = $query->count();

        // Activities by user
        $activitiesByUser = $query->join('users', 'audit_logs.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('count(*) as count'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Activities by action
        $activitiesByAction = $query->select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->get();

        // Activities by model
        $activitiesByModel = $query->whereNotNull('model_type')
            ->select('model_type', DB::raw('count(*) as count'))
            ->groupBy('model_type')
            ->orderBy('count', 'desc')
            ->get();

        // Daily activities
        $dailyActivities = $query->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('audit-logs.statistics', compact(
            'totalActivities',
            'activitiesByUser',
            'activitiesByAction',
            'activitiesByModel',
            'dailyActivities',
            'dateFrom',
            'dateTo'
        ));
    }
}
