<x-app-layout>
    <div class="py-10 bg-organic-cream dark:bg-[#0b1329] min-h-screen text-organic-charcoal dark:text-slate-100 font-sans selection:bg-organic-gold selection:text-organic-charcoal transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Header Section -->
            <div class="relative overflow-hidden bg-white dark:bg-slate-900/60 backdrop-blur-xl border border-organic-green/10 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-md transition duration-300 hover:border-organic-gold/40 dark:hover:border-slate-700/80">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-organic-green/10 dark:bg-organic-gold/10 rounded-full blur-3xl pointer-events-none animate-pulse"></div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/20 dark:border-organic-gold/20 text-organic-green dark:text-organic-gold rounded-2xl">
                            <svg class="w-8 h-8 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="font-display text-2xl sm:text-3xl font-black tracking-tight">Delivery Operations</h1>
                                <span class="flex h-2.5 w-2.5 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-organic-green dark:bg-organic-gold opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-organic-green dark:bg-organic-gold"></span>
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-organic-charcoal/60 dark:text-slate-400 mt-1">Manage active zone dispatches, driver assignments, and customer dropoffs.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative overflow-hidden bg-white dark:bg-slate-900/60 backdrop-blur-xl border border-organic-green/10 dark:border-slate-800/80 rounded-3xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:border-organic-green/40 dark:hover:border-blue-500/40">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-organic-charcoal/60 dark:text-slate-400 uppercase tracking-widest block">Active Tasks</span>
                            <span class="text-3xl font-black text-organic-green dark:text-blue-400 mt-1 block">{{ count($deliveries) }}</span>
                        </div>
                        <div class="p-3 bg-organic-green/10 dark:bg-blue-500/10 border border-organic-green/20 dark:border-blue-500/20 text-organic-green dark:text-blue-400 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-white dark:bg-slate-900/60 backdrop-blur-xl border border-organic-green/10 dark:border-slate-800/80 rounded-3xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:border-organic-gold/40">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-organic-charcoal/60 dark:text-slate-400 uppercase tracking-widest block">Completed Today</span>
                            <span class="text-3xl font-black text-organic-green dark:text-organic-gold mt-1 block">{{ $completedTodayCount ?? 0 }}</span>
                        </div>
                        <div class="p-3 bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/20 dark:border-organic-gold/20 text-organic-green dark:text-organic-gold rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-white dark:bg-slate-900/60 backdrop-blur-xl border border-organic-green/10 dark:border-slate-800/80 rounded-3xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:border-amber-500/40">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-organic-charcoal/60 dark:text-slate-400 uppercase tracking-widest block">Cash Collected Today</span>
                            <span class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-1 block">LKR {{ number_format($totalCollectedToday ?? 0, 2) }}</span>
                        </div>
                        <div class="p-3 bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="flex items-center justify-between p-4 text-xs sm:text-sm text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/30 dark:border-organic-gold/30 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">{{ session('message') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-organic-green dark:text-organic-gold hover:opacity-70">&times;</button>
                </div>
            @endif

            <!-- Active Deliveries Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-xl font-extrabold flex items-center gap-2">
                        <span>Active Zone Deliveries</span>
                        <span class="px-3 py-0.5 bg-organic-green/10 dark:bg-organic-gold/10 border border-organic-green/30 dark:border-organic-gold/30 text-organic-green dark:text-organic-gold text-xs font-bold rounded-full">
                            {{ count($deliveries) }} Pending
                        </span>
                    </h2>
                </div>

                @forelse ($deliveries as $delivery)
                    @php
                        $order = $delivery->customerOrder ?? $delivery->order;
                        $paymentMethod = strtolower($order->payment_method ?? $order->payment_type ?? '');
                        $isCod = in_array($paymentMethod, ['cod', 'cash', 'cash_on_delivery']);
                        $totalAmount = $order->total_amount ?? $order->total_price ?? $order->grand_total ?? 0;
                        $status = $delivery->delivery_status ?? $delivery->status;
                    @endphp

                    <div class="group relative bg-white dark:bg-slate-900/80 backdrop-blur-xl border border-organic-green/10 dark:border-slate-800/90 hover:border-organic-gold/50 rounded-3xl p-6 transition-all duration-300 shadow-md hover:-translate-y-1">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                            <div class="space-y-4 flex-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="px-3 py-1 bg-organic-cream dark:bg-slate-950 text-organic-green dark:text-organic-gold border border-organic-green/30 dark:border-organic-gold/30 text-xs font-black rounded-xl">
                                        Order #{{ $order->id ?? $delivery->order_id }}
                                    </span>
                                    <span class="text-xs text-organic-charcoal/60 dark:text-slate-400">
                                        Zone: <span class="text-organic-charcoal dark:text-slate-200 font-bold">{{ $order->deliveryZone->name ?? 'Assigned Zone' }}</span>
                                    </span>
                                    <span class="text-xs text-organic-charcoal/30 dark:text-slate-500">|</span>
                                    <span class="text-xs text-organic-charcoal/60 dark:text-slate-400">
                                        Placed: <span class="text-organic-charcoal dark:text-slate-200 font-semibold">{{ $delivery->created_at ? $delivery->created_at->diffForHumans() : 'Recently' }}</span>
                                    </span>

                                    <span class="px-3 py-1 text-[11px] font-black uppercase tracking-wider rounded-lg border
                                        {{ $status === 'picked_up' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/30 animate-pulse' : 'bg-organic-green/10 dark:bg-blue-500/10 text-organic-green dark:text-blue-400 border-organic-green/30 dark:border-blue-500/30' }}">
                                        {{ str_replace('_', ' ', $status) }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400">Payment:</span>
                                    @if($isCod)
                                        <span class="px-3 py-1 bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/40 text-xs font-extrabold rounded-full flex items-center gap-1.5">
                                            <span>💵</span> CASH ON DELIVERY (Collect LKR {{ number_format($totalAmount, 2) }})
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-organic-green/15 dark:bg-organic-gold/15 text-organic-green dark:text-organic-gold border border-organic-green/40 dark:border-organic-gold/40 text-xs font-extrabold rounded-full flex items-center gap-1.5">
                                            <span>💳</span> CARD PAYMENT (Paid Online)
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-bold group-hover:text-organic-green dark:group-hover:text-organic-gold transition duration-200">
                                            {{ $order->user->name ?? 'Customer Name' }}
                                        </h3>
                                        @if(isset($order->user->phone))
                                            <a href="tel:{{ $order->user->phone }}" class="inline-flex items-center gap-1 text-xs font-bold text-organic-green dark:text-organic-gold hover:opacity-80 bg-organic-green/10 dark:bg-organic-gold/10 px-2.5 py-1 rounded-lg border border-organic-green/20 dark:border-organic-gold/20 transition hover:bg-organic-green/20 dark:hover:bg-organic-gold/20">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                Call {{ $order->user->phone }}
                                            </a>
                                        @endif
                                    </div>

                                    @php
                                        $address = $delivery->dropoff_address ?? $order->dropoff_address ?? $order->address ?? null;
                                    @endphp
                                    <p class="text-xs sm:text-sm text-organic-charcoal/80 dark:text-slate-300 mt-2 flex items-start gap-1.5">
                                        <svg class="w-4 h-4 text-organic-green dark:text-organic-gold shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span>{{ $address ?? 'Location details not provided' }}</span>
                                    </p>

                                    @if($address)
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:opacity-80 mt-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            Open Navigation Map
                                        </a>
                                    @endif
                                </div>

                                @if(isset($order->items) && $order->items->count())
                                    <div class="p-3 rounded-2xl bg-organic-cream dark:bg-slate-950/60 border border-organic-green/10 dark:border-slate-800/80">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-organic-charcoal/50 dark:text-slate-500 mb-1.5">Order Items</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($order->items as $item)
                                                <span class="text-xs px-2.5 py-1 rounded-xl bg-white dark:bg-slate-800 text-organic-charcoal dark:text-slate-200 font-medium border border-organic-green/15 dark:border-slate-700/60">
                                                    {{ $item->quantity }}x {{ $item->name ?? $item->product_name ?? 'Item' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($order->special_instructions))
                                    <p class="text-xs text-amber-700 dark:text-amber-400/90 italic bg-amber-500/5 p-2.5 rounded-xl border border-amber-500/10 inline-block">
                                        <span class="font-bold not-italic">Note:</span> "{{ $order->special_instructions }}"
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end justify-between gap-4 pt-4 lg:pt-0 border-t lg:border-t-0 border-organic-green/10 dark:border-slate-800 w-full sm:w-auto">
                                <div class="text-left lg:text-right">
                                    <span class="text-[10px] text-organic-charcoal/50 dark:text-slate-500 uppercase font-bold tracking-widest">Total Amount</span>
                                    <span class="text-2xl font-black text-organic-green dark:text-organic-gold block">
                                        LKR {{ number_format($totalAmount, 2) }}
                                    </span>
                                </div>

                                <form method="POST" action="{{ route('delivery.updateStatus', $delivery->id) }}" class="w-full sm:w-auto space-y-3">
                                    @csrf
                                    @method('PATCH')

                                    @if(in_array($status, ['unassigned', 'assigned', 'pending']))
                                        <input type="hidden" name="delivery_status" value="picked_up">
                                        <button type="submit"
                                                class="w-full px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white dark:text-slate-950 font-black text-xs rounded-2xl shadow-md transition transform active:scale-95 flex items-center justify-center gap-2">
                                            <span>Accept & Pick Up Order</span>
                                            <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </button>
                                    @elseif($status === 'picked_up' || $status === 'out_for_delivery')
                                        <input type="hidden" name="delivery_status" value="delivered">

                                        @if($isCod)
                                            <div class="flex items-center gap-2.5 p-3 bg-organic-cream dark:bg-slate-950/80 border border-amber-500/30 rounded-2xl">
                                                <input type="checkbox" name="is_cash_collected" id="cash_check_{{ $delivery->id }}" required class="rounded text-organic-green dark:text-organic-gold focus:ring-organic-green dark:focus:ring-organic-gold w-4 h-4 bg-white dark:bg-slate-900 border-organic-green/30 dark:border-slate-700">
                                                <label for="cash_check_{{ $delivery->id }}" class="text-[11px] text-amber-700 dark:text-amber-300 font-bold cursor-pointer select-none leading-tight">
                                                    I collected LKR {{ number_format($totalAmount, 2) }} cash.
                                                </label>
                                            </div>
                                        @endif

                                        <button type="submit"
                                                onclick="return confirm('Confirm order delivered and mark completed?');"
                                                class="w-full px-6 py-3 bg-organic-green hover:bg-organic-green-light dark:bg-organic-gold dark:hover:bg-organic-gold/90 text-white dark:text-organic-charcoal font-black text-xs rounded-2xl shadow-md transition transform active:scale-95 flex items-center justify-center gap-2">
                                            <span>Mark as Delivered</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    @endif
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900/40 border border-organic-green/10 dark:border-slate-800/80 rounded-3xl p-16 text-center shadow-md">
                        <div class="w-20 h-20 bg-organic-cream dark:bg-slate-800/60 border border-organic-green/15 dark:border-slate-700/60 text-organic-green dark:text-organic-gold rounded-3xl flex items-center justify-center mx-auto mb-5">
                            <svg class="w-10 h-10 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-2xl font-black mb-2">No Deliveries Available</h3>
                        <p class="text-organic-charcoal/60 dark:text-slate-400 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                            There are currently no active deliveries in your assigned delivery zone. New dispatch requests will appear here automatically.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>