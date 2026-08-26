<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    /**
     * Get dynamic status column name based on database schema.
     */
    private function getStatusColumn(): string
    {
        return Schema::hasColumn('deliveries', 'delivery_status') ? 'delivery_status' : 'status';
    }

    /**
     * Display active deliveries & daily metrics for driver dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $statusColumn = $this->getStatusColumn();

        
        $zoneId = $user->delivery_zone_id ?? $user->deliveryProfile->delivery_zone_id ?? null;

        
        $query = Delivery::with([
            'customerOrder.user', 
            'customerOrder.deliveryZone', 
            'customerOrder.items', 
            'customerOrder.payment',
            'customerOrder.review',
            'order.user', 
            'order.deliveryZone', 
            'order.items',
            'order.payment',
            'order.review'
        ])
        ->whereIn($statusColumn, ['unassigned', 'assigned', 'picked_up', 'out_for_delivery', 'pending']);

        
        if (Schema::hasColumn('deliveries', 'driver_id')) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('driver_id')
                  ->orWhere('driver_id', $user->id);
            });
        }

        
        if ($zoneId) {
            $query->where(function ($q) use ($zoneId) {
                $q->whereHas('customerOrder', function ($sub) use ($zoneId) {
                    $sub->where('delivery_zone_id', $zoneId);
                })
                ->orWhereHas('order', function ($sub) use ($zoneId) {
                    $sub->where('delivery_zone_id', $zoneId);
                });
            });
        }

        $deliveries = $query->latest()->get();

        
        $todayDeliveriesQuery = Delivery::where($statusColumn, 'delivered')
            ->whereDate('delivered_at', today());

        if (Schema::hasColumn('deliveries', 'driver_id')) {
            $todayDeliveriesQuery->where('driver_id', $user->id);
        }

        $completedTodayCount = $todayDeliveriesQuery->count();

        
        $totalCollectedToday = $todayDeliveriesQuery->get()->sum(function ($delivery) {
            $order = $delivery->customerOrder ?? $delivery->order;
            if (!$order) return 0;

            return $order->total_amount ?? $order->total_price ?? $order->grand_total ?? 0;
        });

        return view('delivery.index', compact('deliveries', 'completedTodayCount', 'totalCollectedToday'));
    }

    /**
     * Display completed delivery log history for driver (Daily Log).
     */
    public function dailyLog(Request $request): View
    {
        $user = $request->user();
        $statusColumn = $this->getStatusColumn();

        $query = Delivery::with([
            'customerOrder.user', 
            'customerOrder.deliveryZone', 
            'customerOrder.items', 
            'customerOrder.payment',
            'customerOrder.review',
            'order.user', 
            'order.deliveryZone', 
            'order.items',
            'order.payment',
            'order.review'
        ])
        ->where($statusColumn, 'delivered');

        
        if ($user && Schema::hasColumn('deliveries', 'driver_id')) {
            $query->where('driver_id', $user->id);
        }

        $deliveries = $query->latest('delivered_at')->get();

        
        $totalCollectedToday = $deliveries->filter(function ($delivery) {
            if (!$delivery->delivered_at) return false;
            return \Carbon\Carbon::parse($delivery->delivered_at)->isToday();
        })->sum(function ($delivery) {
            $order = $delivery->customerOrder ?? $delivery->order;
            if (!$order) return 0;

            return $order->total_amount ?? $order->total_price ?? $order->grand_total ?? 0;
        });

        // 2. COD Cash පමණක් වෙනම Calculate කිරීම (අවශ්‍ය වුවහොත්)
        $codCollectedToday = $deliveries->filter(function ($delivery) {
            if (!$delivery->delivered_at) return false;
            return \Carbon\Carbon::parse($delivery->delivered_at)->isToday();
        })->sum(function ($delivery) {
            $order = $delivery->customerOrder ?? $delivery->order;
            if (!$order) return 0;

            $paymentMethod = $order->payment->payment_method ?? $order->payment_method ?? $order->payment_type ?? '';
            $isCod = in_array(strtolower($paymentMethod), ['cod', 'cash', 'cash_on_delivery']);

            return $isCod ? ($order->total_amount ?? $order->total_price ?? $order->grand_total ?? 0) : 0;
        });

        return view('delivery.daily-log', compact('deliveries', 'totalCollectedToday', 'codCollectedToday'));
    }

    /**
     * Update delivery status, sync parent order status, collect cash & add points.
     */
    public function updateStatus(Request $request, Delivery $delivery): RedirectResponse
    {
        $statusInput = $request->input('delivery_status', $request->input('status'));

        $request->merge(['status_to_validate' => $statusInput]);
        
        $request->validate([
            'status_to_validate' => 'required|in:unassigned,assigned,picked_up,out_for_delivery,delivered',
        ]);

        $user = $request->user();
        $statusColumn = $this->getStatusColumn();

       
        DB::transaction(function () use ($delivery, $statusInput, $statusColumn, $user) {
            $updateData = [
                $statusColumn  => $statusInput,
                'delivered_at' => $statusInput === 'delivered' ? now() : $delivery->delivered_at,
            ];

            // Driver Assign කිරීම
            if (in_array($statusInput, ['picked_up', 'out_for_delivery', 'assigned', 'delivered']) && $user) {
                if (Schema::hasColumn('deliveries', 'driver_id')) {
                    $updateData['driver_id'] = $user->id;
                }
            }

            $delivery->update($updateData);

            
            $order = $delivery->customerOrder ?? $delivery->order;

            if ($order) {
                $orderTable = $order->getTable();

                if (in_array($statusInput, ['picked_up', 'out_for_delivery'])) {
                    if (Schema::hasColumn($orderTable, 'order_status')) {
                        $order->update(['order_status' => 'out_for_delivery']);
                    } elseif (Schema::hasColumn($orderTable, 'status')) {
                        $order->update(['status' => 'out_for_delivery']);
                    }
                } elseif ($statusInput === 'delivered') {
                    $paymentMethod = $order->payment->payment_method ?? $order->payment_method ?? $order->payment_type ?? '';
                    $isCod = in_array(strtolower($paymentMethod), ['cod', 'cash', 'cash_on_delivery']);

                    $orderUpdates = [];
                    
                    if (Schema::hasColumn($orderTable, 'order_status')) {
                        $orderUpdates['order_status'] = 'completed';
                    } elseif (Schema::hasColumn($orderTable, 'status')) {
                        $orderUpdates['status'] = 'completed';
                    }

                    
                    if ($isCod && Schema::hasColumn($orderTable, 'is_cash_collected')) {
                        $orderUpdates['is_cash_collected'] = true;
                    }

                    $order->update($orderUpdates);

                    
                    if (method_exists($order, 'payment') && $order->payment) {
                        $order->payment->update(['payment_status' => 'paid']);
                    }

                    
                    if ($user) {
                        if (Schema::hasColumn('users', 'points')) {
                            $user->increment('points', 10);
                        } elseif (method_exists($user, 'deliveryProfile') && $user->deliveryProfile && Schema::hasColumn('delivery_drivers', 'points')) {
                            $user->deliveryProfile->increment('points', 10);
                        }
                    }
                }
            }
        });

        return back()->with('message', 'Delivery status successfully updated!');
    }
}