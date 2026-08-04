<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Display kitchen orders dashboard.
     */
    public function index()
    {
        $orders = CustomerOrder::with(['items', 'user'])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at', 'asc')
            ->get();

        $ingredients = Ingredient::all();

        return view('kitchen.index', compact('orders', 'ingredients'));
    }

    /**
     * Update order status from kitchen dashboard.
     */
    public function updateStatus(Request $request, int|string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,out_for_delivery,delivered,cancelled',
        ]);

        $order = CustomerOrder::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return back()->with('message', 'Order status updated successfully.');
    }

    /**
     * Toggle ingredient stock availability.
     */
    public function toggleStock(Ingredient $ingredient)
    {
        // Toggle the 'in_stock' boolean status
        $ingredient->update([
            'in_stock' => !$ingredient->in_stock,
        ]);

        return back()->with('message', 'Ingredient stock updated successfully.');
    }
}