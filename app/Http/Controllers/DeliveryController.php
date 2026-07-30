<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with(['customerOrder.user'])
            ->whereIn('delivery_status', ['assigned', 'picked_up'])
            ->get();

        return response()->json($deliveries);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'delivery_status' => 'required|in:assigned,picked_up,delivered',
        ]);

        $delivery = Delivery::findOrFail($id);
        $delivery->update([
            'delivery_status' => $request->delivery_status,
            'delivered_at' => $request->delivery_status === 'delivered' ? now() : null,
        ]);

        return response()->json(['message' => 'Delivery status updated', 'delivery' => $delivery]);
    }
}