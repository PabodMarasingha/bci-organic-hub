<div class="py-12 bg-organic-cream dark:bg-slate-950 min-h-screen text-organic-charcoal dark:text-slate-100 font-sans transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8 pb-6 border-b border-organic-green/10 dark:border-slate-800">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/30 dark:border-organic-gold/30 text-organic-green dark:text-organic-gold text-xs font-bold rounded-full uppercase tracking-widest mb-2">
                    <span class="w-2 h-2 rounded-full bg-organic-green dark:bg-organic-gold animate-pulse"></span> Order Review
                </span>
                <h1 class="font-display text-3xl font-extrabold tracking-tight">Your Cart</h1>
            </div>
            <a href="{{ route('menu') }}" class="text-xs font-semibold text-organic-green dark:text-organic-gold hover:underline flex items-center gap-1 transition">
                &larr; Back to Menu
            </a>
        </div>

        <!-- Session Message -->
        @if (session()->has('message'))
            <div class="p-4 mb-6 text-sm text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/30 dark:border-organic-gold/30 rounded-2xl" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <!-- Cart Items List -->
        <div class="space-y-4 mb-8">
            @forelse ($cart as $index => $item)
                <div class="bg-white dark:bg-slate-900/80 backdrop-blur-md border border-organic-green/10 dark:border-slate-800 hover:border-organic-gold/50 dark:hover:border-slate-700 rounded-2xl p-5 sm:p-6 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">

                    <div class="flex-1">
                        <div class="flex items-center justify-between sm:justify-start gap-3">
                            <h3 class="font-display text-lg font-bold">{{ $item['item_name'] ?? 'Item' }}</h3>
                        </div>

                        @if (!empty($item['customizations']))
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                @php
                                    $customizationNames = array_map(function($custom) {
                                        return is_array($custom) ? ($custom['name'] ?? '') : $custom;
                                    }, $item['customizations']);
                                @endphp

                                <p class="text-xs text-organic-charcoal/60 dark:text-slate-400 flex items-center gap-1">
                                    <span class="text-organic-green dark:text-organic-gold font-semibold">Add-ons:</span>
                                    {{ implode(', ', array_filter($customizationNames)) }}
                                </p>
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center gap-4 mt-2">
                            <p class="text-xs text-organic-charcoal/60 dark:text-slate-400">
                                Price: <span class="text-organic-green dark:text-organic-gold font-medium">LKR {{ number_format($item['unit_price'] ?? 0, 2) }}</span>
                            </p>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-1 bg-organic-cream dark:bg-slate-950 border border-organic-green/15 dark:border-slate-800 rounded-lg px-1.5 py-1">
                                <span class="text-[10px] font-bold text-organic-charcoal/50 dark:text-slate-500 uppercase mr-1">Qty:</span>
                                <button type="button"
                                        wire:click="updateQuantity({{ $index }}, {{ max(1, ($item['quantity'] ?? 1) - 1) }})"
                                        class="w-5 h-5 flex items-center justify-center bg-white dark:bg-slate-800 hover:bg-organic-green hover:text-white dark:hover:bg-slate-700 text-organic-charcoal dark:text-slate-300 rounded-md text-xs font-bold transition">-</button>
                                <span class="w-6 text-center text-xs font-black text-organic-green dark:text-organic-gold">{{ $item['quantity'] ?? 1 }}</span>
                                <button type="button"
                                        wire:click="updateQuantity({{ $index }}, {{ ($item['quantity'] ?? 1) + 1 }})"
                                        class="w-5 h-5 flex items-center justify-center bg-white dark:bg-slate-800 hover:bg-organic-green hover:text-white dark:hover:bg-slate-700 text-organic-charcoal dark:text-slate-300 rounded-md text-xs font-bold transition">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-organic-green/10 dark:border-slate-800">
                        <div class="text-left sm:text-right">
                            <span class="text-[10px] text-organic-charcoal/50 dark:text-slate-500 uppercase tracking-wider block font-semibold">Subtotal</span>
                            <span class="text-lg font-black text-organic-green dark:text-organic-gold">
                                LKR {{ number_format(($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}
                            </span>
                        </div>

                        <button type="button"
                                wire:click="removeFromCart({{ $index }})"
                                class="p-2.5 text-organic-charcoal/40 dark:text-slate-500 hover:text-organic-tomato hover:bg-organic-tomato/10 rounded-xl transition duration-150 flex items-center gap-1 text-sm font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900/50 border border-organic-green/10 dark:border-slate-800 rounded-3xl p-12 text-center shadow-sm">
                    <div class="w-16 h-16 bg-organic-cream dark:bg-slate-800 text-organic-charcoal/40 dark:text-slate-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-bold mb-2">Your Cart is Empty</h3>
                    <p class="text-organic-charcoal/60 dark:text-slate-400 text-sm mb-6 max-w-sm mx-auto">
                        Browse our menu and build your custom meal!
                    </p>
                    <a href="{{ route('menu') }}" class="inline-flex px-6 py-3 bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/30 dark:border-organic-gold/30 text-organic-green dark:text-organic-gold font-bold text-sm rounded-xl hover:bg-organic-green dark:hover:bg-organic-gold hover:text-white dark:hover:text-organic-charcoal transition">
                        Browse the Menu &rarr;
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Footer Summary Bar -->
        @if (!empty($cart))
            <div class="bg-white dark:bg-slate-900/90 border border-organic-green/10 dark:border-slate-800 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-lg">
                <div>
                    <span class="text-xs font-semibold text-organic-charcoal/60 dark:text-slate-400 uppercase tracking-widest block">Total Estimated Amount</span>
                    <div class="font-display text-3xl font-black text-organic-green dark:text-organic-gold mt-1">
                        LKR {{ number_format(collect($cart)->sum(fn($i) => ($i['unit_price'] ?? 0) * ($i['quantity'] ?? 1)), 2) }}
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="button"
                            wire:click="clearCart"
                            class="px-5 py-4 bg-organic-cream dark:bg-slate-800 hover:bg-organic-green/10 dark:hover:bg-slate-700 text-organic-charcoal/70 dark:text-slate-300 font-bold rounded-2xl transition duration-150 text-sm">
                        Clear Cart
                    </button>

                    <a href="{{ route('orders.create') }}"
                       class="w-full sm:w-auto px-8 py-4 bg-organic-green hover:bg-organic-green-light dark:bg-organic-gold dark:hover:bg-organic-gold/90 text-white dark:text-organic-charcoal font-extrabold rounded-2xl shadow-lg transform active:scale-95 transition-all duration-200 text-center flex items-center justify-center space-x-2">
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