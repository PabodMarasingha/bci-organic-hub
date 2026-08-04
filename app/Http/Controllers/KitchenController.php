<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Display kitchen orders dashboard and ingredient stock status.
     */
    public function index()
    {
        // Kitchen එකට අදාළ Active Orders (Pending, Preparing, Ready)
        $orders = CustomerOrder::with(['items.productItem', 'user'])
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Ingredients සියල්ල ලබා ගැනීම
        $ingredients = Ingredient::all();

        // Dashboard Stats Counts
        $pendingCount   = $orders->where('status', 'pending')->count();
        $preparingCount = $orders->where('status', 'preparing')->count();
        $readyCount     = $orders->where('status', 'ready')->count();

        return view('kitchen.index', compact(
            'orders', 
            'ingredients', 
            'pendingCount', 
            'preparingCount', 
            'readyCount'
        ));
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

        return back()->with('message', 'Order #' . $order->id . ' status updated to ' . strtoupper($validated['status']) . ' successfully.');
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