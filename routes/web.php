<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\OrderOverviewController;
use App\Http\Controllers\Admin\StaffController;
use App\Livewire\ProductBuilder;
use App\Livewire\CartView;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Root Route
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Default Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer-only Routes
    Route::middleware('role:customer')->group(function () {
        Route::get('/menu', [OrderController::class, 'menu'])->name('menu');
        
        // Livewire Custom Meal Builder Route
        Route::get('/build/{product}', ProductBuilder::class)->name('build');

        // Livewire Cart View Route
        Route::get('/cart', CartView::class)->name('cart');

        // Orders Routes
        Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        
        // Cancel Route (Placed above {id} route to avoid route collisions)
        Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    });

    // Kitchen-only Routes
    Route::middleware('role:kitchen')->group(function () {
        Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
        Route::patch('/kitchen/orders/{id}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.updateStatus');
        Route::post('/kitchen/ingredient/{ingredient}/toggle', [KitchenController::class, 'toggleStock'])->name('kitchen.toggleStock');
    });

    // Delivery-only Routes
    Route::middleware('role:delivery')->group(function () {
        Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery.index');
        Route::patch('/delivery/{id}/status', [DeliveryController::class, 'updateStatus'])->name('delivery.updateStatus');
    });

    // Admin-only Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [OrderOverviewController::class, 'index'])->name('dashboard');
        
        Route::get('/ingredients', [IngredientController::class, 'index'])->name('ingredients');
        Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
        Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');
        
        Route::get('/orders', [OrderOverviewController::class, 'index'])->name('orders');
        
        Route::get('/staff', [StaffController::class, 'index'])->name('staff');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');
    });
});

require __DIR__.'/auth.php';