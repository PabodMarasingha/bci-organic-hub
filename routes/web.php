<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    // Default Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Order Routes
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // Kitchen Dashboard Routes
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
    Route::patch('/kitchen/orders/{id}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.updateStatus');

    // Campus Delivery Routes
    Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery.index');
    Route::patch('/delivery/{id}/status', [DeliveryController::class, 'updateStatus'])->name('delivery.updateStatus');
});

require __DIR__.'/auth.php';