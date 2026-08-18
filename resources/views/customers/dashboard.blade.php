<x-app-layout>
    <!-- Custom CSS Animations & Swiper Customization -->
    <style>
        @keyframes flowBeam {
            0% { stroke-dashoffset: 1000; }
            100% { stroke-dashoffset: 0; }
        }
        .animated-path {
            stroke-dasharray: 200 800;
            animation: flowBeam 4s linear infinite;
        }

        /* Swiper Pagination Styling */
        .swiper-pagination-bullet {
            background: #64748b !important;
            opacity: 0.6;
        }
        .swiper-pagination-bullet-active {
            background: #10b981 !important;
            width: 24px !important;
            border-radius: 8px !important;
            opacity: 1;
        }
    </style>

    <div class="py-8 bg-slate-100 dark:bg-[#05070c] min-h-screen text-slate-800 dark:text-slate-100 selection:bg-emerald-500 selection:text-white transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Top Header & Dark Mode Switch Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-[#0b0f19]/90 backdrop-blur-xl p-6 rounded-2xl border border-slate-200 dark:border-slate-800/80 shadow-xl transition-colors duration-300">
                <div>
                    <h2 class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 via-teal-500 to-green-600 dark:from-emerald-400 dark:via-teal-300 dark:to-green-500">
                        Customer Portal
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Welcome back, <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ Auth::user()->name ?? 'User' }}</span>! {{ __("You're logged in!") }}
                    </p>
                </div>

                <!-- Dark / Light Theme Toggle Switch -->
                <div class="flex items-center gap-3">
                    <button id="theme-toggle" type="button" class="p-3 rounded-xl bg-slate-200 dark:bg-[#141a26] border border-slate-300 dark:border-slate-700/70 text-slate-700 dark:text-slate-300 hover:text-emerald-500 dark:hover:text-emerald-400 transition flex items-center gap-2 text-xs font-semibold shadow-sm hover:shadow">
                        <svg id="theme-toggle-dark-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span id="theme-toggle-text">Theme Switch</span>
                    </button>
                </div>
            </div>

            <!-- Promo Ads / Offer Swiper Slider Section -->
            <div class="w-full">
                <div class="swiper offerSwiper rounded-3xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800/80">
                    <div class="swiper-wrapper">

                        <!-- Slide 1: Premium Food Image Background Banner -->
                        <div class="swiper-slide relative h-[300px] sm:h-[380px] bg-slate-900 flex items-center overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1543353071-10c8ba85a904?q=80&w=1400" 
                                 class="absolute inset-0 w-full h-full object-cover object-center opacity-60 transition-transform duration-700 hover:scale-105" 
                                 alt="Fresh Organic Meal">
                            
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/85 to-transparent"></div>

                            <div class="relative z-10 p-6 sm:p-12 max-w-xl space-y-3">
                                <span class="px-3 py-1 bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 text-[10px] font-black rounded-full uppercase tracking-widest inline-block animate-pulse">
                                    🔥 Limited Time Offer
                                </span>
                                <h3 class="text-2xl sm:text-4xl font-black text-white leading-tight">
                                    Get <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">30% OFF</span> On Your Next Fresh Meal
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-300">
                                    Delicious, healthy, chef-crafted organic recipes delivered straight to your door.
                                </p>
                                <div class="pt-2">
                                    <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-extrabold rounded-xl shadow-lg shadow-emerald-500/20 text-xs uppercase tracking-wider inline-flex items-center gap-2 transition transform hover:-translate-y-0.5">
                                        Order Fresh Meals &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2: Custom Recipe Banner -->
                        <div class="swiper-slide relative h-[300px] sm:h-[380px] bg-slate-900 flex items-center overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1200" 
                                 class="absolute inset-0 w-full h-full object-cover object-center opacity-50 transition-transform duration-700 hover:scale-105" 
                                 alt="Healthy Meals">
                            
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>

                            <div class="relative z-10 p-6 sm:p-12 max-w-xl space-y-3">
                                <span class="px-3 py-1 bg-teal-500/20 border border-teal-500/40 text-teal-300 text-[10px] font-black rounded-full uppercase tracking-widest inline-block">
                                    🥗 Fresh & Organic
                                </span>
                                <h3 class="text-2xl sm:text-4xl font-black text-white leading-tight">
                                    Build Your Own Custom Recipe
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-300">
                                    Select your ingredients, add extra toppings, and enjoy personalized dining.
                                </p>
                                <div class="pt-2">
                                    <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="px-5 py-3 bg-slate-100 hover:bg-white text-slate-950 font-extrabold rounded-xl shadow-lg text-xs uppercase tracking-wider inline-flex items-center gap-2 transition transform hover:-translate-y-0.5">
                                        Customize Meal
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3: Express Delivery Banner -->
                        <div class="swiper-slide relative h-[300px] sm:h-[380px] bg-slate-900 flex items-center overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=1200" 
                                 class="absolute inset-0 w-full h-full object-cover object-center opacity-50 transition-transform duration-700 hover:scale-105" 
                                 alt="Fast Delivery">
                            
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>

                            <div class="relative z-10 p-6 sm:p-12 max-w-xl space-y-3">
                                <span class="px-3 py-1 bg-amber-500/20 border border-amber-500/40 text-amber-400 text-[10px] font-black rounded-full uppercase tracking-widest inline-block">
                                    ⚡ Express Dropoff
                                </span>
                                <h3 class="text-2xl sm:text-4xl font-black text-white leading-tight">
                                    Lightning Fast Delivery Guaranteed
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-300">
                                    Track your order in real-time right from your customer dashboard.
                                </p>
                                <div class="pt-2">
                                    <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-slate-950 font-extrabold rounded-xl shadow-lg text-xs uppercase tracking-wider inline-flex items-center gap-2 transition transform hover:-translate-y-0.5">
                                        Browse Menu Now
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

            <!-- Quick Stats Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- Total Orders -->
                <div class="p-5 rounded-2xl bg-white dark:bg-[#0b0f19]/90 border border-slate-200 dark:border-slate-800/80 shadow-lg flex items-center justify-between transition-colors duration-300">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Total Orders</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ $totalOrdersCount ?? 0 }}</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>

                <!-- Active Orders -->
                <div class="p-5 rounded-2xl bg-white dark:bg-[#0b0f19]/90 border border-slate-200 dark:border-slate-800/80 shadow-lg flex items-center justify-between transition-colors duration-300">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Active Order</p>
                        <h3 class="text-2xl font-extrabold text-amber-500 dark:text-amber-400 mt-1">{{ $activeOrdersCount ?? 0 }} Active</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-500/10 text-amber-500 dark:text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Cart Items -->
                <div class="p-5 rounded-2xl bg-white dark:bg-[#0b0f19]/90 border border-slate-200 dark:border-slate-800/80 shadow-lg flex items-center justify-between transition-colors duration-300">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Cart Items</p>
                        <h3 class="text-2xl font-extrabold text-rose-500 dark:text-rose-400 mt-1">{{ $cartCount ?? 0 }} Items</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-rose-500/10 text-rose-500 dark:text-rose-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                    </div>
                </div>

                <!-- Reward Points -->
                <div class="p-5 rounded-2xl bg-white dark:bg-[#0b0f19]/90 border border-slate-200 dark:border-slate-800/80 shadow-lg flex items-center justify-between transition-colors duration-300">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Hub Points</p>
                        <h3 class="text-2xl font-extrabold text-teal-600 dark:text-teal-400 mt-1">{{ Auth::user()->points ?? 350 }} PTS</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

            </div>

            <!-- Active Order Status Live Banner -->
            @if(isset($latestActiveOrder) && $latestActiveOrder)
                <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-50 via-white to-white dark:from-emerald-950/40 dark:via-[#0b0f19] dark:to-[#0b0f19] border border-emerald-300 dark:border-emerald-500/30 flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors duration-300">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-wider mb-2">Live Order Status</span>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                            Order #BCI-{{ $latestActiveOrder->id }} - {{ ucfirst(str_replace('_', ' ', $latestActiveOrder->status)) }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Estimated Delivery Time: <span class="text-emerald-600 dark:text-emerald-400 font-semibold">20-30 mins</span></p>
                    </div>
                    <a href="{{ Route::has('orders.show') ? route('orders.show', $latestActiveOrder->id) : '#' }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition transform hover:-translate-y-0.5 text-center">
                        Track Live Delivery
                    </a>
                </div>
            @else
                <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-50 via-white to-white dark:from-emerald-950/20 dark:via-[#0b0f19] dark:to-[#0b0f19] border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors duration-300">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px] font-bold uppercase tracking-wider mb-2">Fresh & Healthy Meals</span>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Ready for a Healthy Choice Today?</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Explore our organic menu and build your customized meal.</p>
                    </div>
                    <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition text-center">
                        Explore Menu &rarr;
                    </a>
                </div>
            @endif

            <!-- Recent Orders Table -->
            <div class="p-6 rounded-2xl bg-white dark:bg-[#0b0f19]/90 border border-slate-200 dark:border-slate-800/80 shadow-lg transition-colors duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-base text-slate-800 dark:text-slate-100">Recent Organic Orders</h3>
                    <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-bold">View All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] uppercase text-slate-500 dark:text-slate-400 font-semibold">
                                <th class="py-3 px-4">Order ID</th>
                                <th class="py-3 px-4">Items</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs text-slate-600 dark:text-slate-300">
                            @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td class="py-4 px-4 font-semibold text-emerald-600 dark:text-emerald-400">#BCI-{{ $order->id }}</td>
                                    
                                    <td class="py-4 px-4">
                                        @if(isset($order->items) && is_countable($order->items) && count($order->items) > 0)
                                            {{ count($order->items) }} Items
                                        @else
                                            Organic Meal
                                        @endif
                                    </td>
                                    
                                    <td class="py-4 px-4 font-bold text-slate-800 dark:text-slate-100">
                                        Rs. {{ number_format($order->total_price ?? $order->total_amount ?? $order->total ?? 0, 2) }}
                                    </td>
                                    
                                    <td class="py-4 px-4">
                                        @php
                                            $status = strtolower($order->status ?? 'pending');
                                            $badgeClasses = match($status) {
                                                'delivered', 'completed' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                                                'cancelled' => 'bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/20',
                                                default => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $badgeClasses }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <a href="{{ Route::has('orders.show') ? route('orders.show', $order->id) : '#' }}" class="text-slate-400 hover:text-emerald-500 font-semibold transition">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">
                                        No recent orders found. <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="text-emerald-500 underline font-semibold">Place your first order!</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts: Swiper Slider & Theme Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Swiper JS Initialization
            if (typeof Swiper !== 'undefined') {
                new Swiper(".offerSwiper", {
                    loop: true,
                    autoplay: {
                        delay: 4500,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    effect: "fade",
                    fadeEffect: {
                        crossFade: true
                    }
                });
            }

            // Dark/Light Theme Switch Logic
            const themeToggleBtn = document.getElementById('theme-toggle');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');

            // Set Initial Icon State based on HTML Class
            if (document.documentElement.classList.contains('dark')) {
                lightIcon?.classList.remove('hidden');
            } else {
                darkIcon?.classList.remove('hidden');
            }

            // Toggle Click Listener
            themeToggleBtn?.addEventListener('click', () => {
                darkIcon?.classList.toggle('hidden');
                lightIcon?.classList.toggle('hidden');

                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            });
        });
    </script>
</x-app-layout>