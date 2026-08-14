<x-app-layout>
    <!-- Custom CSS Animations for Subtle Border Beam -->
    <style>
        @keyframes flowBeam {
            0% { stroke-dashoffset: 1000; }
            100% { stroke-dashoffset: 0; }
        }
        .animated-path {
            stroke-dasharray: 200 800;
            animation: flowBeam 4s linear infinite;
        }
    </style>

    <div class="py-8 bg-[#05070c] min-h-screen text-slate-100 selection:bg-emerald-500 selection:text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Top Header & Dark Mode Switch Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#0b0f19]/90 backdrop-blur-xl p-6 rounded-2xl border border-slate-800/80 shadow-xl">
                <div>
                    <h2 class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-green-500">
                        Customer Portal
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Welcome back, <span class="text-emerald-400 font-semibold">{{ Auth::user()->name ?? 'User' }}</span>! {{ __("You're logged in!") }}</p>
                </div>

                <!-- Dark / Light Theme Toggle Switch -->
                <div class="flex items-center gap-3">
                    <button id="theme-toggle" class="p-3 rounded-xl bg-[#141a26] border border-slate-700/70 text-slate-300 hover:text-emerald-400 transition flex items-center gap-2 text-xs font-semibold">
                        <svg id="theme-toggle-dark-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span id="theme-toggle-text">Theme Switch</span>
                    </button>
                </div>
            </div>

            <!-- Quick Stats Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- Total Orders -->
                <div class="p-5 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Total Orders</p>
                        <h3 class="text-2xl font-extrabold text-slate-100 mt-1">{{ $totalOrdersCount ?? 0 }}</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>

                <!-- Active Orders -->
                <div class="p-5 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Active Order</p>
                        <h3 class="text-2xl font-extrabold text-amber-400 mt-1">{{ $activeOrdersCount ?? 0 }} Active</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-500/10 text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Cart Items -->
                <div class="p-5 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Cart Items</p>
                        <h3 class="text-2xl font-extrabold text-rose-400 mt-1">{{ $cartCount ?? 0 }} Items</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-rose-500/10 text-rose-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                    </div>
                </div>

                <!-- Reward Points -->
                <div class="p-5 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Hub Points</p>
                        <h3 class="text-2xl font-extrabold text-teal-400 mt-1">{{ Auth::user()->points ?? 350 }} PTS</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-teal-500/10 text-teal-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

            </div>

            <!-- Active Order Status Live Banner -->
            @if(isset($latestActiveOrder) && $latestActiveOrder)
                <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-950/40 via-[#0b0f19] to-[#0b0f19] border border-emerald-500/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider mb-2">Live Order Status</span>
                        <h3 class="text-lg font-bold text-white">
                            Order #BCI-{{ $latestActiveOrder->id }} - {{ ucfirst(str_replace('_', ' ', $latestActiveOrder->status)) }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">Estimated Delivery Time: <span class="text-emerald-400 font-semibold">20-30 mins</span></p>
                    </div>
                    <a href="{{ Route::has('orders.show') ? route('orders.show', $latestActiveOrder->id) : '#' }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-950/80 transition transform hover:-translate-y-0.5 text-center">
                        Track Live Delivery
                    </a>
                </div>
            @else
                <!-- Banner when there are no active orders -->
                <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-950/20 via-[#0b0f19] to-[#0b0f19] border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-slate-800 text-slate-300 text-[10px] font-bold uppercase tracking-wider mb-2">Fresh & Healthy Meals</span>
                        <h3 class="text-lg font-bold text-white">Ready for a Healthy Choice Today?</h3>
                        <p class="text-xs text-slate-400 mt-1">Explore our organic menu and build your customized meal.</p>
                    </div>
                    <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition text-center">
                        Explore Menu &rarr;
                    </a>
                </div>
            @endif

            <!-- Recent Orders Table -->
            <div class="p-6 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-base text-slate-100">Recent Organic Orders</h3>
                    <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="text-xs text-emerald-400 hover:underline font-bold">View All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-[11px] uppercase text-slate-400 font-semibold">
                                <th class="py-3 px-4">Order ID</th>
                                <th class="py-3 px-4">Items</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-xs text-slate-300">
                            @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td class="py-4 px-4 font-semibold text-emerald-400">#BCI-{{ $order->id }}</td>
                                    
                                    <!-- Items Column -->
                                    <td class="py-4 px-4">
                                        @if(isset($order->items) && is_countable($order->items) && count($order->items) > 0)
                                            {{ count($order->items) }} Items
                                        @else
                                            Organic Meal
                                        @endif
                                    </td>
                                    
                                    <td class="py-4 px-4 font-bold text-slate-100">
                                        Rs. {{ number_format($order->total_price ?? $order->total_amount ?? $order->total ?? 0, 2) }}
                                    </td>
                                    
                                    <td class="py-4 px-4">
                                        @php
                                            $status = strtolower($order->status ?? 'pending');
                                            $badgeClasses = match($status) {
                                                'delivered', 'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                                default => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $badgeClasses }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <a href="{{ Route::has('orders.show') ? route('orders.show', $order->id) : '#' }}" class="text-slate-400 hover:text-emerald-400 font-semibold transition">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-500">
                                        No recent orders found. <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="text-emerald-400 underline font-semibold">Place your first order!</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript for Theme Toggle Switch Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');

            if (localStorage.getItem('color-theme') === 'light') {
                lightIcon?.classList.remove('hidden');
            } else {
                darkIcon?.classList.remove('hidden');
            }

            themeToggleBtn?.addEventListener('click', () => {
                darkIcon?.classList.toggle('hidden');
                lightIcon?.classList.toggle('hidden');

                if (localStorage.getItem('color-theme') === 'light') {
                    localStorage.setItem('color-theme', 'dark');
                    document.documentElement.classList.add('dark');
                } else {
                    localStorage.setItem('color-theme', 'light');
                    document.documentElement.classList.remove('dark');
                }
            });
        });
    </script>
</x-app-layout>