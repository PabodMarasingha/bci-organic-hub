<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\Delivery;
use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class KitchenController extends Controller
{
    /**
     * Display live kitchen orders, ready orders, and ingredient stock management.
     */
    public function index(): View
    {
        // 1. Live Orders in progress (Pending, Processing, Preparing)
        $orders = CustomerOrder::with(['items', 'user'])
            ->whereIn('status', ['pending', 'processing', 'preparing'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Completed / Ready Orders
        $readyOrders = CustomerOrder::with(['items', 'user'])
            ->where('status', 'ready')
            ->orderBy('updated_at', 'desc')
            ->take(30)
            ->get();

        // 3. Ingredient Stock Data
        $ingredients = Ingredient::orderBy('name', 'asc')->get();

        return view('kitchen.index', compact('orders', 'readyOrders', 'ingredients'));
    }

    /**
     * Update order status, deduct ingredient stock, and sync delivery status.
     */
    public function updateStatus(Request $request, CustomerOrder $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,preparing,ready,out_for_delivery,delivered,cancelled',
        ]);

        $previousStatus = $order->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($order, $previousStatus, $newStatus) {
            // Update Order Status
            $order->update(['status' => $newStatus]);

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
                                    // Match ingredient in database
                                    $ingredient = Ingredient::where('name', 'LIKE', '%' . trim($ingredientName) . '%')->first();

                                    if ($ingredient && Schema::hasColumn('ingredients', 'quantity')) {
                                        $deductQty = $item->quantity ?? 1;
                                        $ingredient->decrement('quantity', $deductQty);

                                        // Set out of stock if quantity falls to zero or below
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
                    if (!$delivery) {
                        Delivery::create([
                            $orderCol  => $order->id,
                            $statusCol => 'unassigned',
                        ]);
                    } else {
                        // Ensure status is valid for Delivery Dashboard
                        if (in_array($delivery->{$statusCol}, ['pending', 'cancelled', null])) {
                            $delivery->update([$statusCol => 'unassigned']);
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

        return back()
            ->with('message', "Order #{$order->id} status updated to '{$newStatus}'.")
            ->with('success', "Order #{$order->id} status updated to '{$newStatus}'.");
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