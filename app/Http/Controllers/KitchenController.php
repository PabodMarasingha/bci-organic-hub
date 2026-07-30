<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = CustomerOrder::with(['orderItems', 'user'])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($orders);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,out_for_delivery,delivered,cancelled',
        ]);

        $order = CustomerOrder::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Order status updated successfully', 'order' => $order]);
    }
}