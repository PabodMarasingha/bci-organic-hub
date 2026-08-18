<x-app-layout>
    <div class="py-10 bg-[#0b1329] min-h-screen text-slate-100 font-sans selection:bg-emerald-500 selection:text-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header Section -->
            <div class="relative overflow-hidden bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-2xl transition duration-300 hover:border-slate-700/80">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Delivery History & Daily Log</h1>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1">Review your completed dropoffs and daily earnings record.</p>
                        </div>
                    </div>

                    <!-- Back to Dashboard Link -->
                    <div>
                        <a href="{{ route('delivery.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-2xl border border-slate-700/80 transition shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Active Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Completed Count Card -->
                <div class="relative overflow-hidden bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 shadow-xl transition-all duration-300 hover:border-emerald-500/40">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Completed Logged</span>
                            <span class="text-3xl font-black text-emerald-400 mt-1 block">{{ count($deliveries) }}</span>
                        </div>
                        <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Cash Collected Today Card -->
                <div class="relative overflow-hidden bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 shadow-xl transition-all duration-300 hover:border-amber-500/40">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Cash Collected Today</span>
                            <span class="text-3xl font-black text-amber-400 mt-1 block">LKR {{ number_format($totalCollectedToday ?? 0, 2) }}</span>
                        </div>
                        <div class="p-3 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Deliveries List -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
                        <span>Completed Records</span>
                        <span class="px-3 py-0.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full">
                            {{ count($deliveries) }} Delivered
                        </span>
                    </h2>
                </div>

                @forelse ($deliveries as $delivery)
                    @php
                        $order = $delivery->customerOrder ?? $delivery->order;
                        $customer = $order->user ?? null;
                        $amount = $order->total_amount ?? $order->total_price ?? 0;
                        $address = $delivery->dropoff_address ?? $order->dropoff_address ?? $order->address ?? null;
                    @endphp
                    <div class="group relative bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 hover:border-emerald-500/50 rounded-3xl p-6 transition-all duration-300 shadow-xl">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            
                            <!-- Left Section: Details -->
                            <div class="space-y-4 flex-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="px-3 py-1 bg-slate-950 text-emerald-400 border border-emerald-500/30 text-xs font-black rounded-xl">
                                        Order #{{ $order->id ?? $delivery->order_id }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        Zone: <span class="text-slate-200 font-bold">{{ $order->deliveryZone->name ?? 'Assigned Zone' }}</span>
                                    </span>
                                    <span class="text-xs text-slate-500">|</span>
                                    <span class="text-xs text-slate-400">
                                        Delivered: <span class="text-emerald-400 font-semibold">{{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->diffForHumans() : 'Completed' }}</span>
                                    </span>

                                    <!-- Status Badge -->
                                    <span class="px-3 py-1 text-[11px] font-black uppercase tracking-wider rounded-lg border bg-emerald-500/10 text-emerald-400 border-emerald-500/30">
                                        Delivered
                                    </span>
                                </div>

                                <!-- Customer Details -->
                                <div>
                                    <h3 class="text-lg font-bold text-white">
                                        {{ $customer->name ?? 'Customer Name' }}
                                    </h3>
                                    
                                    @if($address)
                                        <p class="text-xs sm:text-sm text-slate-300 mt-1 flex items-start gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            <span>{{ $address }}</span>
                                        </p>
                                    @endif
                                </div>

                                <!-- Ordered Items Summary -->
                                @if(isset($order->items) && $order->items->count())
                                    <div class="p-3 rounded-2xl bg-slate-950/60 border border-slate-800/80">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Delivered Items</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($order->items as $item)
                                                <span class="text-xs px-2.5 py-1 rounded-xl bg-slate-800 text-slate-200 font-medium border border-slate-700/60">
                                                    {{ $item->quantity }}x {{ $item->name ?? $item->product_name ?? 'Item' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Right Section: Price -->
                            <div class="flex flex-col items-start lg:items-end justify-center pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-800">
                                <span class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Amount Collected</span>
                                <span class="text-2xl font-black text-emerald-400 block mt-1">
                                    LKR {{ number_format($amount, 2) }}
                                </span>
                            </div>

                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-16 text-center shadow-2xl backdrop-blur-xl">
                        <div class="w-20 h-20 bg-slate-800/60 border border-slate-700/60 text-emerald-400 rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2">No Delivery Logs Found</h3>
                        <p class="text-slate-400 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                            There are no completed delivery logs recorded yet. Once you mark active orders as delivered, they will be listed here.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>