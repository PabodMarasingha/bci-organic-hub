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

        // Drivers Zone ID ලබා ගැනීම
        $zoneId = $user->delivery_zone_id ?? $user->deliveryProfile->delivery_zone_id ?? null;

        // Active Deliveries Query කිරීම (Review / Ratings එකතු කර ඇත)
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

        // Driver assign වී නැති හෝ මෙම Driver ට assign වූ Orders පමණක් Filter කිරීම
        if (Schema::hasColumn('deliveries', 'driver_id')) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('driver_id')
                  ->orWhere('driver_id', $user->id);
            });
        }

        // Zone ID එකක් තිබේ නම් පමණක් Zone filter කිරීම
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

        // අද දින Delivered කරන ලද දත්ත Query කිරීම
        $todayDeliveriesQuery = Delivery::where($statusColumn, 'delivered')
            ->whereDate('delivered_at', today());

        if (Schema::hasColumn('deliveries', 'driver_id')) {
            $todayDeliveriesQuery->where('driver_id', $user->id);
        }

        $completedTodayCount = $todayDeliveriesQuery->count();

        // අද දින එකතු කළ මුළු මුදල (Card + COD සියල්ලම එකතු කිරීම)
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

        // Driver ට අදාළ Log පමණක් Filter කිරීම
        if ($user && Schema::hasColumn('deliveries', 'driver_id')) {
            $query->where('driver_id', $user->id);
        }

        $deliveries = $query->latest('delivered_at')->get();

        // 1. අද දින Delivered කළ සියලුම Order වල එකතුව (Card + COD)
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

        // DB Transaction එකක් ඇතුළත Updates සිදු කිරීම
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

            // Parent Order එක Synchronize කිරීම
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

                    // COD නම් Cash Collected කරගත් බව Mark කිරීම
                    if ($isCod && Schema::hasColumn($orderTable, 'is_cash_collected')) {
                        $orderUpdates['is_cash_collected'] = true;
                    }

                    $order->update($orderUpdates);

                    // Order Payment Status එක 'paid' ලෙස වෙනස් කිරීම
                    if (method_exists($order, 'payment') && $order->payment) {
                        $order->payment->update(['payment_status' => 'paid']);
                    }

                    // Driver ට Points එකතු කිරීම (+10 Points)
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