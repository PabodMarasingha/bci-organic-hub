<x-app-layout>
    <div class="min-h-screen bg-[#0b1329] text-slate-100 py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Kitchen Command Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900/90 border border-slate-800 p-6 rounded-3xl shadow-xl backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-lg shadow-emerald-500/10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-white tracking-tight">Kitchen Operations Command</h1>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Manage live orders and ingredient stock levels in real-time</p>
                    </div>
                </div>
            </div>

            <!-- Session Alert Message -->
            @if (session('message') || session('success'))
                <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-2xl shadow-lg">
                    <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs font-bold">{{ session('message') ?? session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Active Kitchen Orders Grid (Left side - 2 Columns) -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <span>Live Orders</span>
                            <span class="px-2.5 py-0.5 text-xs font-extrabold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                {{ isset($orders) ? count($orders) : 0 }} Active
                            </span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse ($orders ?? [] as $order)
                            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-5 shadow-xl hover:border-slate-700 transition duration-300 flex flex-col justify-between space-y-4">
                                
                                <div>
                                    <!-- Order Meta info -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Order ID</span>
                                            <h3 class="text-xl font-black text-white">#{{ $order->id }}</h3>
                                            <p class="text-xs text-slate-400 font-medium flex items-center gap-1.5 mt-0.5">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $order->user->name ?? 'Guest User' }}
                                            </p>
                                        </div>

                                        <!-- Status Badge -->
                                        <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full tracking-wider border 
                                            {{ $order->status === 'pending' ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' : '' }}
                                            {{ $order->status === 'preparing' ? 'bg-blue-500/10 text-blue-400 border-blue-500/30 animate-pulse' : '' }}
                                            {{ $order->status === 'ready' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : '' }}">
                                            {{ $order->status }}
                                        </span>
                                    </div>

                                    <hr class="border-slate-800/80 my-3">

                                    <!-- Ordered Items & Customizations -->
                                    <div class="space-y-2.5">
                                        @foreach ($order->items as $item)
                                            <div class="bg-[#141a26] border border-slate-800/60 rounded-2xl p-3">
                                                <div class="flex justify-between items-start">
                                                    <span class="text-xs font-bold text-slate-200">
                                                        <span class="text-emerald-400 font-black">{{ $item->quantity }}x</span> {{ $item->item_name }}
                                                    </span>
                                                    <span class="text-[11px] font-bold text-slate-400">Rs. {{ number_format($item->unit_price, 2) }}</span>
                                                </div>

                                                <!-- SAFE ARRAY / OBJECT CUSTOMIZATION FIX -->
                                                @if (!empty($item->customizations))
                                                    <div class="mt-2 text-[11px] text-emerald-400 font-medium bg-emerald-500/10 p-2 rounded-xl border border-emerald-500/20">
                                                        <span class="text-slate-400 font-bold">Customizations:</span> 
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

                                <!-- Order Status Action Buttons -->
                                <div class="pt-2">
                                    @if ($order->status === 'pending')
                                        <form method="POST" action="{{ route('kitchen.orders.updateStatus', $order->id) }}">
                                            @csrf 
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="preparing">
                                            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black text-xs rounded-xl shadow-lg shadow-amber-500/10 transition duration-200 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                </svg>
                                                Start Preparing
                                            </button>
                                        </form>
                                    @endif

                                    @if ($order->status === 'preparing')
                                        <form method="POST" action="{{ route('kitchen.orders.updateStatus', $order->id) }}">
                                            @csrf 
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="ready">
                                            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition duration-200 flex items-center justify-center gap-2">
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
                            <div class="col-span-2 bg-slate-900/50 border border-slate-800/80 rounded-3xl p-10 text-center">
                                <div class="w-12 h-12 mx-auto rounded-full bg-slate-800/60 flex items-center justify-center mb-3 text-slate-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-300">No active kitchen orders</h3>
                                <p class="text-xs text-slate-500 mt-1">Pending customer orders will automatically show up here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Ingredient Stock Management Sidebar (Right side - 1 Column) -->
                <div class="space-y-6">
                    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl sticky top-24">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                <span>Ingredient Stock</span>
                            </h2>
                            <span class="text-[10px] text-slate-400 font-bold bg-slate-800 border border-slate-700 px-2.5 py-1 rounded-full uppercase tracking-wider">Live Availability</span>
                        </div>

                        <p class="text-xs text-slate-400 mb-4 leading-relaxed">Toggle ingredients out of stock to automatically update menu items for customers.</p>

                        <div class="space-y-3 max-h-[520px] overflow-y-auto pr-1">
                            @forelse ($ingredients ?? [] as $ingredient)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-[#141a26] border border-slate-800/80 hover:border-slate-700 transition">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $ingredient->in_stock ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-rose-500 shadow-[0_0_8px_#f43f5e]' }}"></span>
                                        <div>
                                            <h4 class="text-xs font-bold text-white">{{ $ingredient->name }}</h4>
                                            <span class="text-[10px] font-extrabold uppercase tracking-wider {{ $ingredient->in_stock ? 'text-emerald-400' : 'text-rose-400' }}">
                                                {{ $ingredient->in_stock ? 'In Stock' : 'Out of Stock' }}
                                            </span>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('kitchen.toggleStock', $ingredient->id) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition duration-200 border 
                                            {{ $ingredient->in_stock 
                                                ? 'bg-rose-500/10 text-rose-400 border-rose-500/20 hover:bg-rose-500/20' 
                                                : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20' }}">
                                            {{ $ingredient->in_stock ? 'Mark Out' : 'Mark In' }}
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 text-center py-4">No ingredients available to manage.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>