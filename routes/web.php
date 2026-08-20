<?php

use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\OrderOverviewController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderReviewController;
use App\Http\Controllers\ProfileController;
use App\Livewire\CartView;
use App\Livewire\ProductBuilder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Dynamic Role-Based Redirection on Root Route
Route::get('/', function () {
    if (Auth::check()) {
        /** @var User $user */
        $user = Auth::user();
        $role = strtolower($user->role ?? '');

        if ($user->hasRole('admin') || $role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('kitchen') || $user->hasRole('staff') || in_array($role, ['kitchen', 'staff'], true)) {
            return redirect()->route('staff.dashboard');
        }

        if ($user->hasRole('delivery') || $user->hasRole('Delivery Staff') || in_array($role, ['delivery', 'delivery staff'], true)) {
            return redirect()->route('delivery.dashboard');
        }

        return redirect()->route('menu');
    }

    return redirect()->route('login');
});

// Logout Route
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');


// Authenticated Routes Group
Route::middleware('auth')->group(function () {

    // Default Customer Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');

    // Profile Management
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // Publicly Accessible Product Builder (Logged-in users)
    Route::get('/build/{product}', ProductBuilder::class)->name('customer.build');
    Route::get('/builder/{product}', ProductBuilder::class)->name('build');

    // Customer Protected Routes
    Route::middleware('role:customer')->group(function () {
        
        // Menu Routes
        Route::get('/menu', [CustomerMenuController::class, 'index'])->name('menu');
        Route::get('/customer/menu', [CustomerMenuController::class, 'index'])->name('customer.menu');

        // Cart Actions
        Route::controller(OrderController::class)->prefix('cart')->name('cart.')->group(function () {
            Route::post('/add', 'addToCart')->name('add');
            Route::patch('/update/{index}', 'updateCart')->name('update');
            Route::delete('/remove/{index}', 'removeFromCart')->name('remove');
            Route::delete('/clear', 'clearCart')->name('clear');
        });

        // Livewire Cart Interface
        Route::get('/cart', CartView::class)->name('cart');

        // Customer Orders & Reviews Group
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::controller(OrderController::class)->group(function () {
                Route::get('/my-orders', 'myOrders')->name('index');
                
                // Checkout Route (GET සහ POST දෙකම භාරගන්නා පරිදි Match කර ඇත)
                Route::match(['get', 'post'], '/create', 'create')->name('create');
                
                Route::post('/', 'store')->name('store');
                Route::get('/{order}', 'show')->name('show');
                Route::patch('/{order}/cancel', 'cancel')->name('cancel');
            });

            // Order Review Routes
            Route::get('/{order}/review', [OrderReviewController::class, 'create'])->name('review.create');
            Route::post('/{order}/review', [OrderReviewController::class, 'store'])->name('review.store');
        });
    });

    // Kitchen & Staff Operations
    Route::middleware('role:kitchen|staff')->prefix('kitchen')->name('kitchen.')->group(function () {
        Route::get('/', [KitchenController::class, 'index'])->name('index');
        Route::get('/dashboard', [KitchenController::class, 'index'])->name('dashboard');
        Route::patch('/orders/{order}/status', [KitchenController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/ingredient/{ingredient}/toggle', [KitchenController::class, 'toggleStock'])->name('toggleStock');
    });

    // Staff Direct Shortcut
    Route::get('/staff/dashboard', [KitchenController::class, 'index'])
        ->middleware('role:kitchen|staff')
        ->name('staff.dashboard');

    // Delivery Operations
    Route::middleware('role:delivery|Delivery Staff')->prefix('delivery')->name('delivery.')->group(function () {
        Route::get('/', [DeliveryController::class, 'index'])->name('index');
        Route::get('/dashboard', [DeliveryController::class, 'index'])->name('dashboard');
        Route::get('/daily-log', [DeliveryController::class, 'dailyLog'])->name('dailyLog');
        Route::patch('/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('updateStatus');
    });

    // Admin Control Panel
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        
        // Analytics & Finance
        Route::controller(OrderOverviewController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/money-management', 'dashboard')->name('money');
            Route::get('/orders', 'index')->name('orders');
            Route::patch('/orders/{order}/status', 'updateStatus')->name('orders.update-status');
            Route::patch('/orders/{order}/assign-driver', 'assignDriver')->name('orders.assign-driver');
        });

        // Ingredient Management
        Route::get('/ingredients/{ingredient}', function () {
            return redirect()->route('admin.ingredients.index');
        });
        Route::resource('ingredients', IngredientController::class)->except(['create', 'show', 'edit']);

        // Staff Management Resource
        Route::resource('staff', StaffController::class)->except(['create', 'show', 'edit']);
    });

});

require __DIR__ . '/auth.php';

// Global Fallback Route
Route::fallback(function () {
    return redirect('/');
});