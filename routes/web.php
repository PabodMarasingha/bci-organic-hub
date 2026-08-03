<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\OrderOverviewController;
use App\Models\ProductItem;

// Home Page - Redirect to Login
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

        // Dynamic Menu Route
        Route::get('/menu', [OrderController::class, 'menu'])->name('menu');

        // Product Customization Route
        Route::get('/build/{product}', function (ProductItem $product) {
            return view('build', ['product' => $product->load('ingredients')]);
        })->name('build');

        // Cart Management Routes
        Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
        Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');
        Route::patch('/cart/update/{index}', [OrderController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/remove/{index}', [OrderController::class, 'removeFromCart'])->name('cart.remove');

        // Order Placement & History Routes
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.index');
        
        // ⚠️ Cancel Route එක (GET, POST, PATCH තුනටම සහය දක්වයි)
        Route::match(['get', 'post', 'patch'], '/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        
        // Dynamic ID Route (සමස්ත /orders/{id} එක පහළින් තිබිය යුතුය)
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
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
        Route::get('/ingredients', [IngredientController::class, 'index'])->name('ingredients');
        Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
        Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');
        Route::get('/orders', [OrderOverviewController::class, 'index'])->name('orders');
    });

});

require __DIR__.'/auth.php';