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
        $validated = $request->validate([
            'product_id' => 'required|exists:product_items,id',
            'quantity' => 'required|integer|min:1',
            'customizations' => 'nullable|array',
        ]);

        $product = ProductItem::findOrFail($validated['product_id']);
        $cart = Session::get('cart', []);

        // Security Check: Database එකේ ඇති සැබෑ Price එක භාවිත කිරීම
        $unitPrice = $product->price;

        $existingKey = null;
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $product->id && $item['customizations'] == ($validated['customizations'] ?? [])) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey !== null) {
            // Cart එකේ දැනටමත් තියෙනවා නම් Quantity එක එකතු කිරීම
            $cart[$existingKey]['quantity'] += $validated['quantity'];
        } else {
            // නැත්නම් අලුතින් Cart එකට එකතු කිරීම
            $cart[] = [
                'product_id' => $product->id,
                'item_name' => $product->name,
                'customizations' => $validated['customizations'] ?? [],
                'quantity' => $validated['quantity'],
                'unit_price' => $unitPrice,
            ];
        }

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
            $cart[$index]['quantity'] = $validated['quantity'];
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
            Session::put('cart', array_values($cart));
        }

        return back()->with('message', 'Item removed from cart.');
    }

    /**
     * Display order checkout form.
     */
    public function create()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        return view('orders.create', [
            'cart' => $cart,
            'zones' => DeliveryZone::all(),
        ]);
    }

    /**
     * Store new customer order in database.
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
            return redirect()->route('menu')->withErrors(['cart' => 'Your cart is empty.']);
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
     * Display a specific order's details.
     */
    public function show(Request $request, int|string $id)
    {
        $order = CustomerOrder::with(['items', 'payment', 'delivery', 'deliveryZone'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

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
            ->where('status', 'pending')
            ->findOrFail($id);

        $order->update(['status' => 'cancelled']);

        if ($order->delivery) {
            $order->delivery->update(['delivery_status' => 'cancelled']);
        }

        return back()->with('message', 'Order cancelled successfully.');
    }
}