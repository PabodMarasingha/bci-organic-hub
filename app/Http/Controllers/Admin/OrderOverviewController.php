<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\Delivery;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderOverviewController extends Controller
{
   
    public function dashboard()
    {
        
        $totalRevenue = CustomerOrder::where('status', '!=', 'cancelled')->sum('total_amount');
        $netProfit = $totalRevenue * 0.33; // 33% Profit Estimation
        $pendingOrders = CustomerOrder::where('status', 'pending')->count();
        
        
        $lowStockCount = Schema::hasColumn('ingredients', 'quantity')
            ? Ingredient::where('quantity', '<=', 10)->count()
            : Ingredient::where('is_available', false)->count();

        
        $recentOrders = CustomerOrder::with(['user', 'items', 'deliveryZone', 'delivery', 'payment'])
            ->latest()
            ->take(10)
            ->get();

       
        $drivers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['delivery', 'Delivery Staff', 'driver', 'Driver']);
        })->get();

        
        $cardPaymentCount = CustomerOrder::whereHas('payment', function ($q) {
            $q->where('payment_method', 'card');
        })->count();

        $codPaymentCount = CustomerOrder::whereHas('payment', function ($q) {
            $q->where('payment_method', 'cod');
        })->count();

        
        $startDate = now()->subDays(6)->startOfDay();
        $revenueGrouped = CustomerOrder::where('created_at', '>=', $startDate)
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $weeklyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $weeklyRevenue[] = (float) ($revenueGrouped[$date] ?? 0);
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'netProfit',
            'pendingOrders',
            'lowStockCount',
            'recentOrders',
            'drivers',
            'cardPaymentCount',
            'codPaymentCount',
            'weeklyRevenue'
        ));
    }

    
    public function index(Request $request)
    {
        $query = CustomerOrder::with(['user', 'items', 'deliveryZone', 'delivery.driver', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        $totalSales = CustomerOrder::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = CustomerOrder::count();
        $pendingOrders = CustomerOrder::where('status', 'pending')->count();

        
        $deliveryDrivers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['delivery', 'Delivery Staff', 'driver', 'Driver']);
        })->get();

        return view('admin.orders', compact('orders', 'totalSales', 'totalOrders', 'pendingOrders', 'deliveryDrivers'));
    }

    
    public function updateStatus(Request $request, CustomerOrder $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,ready,out_for_delivery,completed,cancelled',
        ]);

        $order->update([
            'status' => $validated['status']
        ]);

        if (in_array($validated['status'], ['ready', 'out_for_delivery'])) {
            Delivery::firstOrCreate(
                ['customer_order_id' => $order->id],
                [
                    'delivery_status' => 'unassigned',
                    'dropoff_location' => $order->dropoff_location ?? 'N/A',
                    'dropoff_address'  => $order->dropoff_address ?? $order->shipping_address ?? 'No address provided',
                    'driver_id'        => null,
                ]
            );
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    public function assignDriver(Request $request, CustomerOrder $order)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        Delivery::updateOrCreate(
            ['customer_order_id' => $order->id],
            [
                'driver_id'        => $validated['driver_id'],
                'delivery_status'  => 'assigned',
                'dropoff_location' => $order->dropoff_location ?? 'N/A',
                'dropoff_address'  => $order->dropoff_address ?? $order->shipping_address ?? 'No address provided',
            ]
        );

        $order->update([
            'status' => 'out_for_delivery'
        ]);

        return back()->with('success', 'Delivery driver assigned successfully!');
    }
}