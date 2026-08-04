<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DeliveryController extends Controller
{
    /**
     * Display the delivery dashboard with orders.
     */
    public function index()
    {
        // 1. Database එකේ තියෙන සියලුම Orders (Status ලිහිල් කර පරීක්ෂා කිරීමට)
        // 'delivered' නොවන සියලුම Orders පෙන්නුම් කරයි
        $orders = CustomerOrder::where('status', '!=', 'delivered')
            ->latest()
            ->get();

        // 2. Completed Orders (Delivery History)
        $historyOrders = CustomerOrder::where('status', 'delivered')
            ->latest('updated_at')
            ->take(30)
            ->get();

        // 3. Stats Counts
        $readyCount = CustomerOrder::whereIn('status', ['ready', 'preparing', 'pending'])->count();
        $outForDeliveryCount = CustomerOrder::where('status', 'out_for_delivery')->count();
        
        $deliveredTodayCount = CustomerOrder::where('status', 'delivered')
            ->whereDate('updated_at', today())
            ->count();

        // 4. Today Earnings Calculation
        if (Schema::hasColumn('customer_orders', 'delivery_fee')) {
            $todayEarnings = CustomerOrder::where('status', 'delivered')
                ->whereDate('updated_at', today())
                ->sum('delivery_fee');
        } else {
            $todayEarnings = $deliveredTodayCount * 350.00;
        }

        return view('delivery.index', compact(
            'orders', 
            'historyOrders', 
            'readyCount', 
            'outForDeliveryCount', 
            'deliveredTodayCount',
            'todayEarnings'
        ));
    }

    /**
     * Update order delivery status.
     */
    public function updateStatus(Request $request, CustomerOrder $order)
    {
        $request->validate([
            'status' => 'required|in:out_for_delivery,delivered',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        $statusText = $request->status == 'out_for_delivery' ? 'Out for Delivery 🚚' : 'Delivered ✅';

        return redirect()->back()->with('message', "Order #ORD-{$order->id} status updated to {$statusText}");
    }
}