<x-app-layout>
    <div class="py-12 bg-organic-cream dark:bg-[#0b1329] min-h-screen text-organic-charcoal dark:text-slate-100 font-sans selection:bg-organic-gold selection:text-organic-charcoal transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-organic-green/10 dark:border-slate-800/80 gap-4">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/30 dark:border-organic-gold/30 text-organic-green dark:text-organic-gold text-xs font-bold rounded-full uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-organic-green dark:bg-organic-gold animate-ping"></span> Order Review
                    </span>
                    <h1 class="font-display text-3xl sm:text-4xl font-black tracking-tight">Your Cart</h1>
                </div>

                <div class="flex items-center gap-3">
                    @if(!empty($cart))
                        <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2.5 bg-organic-tomato/10 hover:bg-organic-tomato/20 border border-organic-tomato/30 text-organic-tomato text-xs font-bold rounded-xl transition duration-200">
                                Clear Cart
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('menu') }}" class="group inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 hover:bg-organic-cream dark:hover:bg-slate-800 border border-organic-green/15 dark:border-slate-800 hover:border-organic-green/40 dark:hover:border-slate-700 text-organic-charcoal/80 dark:text-slate-300 hover:text-organic-green dark:hover:text-white text-xs font-bold rounded-xl transition duration-300 shadow-sm">
                        <span class="transform group-hover:-translate-x-1 transition duration-200">&larr;</span> Back to Menu
                    </a>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="flex items-center gap-3 p-4 text-xs sm:text-sm text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/30 dark:border-organic-gold/30 rounded-2xl shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="flex items-center gap-3 p-4 text-xs sm:text-sm text-organic-tomato bg-organic-tomato/10 border border-organic-tomato/30 rounded-2xl shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Cart Items Loop -->
            <div class="space-y-4">
                @forelse ($cart as $index => $item)
                    <div class="group relative bg-white dark:bg-slate-900/80 backdrop-blur-xl border border-organic-green/10 dark:border-slate-800/90 hover:border-organic-gold/60 dark:hover:border-organic-gold/40 rounded-3xl p-5 sm:p-6 transition-all duration-300 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-5 hover:-translate-y-0.5">

                        <div class="flex-1 space-y-2">
                            <div class="flex items-center justify-between sm:justify-start gap-3">
                                <h3 class="font-display text-lg font-extrabold group-hover:text-organic-green dark:group-hover:text-organic-gold transition duration-200">
                                    {{ $item['item_name'] ?? 'Custom Meal' }}
                                </h3>
                            </div>

                            @if (!empty($item['customizations']))
                                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                    <span class="text-[11px] font-black uppercase text-organic-green/80 dark:text-organic-gold/80 tracking-wider">Add-ons:</span>
                                    @foreach ($item['customizations'] as $custom)
                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/20 dark:border-organic-gold/20 text-organic-green dark:text-organic-gold text-[11px] font-semibold rounded-md">
                                            + {{ is_array($custom) ? ($custom['name'] ?? '') : $custom }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if(!empty($item['special_instructions']))
                                <p class="text-xs text-organic-charcoal/60 dark:text-slate-400 italic flex items-center gap-1">
                                    <span class="text-organic-charcoal/40 dark:text-slate-500 font-bold not-italic">Note:</span> "{{ $item['special_instructions'] }}"
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center gap-4 pt-2">
                                <p class="text-xs text-organic-charcoal/60 dark:text-slate-400">
                                    Unit Price: <span class="text-organic-charcoal dark:text-slate-200 font-semibold">LKR {{ number_format($item['unit_price'] ?? 0, 2) }}</span>
                                </p>

                                <form method="POST" action="{{ route('cart.update', $index) }}" class="flex items-center gap-2 bg-organic-cream dark:bg-slate-950/80 border border-organic-green/15 dark:border-slate-800 rounded-xl px-2 py-1">
                                    @csrf
                                    @method('PATCH')
                                    <label for="qty-{{ $index }}" class="text-[10px] font-bold text-organic-charcoal/50 dark:text-slate-500 uppercase">Qty:</label>
                                    <input type="number" id="qty-{{ $index }}" name="quantity" value="{{ $item['quantity'] ?? 1 }}" min="1" max="99"
                                           onchange="this.form.submit()"
                                           class="w-12 bg-transparent text-xs font-black text-organic-green dark:text-organic-gold text-center border-0 p-0 focus:ring-0 outline-none">
                                </form>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-6 pt-4 sm:pt-0 border-t sm:border-t-0 border-organic-green/10 dark:border-slate-800/80">
                            <div class="text-left sm:text-right">
                                <span class="text-[10px] text-organic-charcoal/50 dark:text-slate-500 uppercase tracking-widest block font-bold">Item Subtotal</span>
                                <span class="text-xl font-black text-organic-green dark:text-organic-gold">
                                    LKR {{ number_format(($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}
                                </span>
                            </div>

                            <form method="POST" action="{{ route('cart.remove', $index) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-3 text-organic-charcoal/40 dark:text-slate-500 hover:text-organic-tomato bg-organic-cream dark:bg-slate-800/50 hover:bg-organic-tomato/10 border border-organic-green/10 dark:border-slate-800 hover:border-organic-tomato/30 rounded-2xl transition duration-200 flex items-center gap-1.5 text-xs font-bold group/btn shadow-sm"
                                        title="Remove item">
                                    <svg class="w-4 h-4 transition group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Remove</span>
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900/40 border border-organic-green/10 dark:border-slate-800/80 rounded-3xl p-12 text-center shadow-md">
                        <div class="w-20 h-20 bg-organic-cream dark:bg-slate-800/80 border border-organic-green/15 dark:border-slate-700/60 text-organic-charcoal/40 dark:text-slate-500 rounded-3xl flex items-center justify-center mx-auto mb-5">
                            <svg class="w-10 h-10 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                            </svg>
                        </div>
                        <h3 class="font-display text-2xl font-black mb-2">Your Cart is Empty</h3>
                        <p class="text-organic-charcoal/60 dark:text-slate-400 text-xs sm:text-sm mb-6 max-w-sm mx-auto leading-relaxed">
                            Looks like you haven't chosen your healthy meal yet. Explore our delicious menu to build your custom order!
                        </p>
                        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-organic-green hover:bg-organic-green-light dark:bg-organic-gold dark:hover:bg-organic-gold/90 text-white dark:text-organic-charcoal font-black text-xs rounded-2xl shadow-lg transition duration-300 transform hover:-translate-y-0.5">
                            Browse Healthy Menu &rarr;
                        </a>
                    </div>
                @endforelse
            </div>

            @if (!empty($cart))
                <div class="bg-white dark:bg-slate-900/90 backdrop-blur-2xl border border-organic-green/10 dark:border-slate-800 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-lg">
                    <div>
                        <span class="text-xs font-bold text-organic-charcoal/60 dark:text-slate-400 uppercase tracking-widest block">Total Estimated Amount</span>
                        <div class="font-display text-3xl sm:text-4xl font-black text-organic-green dark:text-organic-gold mt-1">
                            LKR {{ number_format(collect($cart)->sum(fn($i) => ($i['unit_price'] ?? 0) * ($i['quantity'] ?? 1)), 2) }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('orders.create') }}"
                           class="w-full sm:w-auto px-8 py-4 bg-organic-green hover:bg-organic-green-light dark:bg-organic-gold dark:hover:bg-organic-gold/90 text-white dark:text-organic-charcoal font-black text-sm rounded-2xl shadow-lg transform active:scale-95 transition-all duration-200 text-center flex items-center justify-center gap-2">
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
</x-app-layout>