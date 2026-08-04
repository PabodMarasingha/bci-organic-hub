<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\DeliveryZone;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display the menu page with search and category filters.
     */
    public function menu(Request $request)
    {
        $query = ProductItem::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $products = $query->where('is_available', true)->get();

        return view('menu', compact('products'));
    }

    /**
     * Add item or custom product to session cart.
     */
    public function addToCart(Request $request)
    {
        // Validation check
        $validated = $request->validate([
            'product_id' => 'required|exists:product_items,id',
            'quantity' => 'required|integer|min:1',
            'customizations' => 'nullable|array',
        ]);

        $product = ProductItem::findOrFail($validated['product_id']);
        $cart = Session::get('cart', []);

        // Flexible price fallback
        $unitPrice = $product->price ?? $product->base_price ?? 0;
        $customizations = $validated['customizations'] ?? [];

        // Check if item already exists in cart with same customizations
        $existingKey = null;
        foreach ($cart as $key => $item) {
            $itemProductId = $item['product_id'] ?? $item['item_id'] ?? null;
            $itemCustomizations = $item['customizations'] ?? [];

            if ($itemProductId == $product->id && $itemCustomizations == $customizations) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey !== null) {
            $cart[$existingKey]['quantity'] += $validated['quantity'];
        } else {
            $cart[] = [
                'product_id' => $product->id,
                'item_name' => $product->name,
                'customizations' => $customizations,
                'quantity' => (int) $validated['quantity'],
                'unit_price' => (float) $unitPrice,
            ];
        }

        // Save updated cart back to session
        Session::put('cart', $cart);

        return redirect()->route('cart')->with('message', 'Item added to cart successfully!');
    }

    /**
     * Display the shopping cart.
     */
    public function cart()
    {
        return view('cart', ['cart' => Session::get('cart', [])]);
    }

    /**
     * Update item quantity in the cart.
     */
    public function updateCart(Request $request, int|string $index)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$index])) {
            $cart[$index]['quantity'] = (int) $validated['quantity'];
            Session::put('cart', $cart);
            return back()->with('message', 'Cart updated successfully!');
        }

        return back()->withErrors(['cart' => 'Item not found in cart.']);
    }

    /**
     * Remove an item from the cart by index.
     */
    public function removeFromCart(int|string $index)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$index])) {
            unset($cart[$index]);
            // Re-index array so keys stay sequential (0, 1, 2...)
            Session::put('cart', array_values($cart));
        }

        return back()->with('message', 'Item removed from cart.');
    }

    /**
     * Display order checkout form with SELECTED items.
     */
    public function create(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Your cart is empty.');
        }

        // Get selected item indices from cart view
        $selectedIndexes = $request->input('selected_items', []);

        if (empty($selectedIndexes)) {
            return redirect()->route('cart')->withErrors(['cart' => 'Please select at least one item to proceed.']);
        }

        // Filter cart to only selected items
        $checkoutCart = [];
        foreach ($selectedIndexes as $index) {
            if (isset($cart[$index])) {
                $checkoutCart[$index] = $cart[$index];
            }
        }

        if (empty($checkoutCart)) {
            return redirect()->route('cart')->withErrors(['cart' => 'Selected items are invalid or no longer in cart.']);
        }

        return view('orders.create', [
            'cart' => $checkoutCart,
            'selectedIndexes' => $selectedIndexes,
            'zones' => DeliveryZone::all(),
        ]);
    }

    /**
     * Store new customer order in database for selected items.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_zone_id' => 'required|exists:delivery_zones,id',
            'dropoff_location' => 'required|string',
            'payment_method' => 'required|string',
            'special_instructions' => 'nullable|string',
            'selected_indexes' => 'required|array',
        ]);

        $cart = Session::get('cart', []);
        $selectedIndexes = $validated['selected_indexes'];

        // Get only the selected items
        $orderItems = [];
        foreach ($selectedIndexes as $index) {
            if (isset($cart[$index])) {
                $orderItems[$index] = $cart[$index];
            }
        }

        if (empty($orderItems)) {
            return redirect()->route('cart')->withErrors(['cart' => 'No valid items were selected for order processing.']);
        }

        $totalAmount = collect($orderItems)->sum(function ($item) {
            return $item['unit_price'] * $item['quantity'];
        });

        $order = DB::transaction(function () use ($validated, $orderItems, $totalAmount, $request) {
            $order = CustomerOrder::create([
                'user_id' => $request->user()->id,
                'delivery_zone_id' => $validated['delivery_zone_id'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create([
                    'item_name' => $item['item_name'],
                    'customizations' => $item['customizations'] ?? [],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            $order->payment()->create([
                'payment_method' => $validated['payment_method'],
                'amount' => $totalAmount,
                'payment_status' => 'pending',
            ]);

            $order->delivery()->create([
                'dropoff_location' => $validated['dropoff_location'],
                'delivery_status' => 'unassigned',
            ]);

            return $order;
        });

        // Order එක සම්පූර්ණ වූ පසු SELECT කල items ටික විතරක් Cart එකෙන් අයින් කරන්න
        foreach ($selectedIndexes as $index) {
            unset($cart[$index]);
        }

        // Re-index session cart and update
        Session::put('cart', array_values($cart));

        return redirect()->route('orders.show', $order->id)
            ->with('message', 'Order placed successfully!');
    }

    /**
     * Display a specific order's details.
     */
    public function show(Request $request, int|string $id)
    {
        $order = CustomerOrder::with(['items', 'payment', 'delivery', 'deliveryZone'])
            ->where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Display list of orders for logged-in user.
     */
    public function myOrders(Request $request)
    {
        $orders = CustomerOrder::with(['items', 'deliveryZone'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Cancel a pending order.
     */
    public function cancel(Request $request, int|string $id)
    {
        $order = CustomerOrder::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

        if (strtolower($order->status) === 'pending') {
            $order->update(['status' => 'cancelled']);

            if ($order->delivery) {
                $order->delivery->update(['delivery_status' => 'cancelled']);
            }

            return redirect()->route('orders.show', $order->id)->with('message', 'Order cancelled successfully.');
        }

        return redirect()->route('orders.show', $order->id)->with('message', 'This order cannot be cancelled.');
    }
}