<div class="py-12 bg-slate-950 min-h-screen text-slate-100 font-sans">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-800">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-widest mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Order Review
                </span>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Your Cart</h1>
            </div>
            <a href="{{ route('menu') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 flex items-center gap-1 transition">
                &larr; Back to Menu
            </a>
        </div>

        @if (session()->has('message'))
            <div class="p-4 mb-6 text-sm text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <!-- Cart Items List (Forelse Loop) -->
        <div class="space-y-4 mb-8">
            @forelse ($cart as $index => $item)
                <div class="bg-slate-900/80 backdrop-blur-md border border-slate-800 hover:border-slate-700 rounded-2xl p-5 sm:p-6 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    <!-- Item Details -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between sm:justify-start gap-3">
                            <h3 class="text-lg font-bold text-white">{{ $item['item_name'] ?? 'Item' }}</h3>
                            <span class="px-2 py-0.5 bg-slate-800 text-slate-300 text-xs font-bold rounded-md border border-slate-700">
                                Qty: {{ $item['quantity'] ?? 1 }}
                            </span>
                        </div>

                        <!-- Customizations Display -->
                        @if (!empty($item['customizations']))
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                @php
                                    $customizationNames = array_map(function($custom) {
                                        return is_array($custom) ? ($custom['name'] ?? '') : $custom;
                                    }, $item['customizations']);
                                @endphp
                                
                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                    <span class="text-emerald-400 font-semibold">Add-ons:</span>
                                    {{ implode(', ', array_filter($customizationNames)) }}
                                </p>
                            </div>
                        @endif

                        <p class="text-xs text-slate-400 mt-1">
                            Price: <span class="text-emerald-400 font-medium">LKR {{ number_format($item['unit_price'] ?? 0, 2) }}</span>
                        </p>
                    </div>

                    <!-- Price & Action Button -->
                    <div class="flex items-center justify-between sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-800">
                        <div class="text-left sm:text-right">
                            <span class="text-[10px] text-slate-500 uppercase tracking-wider block font-semibold">Subtotal</span>
                            <span class="text-lg font-black text-emerald-400">
                                LKR {{ number_format(($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}
                            </span>
                        </div>

                        <button type="button" 
                                wire:click="removeFromCart({{ $index }})" 
                                class="p-2.5 text-slate-500 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition duration-150 flex items-center gap-1 text-sm font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span class="hidden sm:inline">Remove</span>
                        </button>
                    </div>

                </div>
            @empty
                <!-- Empty Cart State -->
                <div class="bg-slate-900/50 border border-slate-800 rounded-3xl p-12 text-center">
                    <div class="w-16 h-16 bg-slate-800 text-slate-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Your Cart is Empty</h3>
                    <p class="text-slate-400 text-sm mb-6 max-w-sm mx-auto">
                        Browse our menu and build your custom meal!
                    </p>
                    <a href="{{ route('menu') }}" class="inline-flex px-6 py-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold text-sm rounded-xl hover:bg-emerald-500 hover:text-slate-950 transition">
                        Browse the Menu &rarr;
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Footer Summary Bar -->
        @if (!empty($cart))
            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-2xl">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Total Estimated Amount</span>
                    <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200 mt-1">
                        LKR {{ number_format(collect($cart)->sum(fn($i) => ($i['unit_price'] ?? 0) * ($i['quantity'] ?? 1)), 2) }}
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="button" 
                            wire:click="clearCart" 
                            class="px-5 py-4 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-2xl transition duration-150 text-sm">
                        Clear Cart
                    </button>

                    <a href="{{ route('orders.create') }}" 
                       class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-extrabold rounded-2xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transform active:scale-95 transition-all duration-200 text-center flex items-center justify-center space-x-2">
                        <span>Proceed to Checkout</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>