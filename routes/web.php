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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Fuel Request Management
Route::resource('fuel-requests', FuelRequestController::class);
Route::post('/fuel-requests/{fuelRequest}/approve', [FuelRequestController::class, 'approve'])->name('fuel-requests.approve');
Route::post('/fuel-requests/{fuelRequest}/reject', [FuelRequestController::class, 'reject'])->name('fuel-requests.reject');
Route::post('/fuel-requests/{fuelRequest}/assign', [FuelRequestController::class, 'assign'])->name('fuel-requests.assign');
Route::post('/fuel-requests/{fuelRequest}/dispense', [FuelRequestController::class, 'dispense'])->name('fuel-requests.dispense');

// Client Management
Route::resource('clients', ClientController::class);
Route::get('/clients/{client}/requests', [ClientController::class, 'requests'])->name('clients.requests');
Route::get('/clients/{client}/payments', [ClientController::class, 'payments'])->name('clients.payments');
Route::get('/clients/{client}/vehicles', [ClientController::class, 'vehicles'])->name('clients.vehicles');
Route::get('/clients/overdue', [ClientController::class, 'overdue'])->name('clients.overdue');

// Station Management
Route::resource('stations', StationController::class);
Route::get('/stations/{station}/requests', [StationController::class, 'requests'])->name('stations.requests');
Route::get('/stations/{station}/inventory', [StationController::class, 'inventory'])->name('stations.inventory');
Route::post('/stations/{station}/restock', [StationController::class, 'restock'])->name('stations.restock');

// Vehicle Management
Route::resource('vehicles', VehicleController::class);
Route::get('/vehicles/{vehicle}/requests', [VehicleController::class, 'requests'])->name('vehicles.requests');

// Receipt Management
Route::resource('receipts', ReceiptController::class);
Route::get('/receipts/pending', [ReceiptController::class, 'pending'])->name('receipts.pending');
Route::post('/receipts/{receipt}/verify', [ReceiptController::class, 'verify'])->name('receipts.verify');
Route::post('/receipts/{receipt}/reject', [ReceiptController::class, 'reject'])->name('receipts.reject');

// Payment Management
Route::resource('payments', PaymentController::class);
Route::post('/payments/{payment}/process', [PaymentController::class, 'process'])->name('payments.process');

// Fuel Price Management
Route::resource('fuel-prices', FuelPriceController::class);
Route::get('/fuel-prices/current', [FuelPriceController::class, 'current'])->name('fuel-prices.current');

// User Management
Route::resource('users', UserController::class);
Route::get('/users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/operational', [ReportController::class, 'operational'])->name('reports.operational');
Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
Route::get('/reports/client-analytics', [ReportController::class, 'clientAnalytics'])->name('reports.client-analytics');
Route::get('/reports/system', [ReportController::class, 'system'])->name('reports.system');
Route::get('/reports/station', [ReportController::class, 'station'])->name('reports.station');
Route::get('/reports/my-activity', [ReportController::class, 'myActivity'])->name('reports.my-activity');

// Profile & Settings
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

// Notifications
Route::get('/notifications', function () {
    return view('notifications.index');
})->name('notifications.index');

});
