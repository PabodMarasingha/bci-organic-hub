<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function cart()
    {
        return view('cart', ['cart' => Session::get('cart', [])]);
    }

    public function removeFromCart($index)
    {
        $cart = Session::get('cart', []);
        unset($cart[$index]);
        Session::put('cart', array_values($cart));
        return back();
    }

    public function create()
    {
        return view('orders.create', [
            'cart' => Session::get('cart', []),
            'zones' => DeliveryZone::all(),
        ]);
    }

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

    public function show(Request $request, $id)
    {
        $order = CustomerOrder::with(['items', 'payment', 'delivery', 'deliveryZone'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return view('orders.show', ['order' => $order]);
    }
}