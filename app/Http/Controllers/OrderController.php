<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\DeliveryZone;
use App\Models\Ingredient;
use App\Models\ProductItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display the healthy menu items.
     */
    public function menu()
    {
        $products = ProductItem::with('ingredients')->latest()->get();

        return view('customers.menu', [
            'products' => $products,
            'meals'    => $products,
        ]);
    }

    /**
     * Display the custom meal builder for a specific product.
     */
    public function build(ProductItem $product)
    {
        $ingredients = Ingredient::all();
        return view('customers.build', compact('product', 'ingredients'));
    }

    /**
     * Add custom or standard meal to session cart and redirect straight to cart page.
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id'           => 'required|exists:product_items,id',
            'quantity'             => 'nullable|integer|min:1',
            'ingredients'          => 'nullable|array',
            'ingredients.*'        => 'exists:ingredients,id',
            'special_instructions' => 'nullable|string|max:255',
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $product = ProductItem::findOrFail($validated['product_id']);
        
        $ingredientIds = array_map('intval', $validated['ingredients'] ?? []);
        sort($ingredientIds);

        $selectedIngredients = Ingredient::whereIn('id', $ingredientIds)->get();

        $extraPrice = $selectedIngredients->sum('price');
        $unitPrice = (float) $product->price + $extraPrice;

        $customizations = $selectedIngredients->map(function ($ingredient) {
            return [
                'id'    => $ingredient->id,
                'name'  => $ingredient->name,
                'price' => (float) $ingredient->price,
            ];
        })->toArray();

        $cart = Session::get('cart', []);

        $foundKey = null;
        foreach ($cart as $key => $cartItem) {
            $existingCustomizationIds = collect($cartItem['customizations'] ?? [])
                ->pluck('id')
                ->map(fn($id) => (int)$id)
                ->sort()
                ->values()
                ->toArray();
            
            if (
                (int)$cartItem['product_id'] === (int)$product->id &&
                $existingCustomizationIds === array_values($ingredientIds) &&
                ($cartItem['special_instructions'] ?? null) === ($validated['special_instructions'] ?? null)
            ) {
                $foundKey = $key;
                break;
            }
        }

        if ($foundKey !== null) {
            $cart[$foundKey]['quantity'] += $quantity;
        } else {
            $cart[] = [
                'product_id'           => $product->id,
                'item_name'            => $product->name,
                'quantity'             => $quantity,
                'unit_price'           => $unitPrice,
                'customizations'       => $customizations,
                'special_instructions' => $validated['special_instructions'] ?? null,
            ];
        }

        Session::put('cart', $cart);

        return redirect()->route('cart')->with('message', 'Item added to cart successfully!');
    }

    /**
     * Display cart contents.
     */
    public function cart()
    {
        return view('customers.cart', ['cart' => Session::get('cart', [])]);
    }

    /**
     * Update quantity of an existing item in cart.
     */
    public function updateCart(Request $request, int $index)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$index])) {
            $cart[$index]['quantity'] = $validated['quantity'];
            Session::put('cart', $cart);
            return back()->with('message', 'Cart updated successfully!');
        }

        return back()->withErrors(['cart' => 'Item not found in cart.']);
    }

    /**
     * Remove an item from cart by array index.
     */
    public function removeFromCart(int $index)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$index])) {
            unset($cart[$index]);
            Session::put('cart', array_values($cart));
        }

        return back()->with('message', 'Item removed from cart.');
    }

    /**
     * Clear all items from cart.
     */
    public function clearCart()
    {
        Session::forget('cart');
        return back()->with('message', 'Cart cleared successfully.');
    }

    /**
     * Display checkout form (Handles GET and POST requests from Selected Items Form).
     */
    public function create(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu')->withErrors(['cart' => 'Your cart is empty.']);
        }

        // Cart page එකේ Checkbox මඟින් Select කළ Items Filter කිරීම
        if ($request->isMethod('post') && $request->has('selected_items')) {
            $selectedIndices = $request->input('selected_items', []);
            
            // පරිශීලකයා select කළ items පමණක් session cart එකෙන් වෙන් කරයි
            $cart = array_intersect_key($cart, array_flip($selectedIndices));

            if (empty($cart)) {
                return redirect()->route('cart')->withErrors(['cart' => 'Please select at least one item to proceed.']);
            }

            // Processing සඳහා තෝරාගත් Items Temporary Session එකක ගබඩා කිරීම
            Session::put('checkout_items', $cart);
        } else {
            // GET request එකකදී standard cart එක හෝ session එකේ ඇති checkout_items ලබා ගනී
            $cart = Session::get('checkout_items', $cart);
        }

        return view('customers.orders.create', [
            'cart'  => $cart,
            'zones' => DeliveryZone::all(),
        ]);
    }

    /**
     * Process checkout, store order in database, deduct stock, and notify Kitchen/Delivery zones.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_name'         => 'required|string|max:255',
            'contact_phone'        => 'required|string|max:20',
            'delivery_zone_id'     => 'nullable|exists:delivery_zones,id',
            'dropoff_location'     => 'required|string',
            'payment_method'       => 'required|in:cod,card',
            'special_instructions' => 'nullable|string',
            'card_holder'          => 'required_if:payment_method,card|nullable|string|max:255',
            'card_number'          => 'required_if:payment_method,card|nullable|string|max:20',
            'card_expiry'          => 'required_if:payment_method,card|nullable|string|max:5',
            'card_cvc'             => 'required_if:payment_method,card|nullable|string|max:4',
        ]);

        // Selected Items ඇති නම් එයින්ද, නැතහොත් ප්‍රධාන Cart එකෙන්ද දත්ත ගනී
        $cart = Session::get('checkout_items', Session::get('cart', []));

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['unit_price'] * $item['quantity'];
        });

        $deliveryFee = 0;
        if (!empty($validated['delivery_zone_id'])) {
            $deliveryZone = DeliveryZone::find($validated['delivery_zone_id']);
            if ($deliveryZone) {
                $deliveryFee = $deliveryZone->delivery_fee ?? $deliveryZone->fee ?? 0;
            }
        }

        $grandTotal = $subtotal + $deliveryFee;

        try {
            $order = DB::transaction(function () use ($validated, $cart, $grandTotal, $deliveryFee, $subtotal, $request) {
                // Validate stock availability with DB row locking
                $this->validateAndDeductStock($cart);

                // Create Parent Order
                $order = CustomerOrder::create([
                    'user_id'              => $request->user()->id,
                    'delivery_zone_id'     => $validated['delivery_zone_id'] ?? null,
                    'contact_name'         => $validated['contact_name'],
                    'contact_phone'        => $validated['contact_phone'],
                    'subtotal'             => $subtotal,
                    'delivery_fee'         => $deliveryFee,
                    'total_amount'         => $grandTotal,
                    'status'               => 'pending',
                    'special_instructions' => $validated['special_instructions'] ?? null,
                ]);

                // Create Order Items
                foreach ($cart as $item) {
                    $order->items()->create([
                        'product_id'           => $item['product_id'],
                        'item_name'            => $item['item_name'],
                        'customizations'       => $item['customizations'] ?? [],
                        'quantity'             => $item['quantity'],
                        'unit_price'           => $item['unit_price'],
                        'special_instructions' => $item['special_instructions'] ?? null,
                    ]);
                }

                // Create Payment Record
                $isCard = $validated['payment_method'] === 'card';
                $order->payment()->create([
                    'payment_method' => $validated['payment_method'],
                    'amount'         => $grandTotal,
                    'payment_status' => $isCard ? 'paid' : 'pending',
                    'transaction_id' => $isCard ? 'TXN-' . strtoupper(uniqid()) : null,
                ]);

                // Create Delivery Dispatch Task
                $order->delivery()->create([
                    'customer_order_id' => $order->id,
                    'recipient_name'    => $validated['contact_name'],
                    'recipient_phone'   => $validated['contact_phone'],
                    'dropoff_location'  => $validated['dropoff_location'],
                    'dropoff_address'   => $validated['dropoff_location'],
                    'delivery_status'   => 'unassigned',
                ]);

                return $order;
            });

            // Order එක සාර්ථක වූ පසු Select කර ගෙවූ Items Cart එකෙන් ඉවත් කරයි
            if (Session::has('checkout_items')) {
                $fullCart = Session::get('cart', []);
                $checkoutItems = Session::get('checkout_items', []);

                // Order කළ Items ප්‍රධාන cart එකෙන් අයින් කිරීම
                $updatedCart = array_filter($fullCart, function ($key) use ($checkoutItems) {
                    return !array_key_exists($key, $checkoutItems);
                }, ARRAY_FILTER_USE_KEY);

                Session::put('cart', array_values($updatedCart));
                Session::forget('checkout_items');
            } else {
                Session::forget('cart');
            }

            return redirect()->route('orders.show', $order->id)
                ->with('message', 'Order placed successfully and dispatched to kitchen!');
        } catch (\Exception $e) {
            return back()->withErrors(['stock' => $e->getMessage()]);
        }
    }

    /**
     * Display details of a specific order for customer.
     */
    public function show(Request $request, CustomerOrder $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access.');
        }

        // Review දත්ත ද සමඟ Eager Load කිරීම
        $order->load(['items.product', 'payment', 'delivery.driver', 'deliveryZone', 'review']);

        return view('customers.orders.show', compact('order'));
    }

    /**
     * Display history of orders for the current customer.
     */
    public function myOrders(Request $request)
    {
        // Customer Order History එකෙහි Review දත්ත Eager Load කිරීම
        $orders = CustomerOrder::with(['items.product', 'deliveryZone', 'delivery.driver', 'review'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('customers.orders.index', compact('orders'));
    }

    /**
     * Cancel an active pending order.
     */
    public function cancel(Request $request, CustomerOrder $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return back()->withErrors(['cancellation' => 'Order not found or access denied.']);
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string',
            'custom_reason'       => 'nullable|string|max:255',
        ]);

        if (!$order->canBeCancelled()) {
            return back()->withErrors(['cancellation' => 'This order cannot be cancelled as it is already being processed or completed.']);
        }

        $finalReason = $validated['cancellation_reason'];
        if ($finalReason === 'Other' && !empty($validated['custom_reason'])) {
            $finalReason = 'Other: ' . $validated['custom_reason'];
        }

        DB::transaction(function () use ($order, $finalReason) {
            $this->restoreStock($order);

            $order->update([
                'status'              => 'cancelled',
                'cancellation_reason' => $finalReason,
            ]);

            if ($order->delivery) {
                $order->delivery->update(['delivery_status' => 'cancelled']);
            }
        });

        return back()->with('message', 'Your order has been cancelled and stock restored successfully.');
    }

    /* =========================================================================
       ADMIN SECTION
       ========================================================================= */

    /**
     * Display all orders for admin.
     */
    public function adminIndex()
    {
        $orders = CustomerOrder::with(['user', 'items.product', 'deliveryZone', 'delivery.driver', 'review'])
            ->latest()
            ->paginate(15);

        $drivers = User::where('role', 'driver')->orWhere('is_driver', true)->get();

        return view('admin.orders.index', compact('orders', 'drivers'));
    }

    /**
     * Assign driver to an order (Admin route handler).
     */
    public function assignDriver(Request $request, CustomerOrder $order)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        if ($order->delivery) {
            $order->delivery->update([
                'driver_id'       => $validated['driver_id'],
                'delivery_status' => 'assigned',
            ]);
        } else {
            $order->delivery()->create([
                'driver_id'        => $validated['driver_id'],
                'dropoff_location' => 'N/A',
                'dropoff_address'  => 'N/A',
                'delivery_status'  => 'assigned',
            ]);
        }

        return back()->with('message', 'Driver assigned successfully!');
    }

    /**
     * Update order status by admin.
     */
    public function updateStatus(Request $request, CustomerOrder $order)
    {
        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('message', 'Order status updated successfully!');
    }

    /* =========================================================================
       HELPER METHODS
       ========================================================================= */

    /**
     * Helper: Validate stock availability AND deduct stock.
     */
    private function validateAndDeductStock(array $cart)
    {
        foreach ($cart as $item) {
            $productItem = ProductItem::with(['ingredients' => function ($query) {
                $query->lockForUpdate();
            }])->find($item['product_id']);

            if ($productItem) {
                foreach ($productItem->ingredients as $ingredient) {
                    $requiredQty = $ingredient->pivot->quantity_required * $item['quantity'];
                    if ($ingredient->quantity < $requiredQty) {
                        throw new \Exception("Insufficient stock for ingredient: {$ingredient->name}");
                    }
                    $ingredient->decrement('quantity', $requiredQty);
                }
            }

            if (!empty($item['customizations'])) {
                foreach ($item['customizations'] as $customization) {
                    $customIngredientId = $customization['id'] ?? null;
                    if ($customIngredientId) {
                        $customIngredient = Ingredient::lockForUpdate()->find($customIngredientId);
                        
                        if ($customIngredient) {
                            $requiredCustomQty = 1 * $item['quantity'];
                            if ($customIngredient->quantity < $requiredCustomQty) {
                                throw new \Exception("Insufficient stock for extra ingredient: {$customIngredient->name}");
                            }
                            $customIngredient->decrement('quantity', $requiredCustomQty);
                        }
                    }
                }
            }
        }
    }

    /**
     * Helper: Restore inventory stock when order is cancelled.
     */
    private function restoreStock(CustomerOrder $order)
    {
        foreach ($order->items as $item) {
            if ($item->product_id) {
                $productItem = ProductItem::with('ingredients')->find($item->product_id);
                if ($productItem) {
                    foreach ($productItem->ingredients as $ingredient) {
                        $requiredQty = $ingredient->pivot->quantity_required * $item->quantity;
                        $ingredient->increment('quantity', $requiredQty);
                    }
                }
            }

            $customizations = is_string($item->customizations) 
                ? json_decode($item->customizations, true) 
                : $item->customizations;

            if (!empty($customizations) && is_array($customizations)) {
                foreach ($customizations as $customization) {
                    $customIngredientId = $customization['id'] ?? null;
                    if ($customIngredientId) {
                        $customIngredient = Ingredient::find($customIngredientId);
                        if ($customIngredient) {
                            $customIngredient->increment('quantity', 1 * $item->quantity);
                        }
                    }
                }
            }
        }
    }
}