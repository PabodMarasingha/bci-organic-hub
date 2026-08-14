<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\DeliveryZone;
use App\Models\Ingredient;
use App\Models\ProductItem;
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
        $products = ProductItem::all();
        return view('menu', compact('products'));
    }

    /**
     * Display the custom meal builder for a specific product.
     */
    public function build(ProductItem $product)
    {
        $ingredients = Ingredient::all();
        return view('build', compact('product', 'ingredients'));
    }

    /**
     * Add custom or standard meal to session cart.
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product_items,id',
            'quantity' => 'required|integer|min:1',
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'exists:ingredients,id',
            'special_instructions' => 'nullable|string|max:255',
        ]);

        $product = ProductItem::findOrFail($validated['product_id']);
        $selectedIngredients = Ingredient::whereIn('id', $validated['ingredients'] ?? [])->get();

        $extraPrice = $selectedIngredients->sum('price');
        $unitPrice = $product->price + $extraPrice;

        $customizations = $selectedIngredients->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'price' => $ingredient->price,
            ];
        })->toArray();

        $cart = Session::get('cart', []);

        $cart[] = [
            'product_id' => $product->id,
            'item_name' => $product->name,
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'customizations' => $customizations,
            'special_instructions' => $validated['special_instructions'] ?? null,
        ];

        Session::put('cart', $cart);

        return redirect()->route('cart')->with('message', 'Item added to cart successfully!');
    }

    /**
     * Display cart contents.
     */
    public function cart()
    {
        return view('cart', ['cart' => Session::get('cart', [])]);
    }

    /**
     * Remove an item from cart by array index.
     */
    public function removeFromCart(int $index)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$index])) {
            unset($cart[$index]);
            Session::put('cart', array_values($cart)); // Reset keys
        }

        return back()->with('message', 'Item removed from cart.');
    }

    /**
     * Display checkout form.
     */
    public function create()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu')->withErrors(['cart' => 'Your cart is empty.']);
        }

        return view('orders.create', [
            'cart' => $cart,
            'zones' => DeliveryZone::all(),
        ]);
    }

    /**
     * Process checkout and store order in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_zone_id' => 'required|exists:delivery_zones,id',
            'dropoff_location' => 'required|string',
            'payment_method' => 'required|string',
            'special_instructions' => 'nullable|string',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $totalAmount = collect($cart)->sum(function ($item) {
            return $item['unit_price'] * $item['quantity'];
        });

        $order = DB::transaction(function () use ($validated, $cart, $totalAmount, $request) {
            $order = CustomerOrder::create([
                'user_id' => $request->user()->id,
                'delivery_zone_id' => $validated['delivery_zone_id'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);

            foreach ($cart as $item) {
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

        Session::forget('cart');

        return redirect()->route('orders.show', $order->id)
            ->with('message', 'Order placed successfully!');
    }

    /**
     * Display details of a specific order.
     */
    public function show(Request $request, int $id)
    {
        $order = CustomerOrder::with(['items', 'payment', 'delivery', 'deliveryZone'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Display history of orders for the current customer.
     */
    public function myOrders(Request $request)
    {
        $orders = CustomerOrder::with(['items', 'deliveryZone', 'delivery'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Cancel an active pending order with a reason.
     */
    public function cancel(Request $request, int $id)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string',
            'custom_reason' => 'nullable|string|max:255',
        ]);

        // 1. Order එක Database එකේ පවතිනවාදැයි පරීක්ෂාව
        $order = CustomerOrder::find($id);

        if (!$order) {
            return back()->withErrors(['cancellation' => 'Order not found.']);
        }

        // 2. අදාළ Order එක Log වී සිටින User ටම අයත් එකක්දැයි පරීක්ෂාව
        if ($order->user_id !== $request->user()->id) {
            return back()->withErrors(['cancellation' => 'You do not have permission to cancel this order.']);
        }

        // 3. Order එක Cancel කළ හැකි තත්ත්වයේ තිබේදැයි Model Helper Method එකෙන් පරීක්ෂා කිරීම
        if (!$order->canBeCancelled()) {
            return back()->withErrors(['cancellation' => 'This order cannot be cancelled as it is already being processed or completed.']);
        }

        $finalReason = $validated['cancellation_reason'];
        if ($finalReason === 'Other' && !empty($validated['custom_reason'])) {
            $finalReason = 'Other: ' . $validated['custom_reason'];
        }

        // 4. Status සහ Cancellation Reason update කිරීම
        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $finalReason,
        ]);

        return back()->with('message', 'Your order has been cancelled successfully.');
    }
}