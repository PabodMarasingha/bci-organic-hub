<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.item_name' => 'required|string',
            'items.*.customizations' => 'nullable|array',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'delivery_zone' => 'required|string',
            'dropoff_location' => 'required|string',
            'payment_method' => 'required|string',
            'special_instructions' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated, $request) {
    // 1. Create Main Order (removed 'delivery_zone' from here)
    $order = CustomerOrder::create([
        'user_id' => $request->user()->id,
        'total_amount' => $validated['total_amount'],
        'status' => 'pending',
        'special_instructions' => $validated['special_instructions'] ?? null,
    ]);

    // 2. Create Order Items
    foreach ($request->items as $item) {
        $order->items()->create([
            'item_name' => $item['item_name'],
            'customizations' => json_encode($item['customizations'] ?? []),
            'quantity' => $item['quantity'],
            'unit_price' => $item['unit_price'],
        ]);
    }

    // 3. Create Payment Record
    $order->payment()->create([
        'payment_method' => $validated['payment_method'],
        'amount' => $validated['total_amount'],
        'status' => 'pending',
    ]);

    // 4. Create Delivery Record (delivery_zone goes HERE)
    $order->delivery()->create([
        'delivery_zone' => $validated['delivery_zone'],
        'dropoff_location' => $validated['dropoff_location'],
        'status' => 'pending',
    ]);

    return response()->json([
        'message' => 'Order placed successfully!',
        'order_id' => $order->id
    ], 201);
});
    }

    public function show(Request $request, $id)
    {
        $order = CustomerOrder::with(['orderItems', 'payment', 'delivery'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($order);
    }
}