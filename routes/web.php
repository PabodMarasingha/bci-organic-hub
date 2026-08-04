<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\OrderOverviewController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\ProductItem;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Home Page - Log වී නොමැති නම් Direct Login Interface එක පෙන්වයි
Route::get('/', function () {
    if (!Auth::check()) {
        return view('auth.login');
    }
    return redirect()->route('dashboard');
});

// 2. Direct GET Logout Route (Browser එකෙන් logout ගැසූ විට error එක නොපෙන්වීමට)
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('force.logout');

// 3. Quick Auto-Login Route
Route::get('/quick-login/{role}', [AuthenticatedSessionController::class, 'quickLogin'])->name('quick.login');

// Authenticated Common Routes (All Logged-in Users)
Route::middleware('auth')->group(function () {

    // Profile Management
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Role-based Dashboard Redirection
    Route::get('/dashboard', function () {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return match ($user?->role) {
            'admin'    => redirect()->route('admin.orders'),
            'kitchen'  => redirect()->route('kitchen.index'),
            'delivery' => redirect()->route('delivery.index'),
            default    => view('dashboard'), // Customer Dashboard
        };
    })->name('dashboard');

    // ====================================================
    // 1. CUSTOMER ROUTES
    // ====================================================
    Route::middleware('role:customer')->group(function () {

        // Menu & Customization
        Route::get('/menu', [OrderController::class, 'menu'])->name('menu');

        Route::get('/build/{product}', function (ProductItem $product) {
            return view('build', ['product' => $product->load('ingredients')]);
        })->name('build');

        Route::get('/menu/{product}', function (ProductItem $product) {
            return redirect()->route('build', $product);
        })->name('products.show');

        // Cart Management & Orders
        Route::controller(OrderController::class)->group(function () {
            Route::get('/cart', 'cart')->name('cart');
            Route::post('/cart/add', 'addToCart')->name('cart.add');
            Route::patch('/cart/update/{index}', 'updateCart')->name('cart.update');
            Route::delete('/cart/remove/{index}', 'removeFromCart')->name('cart.remove');

            // Order Processing & History
            Route::get('/orders/create', 'create')->name('orders.create');
            Route::post('/orders', 'store')->name('orders.store');
            Route::get('/orders', 'myOrders')->name('orders.index');
            Route::get('/orders/{id}', 'show')->name('orders.show');

            // Order Cancellation
            Route::match(['get', 'post', 'patch'], '/orders/{id}/cancel', 'cancel')->name('orders.cancel');
        });
    });

    // ====================================================
    // 2. KITCHEN STAFF ROUTES
    // ====================================================
    Route::middleware('role:kitchen,admin')->prefix('kitchen')->name('kitchen.')->controller(KitchenController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/orders/{id}/status', 'updateStatus')->name('orders.updateStatus');
        Route::match(['post', 'patch'], '/ingredients/{ingredient}/toggle', 'toggleStock')->name('ingredients.toggle');
    });

    // ====================================================
    // 3. DELIVERY STAFF ROUTES
    // ====================================================
    Route::middleware('role:delivery,admin')->prefix('delivery')->name('delivery.')->controller(DeliveryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/{order}/status', 'updateStatus')->name('updateStatus');
    });

    // ====================================================
    // 4. ADMIN ROUTES
    // ====================================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        
        // Ingredient Management
        Route::controller(IngredientController::class)->group(function () {
            Route::get('/ingredients', 'index')->name('ingredients');
            Route::post('/ingredients', 'store')->name('ingredients.store');
            Route::delete('/ingredients/{ingredient}', 'destroy')->name('ingredients.destroy');
        });

        // Admin Order Overview
        Route::get('/orders', [OrderOverviewController::class, 'index'])->name('orders');
    });

});

require __DIR__.'/auth.php';