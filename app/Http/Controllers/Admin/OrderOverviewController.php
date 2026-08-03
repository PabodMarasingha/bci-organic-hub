<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;

class OrderOverviewController extends Controller
{
    public function index()
    {
        $orders = CustomerOrder::with('user')->latest()->get();
        $totalSales = CustomerOrder::where('status', '!=', 'cancelled')->sum('total_amount');

        return view('admin.orders', compact('orders', 'totalSales'));
    }
}