<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
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

    // Dashboard Redirection based on Role
    Route::get('/dashboard', function () {
        /** @var \App\Models\User|null $user */
        $user = Auth::user(); 
        $role = $user->role ?? 'customer';

        return match ($role) {
            'admin'    => redirect()->route('admin.orders'),
            'kitchen'  => redirect()->route('kitchen.index'),
            'delivery' => redirect()->route('delivery.index'),
            default    => view('dashboard'), // Customer Dashboard
        };
    })->name('dashboard');

    // ----------------------------------------------------
    // 1. CUSTOMER ROUTES
    // ----------------------------------------------------
    Route::middleware('role:customer')->group(function () {

        // Dynamic Menu Route
        Route::get('/menu', [OrderController::class, 'menu'])->name('menu');

        // Product Customization / Single Product Details Page
        Route::get('/build/{product}', function (ProductItem $product) {
            return view('build', ['product' => $product->load('ingredients')]);
        })->name('build');

        // Alias for easy linking: route('products.show', $product) -> redirects to build page
        Route::get('/menu/{product}', function (ProductItem $product) {
            return redirect()->route('build', $product);
        })->name('products.show');

        // Cart Management Routes
        Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
        Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');
        Route::patch('/cart/update/{index}', [OrderController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/remove/{index}', [OrderController::class, 'removeFromCart'])->name('cart.remove');

        // Order Placement & History Routes
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.index');
        
        // Cancel Order Route
        Route::match(['get', 'post', 'patch'], '/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        
        // Dynamic Order ID View
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