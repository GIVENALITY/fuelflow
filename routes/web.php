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
use App\Http\Controllers\ClientRegistrationController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\EnhancedPaymentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\StationAttendantController;
use App\Http\Controllers\NotificationController;

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

// Client Registration Routes (Public)
Route::get('/register', [ClientRegistrationController::class, 'showRegistrationForm'])->name('client-registration.index');
Route::post('/register', [ClientRegistrationController::class, 'store'])->name('client-registration.store');
Route::get('/registration-success', [ClientRegistrationController::class, 'success'])->name('client-registration.success');

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

// Payment Management (using EnhancedPaymentController)
// Route::resource('payments', PaymentController::class);
// Route::post('/payments/{payment}/process', [PaymentController::class, 'process'])->name('payments.process');
// Route::patch('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
// Route::patch('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

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
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

// Admin Client Management Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/clients/pending-applications', [AdminClientController::class, 'pendingApplications'])->name('clients.pending-applications');
    Route::get('/clients/{client}/application', [AdminClientController::class, 'showApplication'])->name('clients.show-application');
    Route::post('/clients/{client}/approve', [AdminClientController::class, 'approveClient'])->name('clients.approve');
    Route::post('/clients/{client}/reject', [AdminClientController::class, 'rejectClient'])->name('clients.reject');
    Route::post('/clients/{client}/mark-contract-sent', [AdminClientController::class, 'markContractSent'])->name('clients.mark-contract-sent');
    Route::post('/clients/{client}/upload-signed-contract', [AdminClientController::class, 'uploadSignedContract'])->name('clients.upload-signed-contract');
    Route::get('/clients/{client}/download-document/{type}', [AdminClientController::class, 'downloadDocument'])->name('clients.download-document');
    Route::get('/vehicles/{vehicle}/download-document/{type}', [AdminClientController::class, 'downloadVehicleDocument'])->name('vehicles.download-document');
});

// Client Order Management Routes
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [ClientOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [ClientOrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/special-approval', [ClientOrderController::class, 'requestSpecialApproval'])->name('orders.special-approval');
    Route::get('/orders/bulk-upload', [ClientOrderController::class, 'bulkUpload'])->name('orders.bulk-upload');
    Route::post('/orders/bulk-upload', [ClientOrderController::class, 'processBulkUpload'])->name('orders.process-bulk-upload');
    Route::get('/orders/download-template', [ClientOrderController::class, 'downloadTemplate'])->name('orders.download-template');
    Route::get('/vehicles/search', [ClientOrderController::class, 'searchVehicles'])->name('vehicles.search');
});

// Enhanced Payment Routes
Route::prefix('payments')->name('payments.')->group(function () {
    Route::get('/', [EnhancedPaymentController::class, 'index'])->name('index');
    Route::get('/create', [EnhancedPaymentController::class, 'create'])->name('create');
    Route::post('/', [EnhancedPaymentController::class, 'store'])->name('store');
    Route::get('/{payment}', [EnhancedPaymentController::class, 'show'])->name('show');
    Route::post('/{payment}/verify', [EnhancedPaymentController::class, 'verify'])->name('verify');
    Route::get('/{payment}/download-proof', [EnhancedPaymentController::class, 'downloadProof'])->name('download-proof');
    Route::get('/pending/list', [EnhancedPaymentController::class, 'pendingPayments'])->name('pending');
});


// SuperAdmin Routes
Route::prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/stations', [SuperAdminController::class, 'manageStations'])->name('stations.index');
    Route::get('/stations/create', [SuperAdminController::class, 'createStation'])->name('stations.create');
    Route::post('/stations', [SuperAdminController::class, 'storeStation'])->name('stations.store');
    Route::post('/stations/{station}/toggle-status', [SuperAdminController::class, 'toggleStationStatus'])->name('stations.toggle-status');
    Route::get('/users', [SuperAdminController::class, 'manageUsers'])->name('users.index');
    Route::get('/users/create', [SuperAdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::post('/users/{user}/toggle-status', [SuperAdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::get('/reports', [SuperAdminController::class, 'reports'])->name('reports.index');
});

// Station Manager Routes
Route::prefix('station-manager')->name('station-manager.')->group(function () {
    Route::get('/dashboard', [StationManagerController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendants', [StationManagerController::class, 'manageAttendants'])->name('attendants.index');
    Route::get('/attendants/create', [StationManagerController::class, 'addAttendant'])->name('attendants.create');
    Route::post('/attendants', [StationManagerController::class, 'storeAttendant'])->name('attendants.store');
    Route::post('/attendants/{attendant}/toggle-status', [StationManagerController::class, 'toggleAttendantStatus'])->name('attendants.toggle-status');
    Route::get('/orders', [StationManagerController::class, 'orders'])->name('orders.index');
    Route::post('/orders/{fuelRequest}/approve', [StationManagerController::class, 'approveOrder'])->name('orders.approve');
    Route::post('/orders/{fuelRequest}/reject', [StationManagerController::class, 'rejectOrder'])->name('orders.reject');
    Route::get('/receipts', [StationManagerController::class, 'receipts'])->name('receipts.index');
    Route::get('/inventory', [StationManagerController::class, 'stationInventory'])->name('inventory.index');
    Route::post('/inventory', [StationManagerController::class, 'updateInventory'])->name('inventory.update');
    Route::get('/reports', [StationManagerController::class, 'reports'])->name('reports.index');
});

// Station Attendant Routes
Route::prefix('station-attendant')->name('station-attendant.')->group(function () {
    Route::get('/dashboard', [StationAttendantController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [StationAttendantController::class, 'myOrders'])->name('orders.index');
    Route::get('/orders/history', [StationAttendantController::class, 'orderHistory'])->name('orders.history');
    Route::get('/orders/{fuelRequest}', [StationAttendantController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{fuelRequest}/fill', [StationAttendantController::class, 'fillOrder'])->name('orders.fill');
    Route::post('/vehicles/search', [StationAttendantController::class, 'searchVehicle'])->name('vehicles.search');
    Route::post('/orders/get-details', [StationAttendantController::class, 'getOrderDetails'])->name('orders.get-details');
    Route::get('/inventory', [StationAttendantController::class, 'getStationInventory'])->name('inventory');
});

});
