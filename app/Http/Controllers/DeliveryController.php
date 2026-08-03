<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with(['order.user', 'order.deliveryZone'])
            ->whereIn('delivery_status', ['unassigned', 'picked_up'])
            ->get();

        return view('delivery.index', compact('deliveries'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'delivery_status' => 'required|in:unassigned,picked_up,delivered',
        ]);

        $delivery = Delivery::findOrFail($id);
        $delivery->update([
            'delivery_status' => $request->delivery_status,
            'delivered_at' => $request->delivery_status === 'delivered' ? now() : null,
        ]);

        // Keep the parent order's status in sync
        if ($request->delivery_status === 'picked_up') {
            $delivery->order->update(['status' => 'out_for_delivery']);
        } elseif ($request->delivery_status === 'delivered') {
            $delivery->order->update(['status' => 'delivered']);
        }

        return back()->with('message', 'Delivery status updated.');
    }
}