<x-app-layout>
    <div class="min-h-screen bg-organic-cream dark:bg-[#0b1329] text-organic-charcoal dark:text-slate-100 py-10 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Kitchen Command Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-900/90 border border-organic-green/10 dark:border-slate-800 p-6 rounded-3xl shadow-md">
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-2xl bg-organic-green/10 dark:bg-organic-gold/10 text-organic-green dark:text-organic-gold border border-organic-green/20 dark:border-organic-gold/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-display text-2xl font-black tracking-tight">Kitchen Operations Command</h1>
                        <p class="text-xs text-organic-charcoal/60 dark:text-slate-400 font-medium mt-0.5">Manage live orders and ingredient stock levels in real-time</p>
                    </div>
                </div>
            </div>

            @if (session('message') || session('success'))
                <div class="flex items-center gap-3 bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/30 dark:border-organic-gold/30 text-organic-green dark:text-organic-gold p-4 rounded-2xl shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs font-bold">{{ session('message') ?? session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">

                    <!-- SECTION 1: Active Live Orders -->
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <h2 class="font-display text-lg font-bold flex items-center gap-2">
                                <span>Active Live Orders</span>
                                <span class="px-2.5 py-0.5 text-xs font-extrabold rounded-full bg-organic-gold/10 text-organic-gold border border-organic-gold/30">
                                    {{ count($orders ?? []) }} Active
                                </span>
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse ($orders ?? [] as $order)
                                <div class="bg-white dark:bg-slate-900/90 border border-organic-green/10 dark:border-slate-800 rounded-3xl p-5 shadow-md hover:border-organic-gold/40 dark:hover:border-slate-700 transition duration-300 flex flex-col justify-between space-y-4">

                                    <div>
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <span class="text-[10px] font-black text-organic-charcoal/40 dark:text-slate-500 uppercase tracking-widest">Order ID</span>
                                                <h3 class="font-display text-xl font-black">#{{ $order->id }}</h3>
                                                <p class="text-xs text-organic-charcoal/60 dark:text-slate-400 font-medium flex items-center gap-1.5 mt-0.5">
                                                    <svg class="w-3.5 h-3.5 text-organic-charcoal/40 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $order->user->name ?? 'Guest User' }}
                                                </p>
                                            </div>

                                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full tracking-wider border
                                                {{ $order->status === 'pending' ? 'bg-organic-gold/10 text-organic-gold border-organic-gold/30' : '' }}
                                                {{ in_array($order->status, ['preparing', 'processing']) ? 'bg-organic-green/10 text-organic-green dark:bg-organic-gold/10 dark:text-organic-gold border-organic-green/30 dark:border-organic-gold/30 animate-pulse' : '' }}">
                                                {{ $order->status }}
                                            </span>
                                        </div>

                                        <hr class="border-organic-green/10 dark:border-slate-800/80 my-3">

                                        <div class="space-y-2.5">
                                            @foreach ($order->items as $item)
                                                <div class="bg-organic-cream dark:bg-[#141a26] border border-organic-green/10 dark:border-slate-800/60 rounded-2xl p-3">
                                                    <div class="flex justify-between items-start">
                                                        <span class="text-xs font-bold text-organic-charcoal dark:text-slate-200">
                                                            <span class="text-organic-green dark:text-organic-gold font-black">{{ $item->quantity }}x</span> {{ $item->item_name }}
                                                        </span>
                                                        <span class="text-[11px] font-bold text-organic-charcoal/60 dark:text-slate-400">Rs. {{ number_format($item->unit_price, 2) }}</span>
                                                    </div>

                                                    @if (!empty($item->customizations))
                                                        <div class="mt-2 text-[11px] text-organic-green dark:text-organic-gold font-medium bg-organic-green/10 dark:bg-organic-gold/10 p-2 rounded-xl border border-organic-green/20 dark:border-organic-gold/20">
                                                            <span class="text-organic-charcoal/60 dark:text-slate-400 font-bold">Customizations:</span>
                                                            @if (is_array($item->customizations) || is_object($item->customizations))
                                                                {{ collect($item->customizations)->map(function($c) {
                                                                    if (is_array($c)) return $c['name'] ?? $c['title'] ?? implode(' ', array_filter($c, 'is_string'));
                                                                    if (is_object($c)) return $c->name ?? $c->title ?? json_encode($c);
                                                                    return $c;
                                                                })->implode(', ') }}
                                                            @else
                                                                {{ $item->customizations }}
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="pt-2">
                                        @if ($order->status === 'pending')
                                            <form method="POST" action="{{ route('kitchen.orders.updateStatus', $order->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="preparing">
                                                <button type="submit" class="w-full py-2.5 bg-organic-gold hover:bg-organic-gold/90 text-organic-charcoal font-black text-xs rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    </svg>
                                                    Start Preparing
                                                </button>
                                            </form>
                                        @endif

                                        @if (in_array($order->status, ['preparing', 'processing']))
                                            <form method="POST" action="{{ route('kitchen.orders.updateStatus', $order->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="ready">
                                                <button type="submit" class="w-full py-2.5 bg-organic-green hover:bg-organic-green-light text-white font-black text-xs rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Mark as Ready
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                </div>
                            @empty
                                <div class="col-span-2 bg-white dark:bg-slate-900/50 border border-organic-green/10 dark:border-slate-800/80 rounded-3xl p-8 text-center">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-organic-cream dark:bg-slate-800/60 flex items-center justify-center mb-3 text-organic-charcoal/40 dark:text-slate-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-bold">No active kitchen orders</h3>
                                    <p class="text-xs text-organic-charcoal/50 dark:text-slate-500 mt-1">Pending customer orders will automatically show up here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- SECTION 2: Completed / Ready Orders -->
                    <div class="space-y-5 pt-4 border-t border-organic-green/10 dark:border-slate-800/80">
                        <div class="flex items-center justify-between">
                            <h2 class="font-display text-lg font-bold flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-organic-green dark:bg-organic-gold animate-pulse"></span>
                                <span>Completed Ready Orders</span>
                                <span class="px-2.5 py-0.5 text-xs font-extrabold rounded-full bg-organic-green/10 dark:bg-organic-gold/10 text-organic-green dark:text-organic-gold border border-organic-green/20 dark:border-organic-gold/20">
                                    {{ isset($readyOrders) ? count($readyOrders) : 0 }} Ready
                                </span>
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse ($readyOrders ?? [] as $readyOrder)
                                <div class="bg-white dark:bg-slate-900/90 border border-organic-green/30 dark:border-organic-gold/30 rounded-3xl p-5 shadow-md flex flex-col justify-between space-y-4">
                                    <div>
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <span class="text-[10px] font-black text-organic-charcoal/40 dark:text-slate-500 uppercase tracking-widest">Order ID</span>
                                                <h3 class="font-display text-xl font-black">#{{ $readyOrder->id }}</h3>
                                                <p class="text-xs text-organic-charcoal/60 dark:text-slate-400 font-medium mt-0.5">
                                                    {{ $readyOrder->user->name ?? 'Guest User' }}
                                                </p>
                                            </div>
                                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full tracking-wider border bg-organic-green/10 dark:bg-organic-gold/10 text-organic-green dark:text-organic-gold border-organic-green/30 dark:border-organic-gold/30">
                                                READY
                                            </span>
                                        </div>

                                        <hr class="border-organic-green/10 dark:border-slate-800/80 my-3">

                                        <div class="space-y-2">
                                            @foreach ($readyOrder->items as $item)
                                                <div class="flex justify-between text-xs text-organic-charcoal/80 dark:text-slate-300 bg-organic-cream dark:bg-[#141a26]/60 p-2.5 rounded-xl border border-organic-green/10 dark:border-slate-800/50">
                                                    <span><strong class="text-organic-green dark:text-organic-gold">{{ $item->quantity }}x</strong> {{ $item->item_name }}</span>
                                                    <span class="text-organic-charcoal/60 dark:text-slate-400 font-medium">Rs. {{ number_format($item->unit_price, 2) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="text-[11px] text-organic-charcoal/50 dark:text-slate-500 text-right pt-2 border-t border-organic-green/10 dark:border-slate-800/50">
                                        Ready at: {{ optional($readyOrder->updated_at)->format('h:i A') ?? 'N/A' }}
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 bg-white dark:bg-slate-900/30 border border-organic-green/10 dark:border-slate-800/60 rounded-3xl p-6 text-center">
                                    <p class="text-xs text-organic-charcoal/50 dark:text-slate-500">No completed ready orders available at the moment.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Ingredient Stock Management Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-900/90 border border-organic-green/10 dark:border-slate-800 rounded-3xl p-6 shadow-md sticky top-24">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-display text-lg font-bold flex items-center gap-2">
                                <span>Ingredient Stock</span>
                            </h2>
                            <span class="text-[10px] text-organic-charcoal/60 dark:text-slate-400 font-bold bg-organic-cream dark:bg-slate-800 border border-organic-green/15 dark:border-slate-700 px-2.5 py-1 rounded-full uppercase tracking-wider">Live Availability</span>
                        </div>

                        <p class="text-xs text-organic-charcoal/60 dark:text-slate-400 mb-4 leading-relaxed">Toggle ingredients out of stock to automatically update menu items for customers.</p>

                        <div class="space-y-3 max-h-[520px] overflow-y-auto pr-1">
                            @forelse ($ingredients ?? [] as $ingredient)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-organic-cream dark:bg-[#141a26] border border-organic-green/10 dark:border-slate-800/80 hover:border-organic-gold/40 dark:hover:border-slate-700 transition">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $ingredient->in_stock ? 'bg-organic-green dark:bg-organic-gold' : 'bg-organic-tomato' }}"></span>
                                        <div>
                                            <h4 class="text-xs font-bold">{{ $ingredient->name }}</h4>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[10px] font-extrabold uppercase tracking-wider {{ $ingredient->in_stock ? 'text-organic-green dark:text-organic-gold' : 'text-organic-tomato' }}">
                                                    {{ $ingredient->in_stock ? 'In Stock' : 'Out of Stock' }}
                                                </span>
                                                @if (isset($ingredient->quantity))
                                                    <span class="text-[10px] text-organic-charcoal/40 dark:text-slate-500 font-bold">• Qty: {{ $ingredient->quantity }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('kitchen.toggleStock', $ingredient->id) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition duration-200 border
                                            {{ $ingredient->in_stock
                                                ? 'bg-organic-tomato/10 text-organic-tomato border-organic-tomato/20 hover:bg-organic-tomato/20'
                                                : 'bg-organic-green/10 dark:bg-organic-gold/10 text-organic-green dark:text-organic-gold border-organic-green/20 dark:border-organic-gold/20 hover:bg-organic-green/20 dark:hover:bg-organic-gold/20' }}">
                                            {{ $ingredient->in_stock ? 'Mark Out' : 'Mark In' }}
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="p-4 text-center bg-organic-cream dark:bg-slate-900/40 rounded-2xl border border-organic-green/10 dark:border-slate-800/60">
                                    <p class="text-xs text-organic-charcoal/50 dark:text-slate-500">No ingredients configured yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>