<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Schema;

class OrderOverviewController extends Controller
{
    public function index()
    {
        // Calculate Total Sales safely across different table schemas
        $totalSales = 0;
        if (Schema::hasColumn('orders', 'total_price')) {
            $totalSales = Order::where('status', 'completed')->sum('total_price');
        } elseif (Schema::hasColumn('orders', 'total')) {
            $totalSales = Order::where('status', 'completed')->sum('total');
        } elseif (Schema::hasColumn('orders', 'price')) {
            $totalSales = Order::where('status', 'completed')->sum('price');
        } else {
            // Fallback if price column is named differently
            $totalSales = Order::where('status', 'completed')->get()->sum(function($order) {
                return $order->price ?? $order->total_price ?? $order->total ?? 0;
            });
        }

        // Stats Calculations
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $kitchenOrders = Order::where('status', 'preparing')->count();
        $deliveryOrders = Order::where('status', 'out_for_delivery')->count();

        // System User Counts
        $usersCount = [
            'customers' => User::where('role', 'customer')->count(),
            'kitchen'   => User::where('role', 'kitchen')->count(),
            'delivery'  => User::where('role', 'delivery')->count(),
            'admin'     => User::where('role', 'admin')->count(),
        ];

        // Fetch Orders, Users & Ingredients
        $orders = Order::with('user')->latest()->get();
        $users = User::latest()->get();
        $ingredients = Ingredient::all();

        return view('admin.orders', compact(
            'totalSales',
            'totalOrders',
            'pendingOrders',
            'kitchenOrders',
            'deliveryOrders',
            'usersCount',
            'orders',
            'users',
            'ingredients'
        ));
    }
}