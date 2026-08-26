<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\Delivery;
use App\Models\Ingredient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class KitchenController extends Controller
{
    /**
     * Display live kitchen orders, ready orders, ingredient stock, metrics, and drivers.
     */
    public function index(): View
    {
        // 1. Check if 'driver' relationship exists on CustomerOrder model to avoid RelationNotFoundException
        $orderRelations = ['items', 'user'];
        if (method_exists(CustomerOrder::class, 'driver')) {
            $orderRelations[] = 'driver';
        }

        // Live Orders in progress (Pending, Processing, Preparing)
        $orders = CustomerOrder::with($orderRelations)
            ->whereIn('status', ['pending', 'processing', 'preparing'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Completed / Ready Orders
        $readyOrders = CustomerOrder::with($orderRelations)
            ->where('status', 'ready')
            ->orderBy('updated_at', 'desc')
            ->take(30)
            ->get();

        // 3. Ingredient Stock Data
        $ingredients = Ingredient::orderBy('name', 'asc')->get();

        // 4. Active Delivery Drivers List
        $drivers = User::whereIn('role', ['driver', 'delivery'])->get();

        // 5. Kitchen Analytics & Order Metrics Calculation
        $todayOrdersCount = CustomerOrder::whereDate('created_at', Carbon::today())->count();

        // Average Preparation Time Calculation for 'ready' orders today
        $completedToday = CustomerOrder::whereDate('created_at', Carbon::today())
            ->where('status', 'ready')
            ->get();

        $avgPrepTime = 0;
        if ($completedToday->count() > 0) {
            $totalMinutes = $completedToday->sum(function ($order) {
                return Carbon::parse($order->created_at)->diffInMinutes(Carbon::parse($order->updated_at));
            });
            $avgPrepTime = (int) round($totalMinutes / $completedToday->count());
        }

        // Out of Stock Ingredients Count
        $outOfStockCount = Ingredient::where('in_stock', false)->count();

        return view('kitchen.index', compact(
            'orders', 
            'readyOrders', 
            'ingredients', 
            'drivers', 
            'todayOrdersCount', 
            'avgPrepTime', 
            'outOfStockCount'
        ));
    }

    /**
     * Update order status, assign delivery driver, deduct stock, and sync delivery status.
     */
    public function updateStatus(Request $request, CustomerOrder $order): RedirectResponse
    {
        $validated = $request->validate([
            'status'    => 'required|string|in:pending,processing,preparing,ready,out_for_delivery,delivered,cancelled',
            'driver_id' => 'nullable|exists:users,id',
        ]);

        $previousStatus = $order->status;
        $newStatus      = $validated['status'];
        $driverId       = $request->input('driver_id');

        DB::transaction(function () use ($order, $previousStatus, $newStatus, $driverId) {
            // Data array to update order
            $updateData = ['status' => $newStatus];

            // Assign driver if provided and column exists
            if ($driverId && Schema::hasColumn('customer_orders', 'driver_id')) {
                $updateData['driver_id'] = $driverId;
                
                // Update Driver status to 'on_delivery'
                if (Schema::hasColumn('users', 'driver_status')) {
                    User::where('id', $driverId)->update(['driver_status' => 'on_delivery']);
                }
            }

            // Update Customer Order
            $order->update($updateData);

            // Deduct ingredient stock automatically when order transitions to 'ready'
            if ($newStatus === 'ready' && $previousStatus !== 'ready') {
                foreach ($order->items as $item) {
                    $customizations = $item->customizations;

                    if (!empty($customizations)) {
                        if (is_string($customizations)) {
                            $customizations = json_decode($customizations, true) ?? [];
                        }

                        if (is_array($customizations) || is_object($customizations)) {
                            foreach ($customizations as $c) {
                                $ingredientName = null;

                                if (is_array($c)) {
                                    $ingredientName = $c['name'] ?? $c['title'] ?? null;
                                } elseif (is_object($c)) {
                                    $ingredientName = $c->name ?? $c->title ?? null;
                                } elseif (is_string($c)) {
                                    $ingredientName = $c;
                                }

                                if ($ingredientName) {
                                    $ingredient = Ingredient::where('name', 'LIKE', '%' . trim($ingredientName) . '%')->first();

                                    if ($ingredient && Schema::hasColumn('ingredients', 'quantity')) {
                                        $deductQty = $item->quantity ?? 1;
                                        $ingredient->decrement('quantity', $deductQty);

                                        if ($ingredient->fresh()->quantity <= 0 && Schema::hasColumn('ingredients', 'in_stock')) {
                                            $ingredient->update(['in_stock' => false]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Sync Delivery Model
            if (class_exists(Delivery::class)) {
                $statusCol = Schema::hasColumn('deliveries', 'delivery_status') ? 'delivery_status' : 'status';
                $orderCol  = Schema::hasColumn('deliveries', 'customer_order_id') ? 'customer_order_id' : 'order_id';

                $delivery = Delivery::where($orderCol, $order->id)->first();

                if ($newStatus === 'ready') {
                    $deliveryData = [
                        $orderCol  => $order->id,
                        $statusCol => 'unassigned',
                    ];

                    if ($driverId && Schema::hasColumn('deliveries', 'driver_id')) {
                        $deliveryData['driver_id'] = $driverId;
                        $deliveryData[$statusCol]  = 'assigned';
                    }

                    if (!$delivery) {
                        Delivery::create($deliveryData);
                    } else {
                        if (in_array($delivery->{$statusCol}, ['pending', 'unassigned', 'cancelled', null])) {
                            $delivery->update($deliveryData);
                        }
                    }
                } elseif ($newStatus === 'out_for_delivery') {
                    if ($delivery) {
                        $delivery->update([$statusCol => 'out_for_delivery']);
                    }
                } elseif ($newStatus === 'cancelled') {
                    if ($delivery) {
                        $delivery->update([$statusCol => 'cancelled']);
                    }
                }
            }
        });

        $formattedStatus = strtoupper(str_replace('_', ' ', $newStatus));

        return back()
            ->with('message', "Order #{$order->id} status updated to '{$formattedStatus}'.")
            ->with('success', "Order #{$order->id} status updated to '{$formattedStatus}'.");
    }

    /**
     * Toggle the in_stock status of an ingredient.
     */
    public function toggleStock(Ingredient $ingredient): RedirectResponse
    {
        $ingredient->update([
            'in_stock' => !$ingredient->in_stock,
        ]);

        $statusText = $ingredient->in_stock ? 'In Stock' : 'Out of Stock';

        return back()
            ->with('message', "Ingredient '{$ingredient->name}' marked as {$statusText}.")
            ->with('success', "Ingredient '{$ingredient->name}' marked as {$statusText}.");
    }
}