<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\DeliveryController;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Product Builder Routes
    Route::get('/menu', function () {
        return view('menu', ['products' => ProductItem::all()]);
    })->name('menu');

    Route::get('/build/{product}', function (ProductItem $product) {
        return view('build', ['product' => $product]);
    })->name('build');

    // Cart Routes
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
    Route::post('/cart/remove/{index}', [OrderController::class, 'removeFromCart'])->name('cart.remove');

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
    Route::post('/kitchen/ingredient/{ingredient}/toggle', [KitchenController::class, 'toggleStock'])->name('kitchen.toggleStock');

    // Campus Delivery Routes
    Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery.index');
    Route::patch('/delivery/{id}/status', [DeliveryController::class, 'updateStatus'])->name('delivery.updateStatus');

    // Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/ingredients', [\App\Http\Controllers\Admin\IngredientController::class, 'index'])->name('admin.ingredients');
    Route::post('/ingredients', [\App\Http\Controllers\Admin\IngredientController::class, 'store'])->name('admin.ingredients.store');
    Route::delete('/ingredients/{ingredient}', [\App\Http\Controllers\Admin\IngredientController::class, 'destroy'])->name('admin.ingredients.destroy');
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderOverviewController::class, 'index'])->name('admin.orders');
});
});

require __DIR__.'/auth.php';