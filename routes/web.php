<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;
use App\Models\Setting;

// ============================================
// PUBLIC ROUTES — Customer Facing
// ============================================

Route::get('/', function() {
    $settings = [
        'base_price' => Setting::getValue('base_price_per_gallon', 25.00),
        'delivery_small_price' => Setting::getValue('delivery_small_order_price', 30.00),
        'bulk_threshold' => (int) Setting::getValue('delivery_bulk_threshold', 5.00),
    ];
    return view('home', compact('settings'));
})->name('home');

// Order form
Route::get('/order', function () {
    return view('order');
})->name('order');

// Place order (form submit)
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

// Receipt page
Route::get('/receipt/{id}', function ($id) {
    $order = \App\Models\Order::findOrFail($id);
    return view('receipt', compact('order'));
})->name('receipt');

// Contact form submit
Route::post('/contact', [MessageController::class, 'store'])->name('contact.store');

// ============================================
// AUTH ROUTES — No middleware
// ============================================
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// ============================================
// PROTECTED ROUTES — Requires auth.user middleware
// ============================================
Route::middleware('auth.user')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard (both roles)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Orders (both)
    Route::put('/orders/{id}', [AdminController::class, 'updateOrder'])->name('orders.update');
    Route::delete('/orders/{id}', [AdminController::class, 'deleteOrder'])->name('orders.delete');

    // Messages (both)
    Route::put('/messages/{id}', [MessageController::class, 'update'])->name('messages.update');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        // Pricing
        Route::post('/pricing', [AdminController::class, 'updatePricing'])->name('pricing.update');

        // User management
        Route::get('/users', [AdminController::class, 'showUsers'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    });
});