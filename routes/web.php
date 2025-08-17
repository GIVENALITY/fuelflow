<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\DeliveryController;
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

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Billing Management
Route::resource('billing', BillingController::class);
Route::get('/billing/{billing}/export', [BillingController::class, 'export'])->name('billing.export');
Route::get('/billing/reports', [BillingController::class, 'reports'])->name('billing.reports');

// Customer Management
Route::resource('customers', CustomerController::class);
Route::get('/customers/{customer}/bills', [CustomerController::class, 'bills'])->name('customers.bills');
Route::get('/customers/{customer}/payments', [CustomerController::class, 'payments'])->name('customers.payments');

// Invoice Management
Route::resource('invoices', InvoiceController::class);
Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');

// Payment Management
Route::resource('payments', PaymentController::class);
Route::post('/payments/{payment}/process', [PaymentController::class, 'process'])->name('payments.process');

// Fuel Management
Route::resource('fuel', FuelController::class);
Route::get('/fuel/inventory', [FuelController::class, 'inventory'])->name('fuel.inventory');
Route::post('/fuel/restock', [FuelController::class, 'restock'])->name('fuel.restock');

// Delivery Management
Route::resource('deliveries', DeliveryController::class);
Route::post('/deliveries/{delivery}/complete', [DeliveryController::class, 'complete'])->name('deliveries.complete');
Route::get('/deliveries/schedule', [DeliveryController::class, 'schedule'])->name('deliveries.schedule');

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
Route::get('/reports/fuel', [ReportController::class, 'fuel'])->name('reports.fuel');

// Profile & Settings
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

// Notifications
Route::get('/notifications', function () {
    return view('notifications.index');
})->name('notifications.index');

// Logout
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');
