<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        
        if (!$user) {
            return redirect()->route('login');
        }

        

        if ($user->hasAnyRole(['admin', 'Admin'])) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasAnyRole(['kitchen', 'Kitchen Staff', 'staff'])) {
            return redirect()->route('staff.dashboard');
        }

        if ($user->hasAnyRole(['delivery', 'Delivery Staff'])) {
            return redirect()->route('delivery.dashboard');
        }

        

        $userId = $user->id;
        $activeStatuses = ['pending', 'preparing', 'ready', 'on_delivery', 'processing'];

        // Base Query
        $ordersQuery = CustomerOrder::where('user_id', $userId);

        // Total Orders Count
        $totalOrdersCount = (clone $ordersQuery)->count();

        // Active Orders Query (Cleaner Case-insensitive matching)
        $activeQuery = (clone $ordersQuery)->whereIn(DB::raw('LOWER(status)'), $activeStatuses);

        // Active Orders Count
        $activeOrdersCount = (clone $activeQuery)->count();

        // Latest Active Order
        $latestActiveOrder = (clone $activeQuery)
            ->orderBy('id', 'desc')
            ->first();

        // Recent 5 Orders
        $recentOrders = (clone $ordersQuery)
            ->with('items')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Cart Item Count from Session
        $cart = session()->get('cart', []);
        $cartCount = is_array($cart) ? count($cart) : 0;

        return view('customers.dashboard', compact(
            'user',
            'totalOrdersCount',
            'activeOrdersCount',
            'latestActiveOrder',
            'recentOrders',
            'cartCount'
        ));
    }
}