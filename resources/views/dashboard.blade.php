<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-800 tracking-tight">
                    Customer Dashboard
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Welcome back to BCI Organic Hub
                </p>
            </div>
            
            <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 rounded-xl transition-all shadow-md hover:shadow-emerald-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                + Order Food Now
            </a>
        </div>
    </x-slot>

    @php
        $userOrders = \App\Models\CustomerOrder::where('user_id', auth()->id())->latest()->get();
        $totalOrders = $userOrders->count();
        $activeOrders = $userOrders->whereIn('status', ['pending', 'cooking', 'ready'])->count();
        $completedOrders = $userOrders->where('status', 'completed')->count();
        $recentOrders = $userOrders->take(5);
    @endphp

    <div class="py-8 bg-slate-50/60 min-h-screen" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Hero Banner -->
            <div class="relative overflow-hidden bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-900/10 transition-all duration-500 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-emerald-100">
                            <span>🌱 Healthy & Organic Food</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                            Welcome back, {{ auth()->user()->name }}! 👋
                        </h1>
                        <p class="text-xs sm:text-sm text-emerald-100 max-w-xl font-medium leading-relaxed">
                            Craving something fresh today? Browse our organic menu or track your ongoing orders below!
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 shrink-0">
                        <a href="{{ route('menu') }}" class="px-4 py-2.5 bg-white text-emerald-700 hover:bg-emerald-50 font-bold text-xs rounded-xl shadow-sm transition">
                            Browse Menu
                        </a>
                        <a href="{{ route('cart') }}" class="px-4 py-2.5 bg-emerald-800/60 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl border border-emerald-500/30 transition">
                            View Cart
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 transition-all duration-500 delay-100 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Orders</span>
                        <span class="text-3xl font-black text-slate-800">{{ $totalOrders }}</span>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-bold text-xl">🛍️</div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Active Orders</span>
                        <span class="text-3xl font-black text-slate-800">{{ $activeOrders }}</span>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center font-bold text-xl">⏳</div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Completed Orders</span>
                        <span class="text-3xl font-black text-slate-800">{{ $completedOrders }}</span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center font-bold text-xl">✅</div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-500 delay-200 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Recent Orders</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Your latest food activity</p>
                    </div>
                    @if($totalOrders > 0)
                        <a href="{{ route('orders.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                            View All &rarr;
                        </a>
                    @endif
                </div>

                @if($recentOrders->count() > 0)
                    <div class="divide-y divide-slate-100 overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/30 text-[11px] uppercase tracking-wider font-bold text-slate-400">
                                    <th class="py-3.5 px-6">Order ID</th>
                                    <th class="py-3.5 px-6">Date</th>
                                    <th class="py-3.5 px-6">Items</th>
                                    <th class="py-3.5 px-6">Total Amount</th>
                                    <th class="py-3.5 px-6">Status</th>
                                    <th class="py-3.5 px-6 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($recentOrders as $order)
                                    @php
                                        $statusColors = [
                                            'pending'   => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'cooking'   => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'ready'     => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        ];
                                        $badgeStyle = $statusColors[strtolower($order->status)] ?? 'bg-slate-100 text-slate-800';
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-6 font-bold text-slate-800">#{{ $order->id }}</td>
                                        <td class="py-4 px-6 text-slate-500 font-medium">{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td class="py-4 px-6 text-slate-600">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</td>
                                        <td class="py-4 px-6 font-extrabold text-slate-800">LKR {{ number_format($order->total_amount, 2) }}</td>
                                        <td class="py-4 px-6">
                                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $badgeStyle }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl transition">
                                                View Live Tracker &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center space-y-4">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto text-2xl">🍲</div>
                        <p class="text-xs text-slate-400">No orders placed yet!</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>