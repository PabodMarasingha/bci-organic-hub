<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = CustomerOrder::with(['items', 'user'])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at', 'asc')
            ->get();

        $ingredients = Ingredient::all();

        return view('kitchen.index', compact('orders', 'ingredients'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,out_for_delivery,delivered,cancelled',
        ]);

        $order = CustomerOrder::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('message', 'Order status updated.');
    }

    public function toggleStock(Ingredient $ingredient)
    {
        $ingredient->update(['in_stock' => !$ingredient->in_stock]);
        return back();
    }
}