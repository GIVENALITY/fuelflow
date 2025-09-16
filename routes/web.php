<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FuelRequestController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FuelPriceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\BulkPriceUpdateController;
use App\Http\Controllers\StationManagerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,15');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Two-Factor Authentication Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/two-factor/setup', [App\Http\Controllers\TwoFactorController::class, 'showSetup'])->name('two-factor.setup');
    Route::post('/two-factor/enable', [App\Http\Controllers\TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/two-factor/disable', [App\Http\Controllers\TwoFactorController::class, 'disable'])->name('two-factor.disable');
});

Route::get('/two-factor/login', [App\Http\Controllers\TwoFactorController::class, 'showLogin'])->name('two-factor.login');
Route::post('/two-factor/verify', [App\Http\Controllers\TwoFactorController::class, 'verify'])->name('two-factor.verify')->middleware('throttle:5,15');
Route::get('/two-factor/recovery', [App\Http\Controllers\TwoFactorController::class, 'showRecovery'])->name('two-factor.recovery');
Route::post('/two-factor/recovery/verify', [App\Http\Controllers\TwoFactorController::class, 'verifyRecovery'])->name('two-factor.recovery.verify')->middleware('throttle:5,15');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fuel Request Management
    Route::get('/fuel-requests/pending', [FuelRequestController::class, 'pending'])->name('fuel-requests.pending');
    Route::get('/fuel-requests/my-assignments', [FuelRequestController::class, 'myAssignments'])->name('fuel-requests.my-assignments');
    Route::post('/fuel-requests/{fuelRequest}/approve', [FuelRequestController::class, 'approve'])->name('fuel-requests.approve')->middleware('throttle:10,1');
    Route::post('/fuel-requests/{fuelRequest}/reject', [FuelRequestController::class, 'reject'])->name('fuel-requests.reject')->middleware('throttle:10,1');
    Route::post('/fuel-requests/{fuelRequest}/assign', [FuelRequestController::class, 'assign'])->name('fuel-requests.assign')->middleware('throttle:10,1');
    Route::post('/fuel-requests/{fuelRequest}/dispense', [FuelRequestController::class, 'dispense'])->name('fuel-requests.dispense')->middleware('throttle:10,1');
    Route::resource('fuel-requests', FuelRequestController::class);

    // Client Management
    Route::get('/clients/overdue', [ClientController::class, 'overdue'])->name('clients.overdue');
    Route::resource('clients', ClientController::class);
    Route::get('/clients/{client}/requests', [ClientController::class, 'requests'])->name('clients.requests');
    Route::get('/clients/{client}/payments', [ClientController::class, 'payments'])->name('clients.payments');
    Route::get('/clients/{client}/vehicles', [ClientController::class, 'vehicles'])->name('clients.vehicles');

    // Client Vehicle Management
    Route::post('/clients/{client}/vehicles/add', [ClientController::class, 'addVehicle'])->name('clients.vehicles.add');
    Route::put('/clients/{client}/vehicles/{vehicle}/update', [ClientController::class, 'updateVehicle'])->name('clients.vehicles.update');
    Route::delete('/clients/{client}/vehicles/{vehicle}/delete', [ClientController::class, 'deleteVehicle'])->name('clients.vehicles.delete');

    // Station Management
    Route::resource('stations', StationController::class);
    Route::get('/stations/{station}/requests', [StationController::class, 'requests'])->name('stations.requests');
    Route::get('/stations/{station}/inventory', [StationController::class, 'inventory'])->name('stations.inventory');
    Route::post('/stations/{station}/restock', [StationController::class, 'restock'])->name('stations.restock');

    // Station Manager Management
    Route::resource('station-managers', StationManagerController::class);
    Route::post('/station-managers/{stationManager}/assign', [StationManagerController::class, 'assignStation'])->name('station-managers.assign');
    Route::post('/station-managers/{stationManager}/unassign', [StationManagerController::class, 'unassignStation'])->name('station-managers.unassign');

    // Vehicle Management
    Route::resource('vehicles', VehicleController::class);
    Route::get('/vehicles/{vehicle}/requests', [VehicleController::class, 'requests'])->name('vehicles.requests');

    // Receipt Management
    Route::get('/receipts/pending', [ReceiptController::class, 'pending'])->name('receipts.pending');
    Route::post('/receipts/{receipt}/verify', [ReceiptController::class, 'verify'])->name('receipts.verify');
    Route::post('/receipts/{receipt}/reject', [ReceiptController::class, 'reject'])->name('receipts.reject');
    Route::resource('receipts', ReceiptController::class);

    // Payment Management
    Route::resource('payments', PaymentController::class);
    Route::post('/payments/{payment}/process', [PaymentController::class, 'process'])->name('payments.process');
    Route::patch('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::patch('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

    // Fuel Price Management
    Route::resource('fuel-prices', FuelPriceController::class);
    Route::get('/fuel-prices/current', [FuelPriceController::class, 'current'])->name('fuel-prices.current');

    // User Management
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/operational', [ReportController::class, 'operational'])->name('reports.operational');
    Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('/reports/client-analytics', [ReportController::class, 'clientAnalytics'])->name('reports.client-analytics');
    Route::get('/reports/system', [ReportController::class, 'system'])->name('reports.system');
    Route::get('/reports/station', [ReportController::class, 'station'])->name('reports.station');
    Route::get('/reports/my-activity', [ReportController::class, 'myActivity'])->name('reports.my-activity');
    Route::get('/reports/all-stations', [ReportController::class, 'allStations'])->name('reports.all-stations');
    Route::get('/reports/station/{stationId}', [ReportController::class, 'stationDetail'])->name('reports.station-detail');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Location Management
    Route::resource('locations', LocationController::class);
    Route::get('/locations/type/{type}', [LocationController::class, 'getByType'])->name('locations.by-type');

    // Route Management
    Route::resource('routes', RouteController::class);
    Route::post('/routes/{route}/reorder-stops', [RouteController::class, 'reorderStops'])->name('routes.reorder-stops');
    Route::post('/routes/{route}/add-stop', [RouteController::class, 'addStop'])->name('routes.add-stop');
    Route::delete('/routes/{route}/stops/{stop}', [RouteController::class, 'removeStop'])->name('routes.remove-stop');

    // Onboarding
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding/complete', [OnboardingController::class, 'markComplete'])->name('onboarding.complete');

    // Bulk Price Updates
    Route::get('/bulk-price-updates', [BulkPriceUpdateController::class, 'index'])->name('bulk-price-updates.index');
    Route::get('/bulk-price-updates/download-template', [BulkPriceUpdateController::class, 'downloadTemplate'])->name('bulk-price-updates.download-template');
    Route::post('/bulk-price-updates/upload', [BulkPriceUpdateController::class, 'upload'])->name('bulk-price-updates.upload');
    Route::post('/bulk-price-updates/apply', [BulkPriceUpdateController::class, 'apply'])->name('bulk-price-updates.apply');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');

    // Client Portal Routes
    Route::prefix('client-portal')->name('client-portal.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return app(\App\Http\Controllers\DashboardController::class)->index();
        })->name('dashboard');

        Route::get('/requests', [App\Http\Controllers\FuelRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [App\Http\Controllers\FuelRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [App\Http\Controllers\FuelRequestController::class, 'store'])->name('requests.store');
        Route::get('/requests/{fuelRequest}', [App\Http\Controllers\FuelRequestController::class, 'show'])->name('requests.show');

        Route::get('/vehicles', [App\Http\Controllers\VehicleController::class, 'index'])->name('vehicles.index');

        Route::get('/payments', [App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/reports', [App\Http\Controllers\ReportController::class, 'clientReports'])->name('reports.index');
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    });

    // Approval Chains
    Route::resource('approval-chains', App\Http\Controllers\ApprovalChainController::class);
    Route::post('/approval-chains/{approvalChain}/duplicate', [App\Http\Controllers\ApprovalChainController::class, 'duplicate'])->name('approval-chains.duplicate');



    // Payments
    Route::get('/payments', [App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payments.show');

    // Audit Logs
    Route::get('/audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [App\Http\Controllers\AuditLogController::class, 'show'])->name('audit-logs.show');
    Route::get('/audit-logs/export', [App\Http\Controllers\AuditLogController::class, 'export'])->name('audit-logs.export');
    Route::get('/audit-logs/statistics', [App\Http\Controllers\AuditLogController::class, 'statistics'])->name('audit-logs.statistics');

});
