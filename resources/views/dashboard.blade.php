<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Customer Dashboard') }}
            </h2>
            <a href="{{ route('menu') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow transition duration-200">
                + Order Food Now
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-2xl p-6 text-white shadow-lg flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-green-100 text-sm mt-1">Enjoy healthy and organic meals freshly cooked for you.</p>
                </div>
                <div class="hidden md:flex space-x-3">
                    <a href="{{ route('menu') }}" class="px-4 py-2 bg-white text-green-700 text-sm font-bold rounded-lg shadow hover:bg-green-50 transition">Browse Menu</a>
                    <a href="{{ route('cart') }}" class="px-4 py-2 bg-green-800/50 hover:bg-green-800 text-white text-sm font-bold rounded-lg transition">View Cart</a>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Orders -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Orders</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $totalOrders ?? 0 }}</h3>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="p-3 bg-amber-100 text-amber-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Active / Pending</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $pendingOrders ?? 0 }}</h3>
                    </div>
                </div>

                <!-- Completed Orders -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="p-3 bg-green-100 text-green-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Completed</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $completedOrders ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Recent Orders</h3>
                    @if(Route::has('orders.my'))
                        <a href="{{ route('orders.my') }}" class="text-sm font-semibold text-green-600 hover:text-green-700">View All Orders &rarr;</a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-xs uppercase font-semibold">
                                <th class="py-3 px-6">Order ID</th>
                                <th class="py-3 px-6">Total Items</th>
                                <th class="py-3 px-6">Total Amount</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($recentOrders ?? [] as $order)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-800">#ORD-{{ $order->id }}</td>
                                    <td class="py-4 px-6 text-slate-600">{{ $order->items->sum('quantity') }} items</td>
                                    <td class="py-4 px-6 font-semibold text-slate-800">LKR {{ number_format($order->total_amount, 2) }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                            @if($order->status == 'pending') bg-amber-100 text-amber-700
                                            @elseif($order->status == 'completed') bg-green-100 text-green-700
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-700
                                            @else bg-slate-100 text-slate-700 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if(Route::has('orders.show'))
                                            <a href="{{ route('orders.show', $order->id) }}" class="text-xs font-semibold px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400">
                                        No recent orders found. <a href="{{ route('menu') }}" class="text-green-600 font-semibold underline">Place your first order!</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>