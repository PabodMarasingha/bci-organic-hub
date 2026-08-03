<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\OrderOverviewController;
use App\Models\ProductItem;

// Home Page
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated Common Routes (All Logged-in Users)
Route::middleware('auth')->group(function () {
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ----------------------------------------------------
    // 1. CUSTOMER ROUTES
    // ----------------------------------------------------
    Route::middleware('role:customer')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/menu', function () {
            return view('menu', ['products' => ProductItem::all()]);
        })->name('menu');

        Route::get('/build/{product}', function (ProductItem $product) {
            return view('build', ['product' => $product]);
        })->name('build');

        // Cart & Order Routes
        Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
        Route::post('/cart/remove/{index}', [OrderController::class, 'removeFromCart'])->name('cart.remove');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.index');
    });

    // ----------------------------------------------------
    // 2. KITCHEN STAFF ROUTES
    // ----------------------------------------------------
    Route::middleware('role:kitchen')->group(function () {
        Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
        Route::patch('/kitchen/orders/{id}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.updateStatus');
        Route::post('/kitchen/ingredient/{ingredient}/toggle', [KitchenController::class, 'toggleStock'])->name('kitchen.toggleStock');
    });

    // ----------------------------------------------------
    // 3. DELIVERY STAFF ROUTES
    // ----------------------------------------------------
    Route::middleware('role:delivery')->group(function () {
        Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery.index');
        Route::patch('/delivery/{id}/status', [DeliveryController::class, 'updateStatus'])->name('delivery.updateStatus');
    });

    // ----------------------------------------------------
    // 4. ADMIN ROUTES
    // ----------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/ingredients', [IngredientController::class, 'index'])->name('ingredients.index');
        Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
        Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');
        Route::get('/orders', [OrderOverviewController::class, 'index'])->name('orders.index');
    });

});

require __DIR__.'/auth.php';