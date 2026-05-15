<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;

// ============================================
// PUBLIC ROUTES — Customer Facing
// ============================================

// Home (Hero, About, Services, Features, Team, Contact all in one page)
Route::get('/', function () {
    return view('home');
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
// ADMIN AUTH ROUTES — No middleware
// ============================================

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// ============================================
// ADMIN PROTECTED ROUTES — Requires admin session
// ============================================

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Orders
    Route::put('/orders/{id}', [AdminController::class, 'updateOrder'])->name('orders.update');
    Route::delete('/orders/{id}', [AdminController::class, 'deleteOrder'])->name('orders.delete');

    // Messages
    Route::put('/messages/{id}', [MessageController::class, 'update'])->name('messages.update');

});