<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Active order statuses
        $activeStatuses = ['pending', 'preparing', 'ready', 'on_delivery', 'processing'];

        // Base query using CustomerOrder model
        $ordersQuery = CustomerOrder::where('user_id', $userId);

        // 1. Total Orders Count
        $totalOrdersCount = (clone $ordersQuery)->count();

        // 2. Active Orders Query (Case-insensitive status matching)
        $activeQuery = (clone $ordersQuery)->where(function ($query) use ($activeStatuses) {
            foreach ($activeStatuses as $status) {
                $query->orWhereRaw('LOWER(status) = ?', [strtolower($status)]);
            }
        });

        // Active Orders Count
        $activeOrdersCount = (clone $activeQuery)->count();

        // 3. Latest Active Order
        $latestActiveOrder = (clone $activeQuery)
            ->orderBy('id', 'desc')
            ->first();

        // 4. Recent 5 Orders 
        $recentOrders = (clone $ordersQuery)
            ->with('items')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // 5. Cart count from session
        $cart = session()->get('cart', []);
        $cartCount = is_array($cart) ? count($cart) : 0;

        return view('dashboard', compact(
            'totalOrdersCount',
            'activeOrdersCount',
            'latestActiveOrder',
            'recentOrders',
            'cartCount'
        ));
    }
}