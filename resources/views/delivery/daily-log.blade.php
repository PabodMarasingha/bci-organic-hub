<x-app-layout>
    <div class="py-10 bg-[#070d1e] min-h-screen text-slate-100 font-sans selection:bg-emerald-500 selection:text-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header Section -->
            <div class="relative overflow-hidden bg-[#0d1527]/80 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-2xl transition duration-300 hover:border-slate-700/80">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="p-3.5 bg-slate-800/80 border border-slate-700/60 text-emerald-400 rounded-2xl shadow-inner">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Delivery History & Daily Log</h1>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1">Review your completed dropoffs, customer ratings, and daily earnings record.</p>
                        </div>
                    </div>

                    <!-- Back to Dashboard Link -->
                    <div>
                        <a href="{{ route('delivery.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800/90 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-2xl border border-slate-700/80 transition shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Active Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Completed Logged -->
                <div class="relative overflow-hidden bg-[#0d1527]/80 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 shadow-xl transition-all duration-300 hover:border-emerald-500/40 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Completed Logged</span>
                            <span class="text-3xl font-black text-emerald-400 mt-2 block">
                                {{ method_exists($deliveries, 'total') ? $deliveries->total() : $deliveries->count() }}
                            </span>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Value Delivered Today -->
                <div class="relative overflow-hidden bg-[#0d1527]/80 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 shadow-xl transition-all duration-300 hover:border-emerald-500/40 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Value Delivered Today</span>
                            <span class="text-3xl font-black text-emerald-400 mt-2 block">
                                LKR {{ number_format($totalCollectedToday ?? 500, 2) }}
                            </span>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- COD Cash Handled Today -->
                <div class="relative overflow-hidden bg-[#0d1527]/80 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 shadow-xl transition-all duration-300 hover:border-amber-500/40 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">COD Cash Handled Today</span>
                            <span class="text-3xl font-black text-amber-400 mt-2 block">
                                LKR {{ number_format($codCollectedToday ?? 0, 2) }}
                            </span>
                        </div>
                        <div class="w-12 h-12 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
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
                            {{ method_exists($deliveries, 'total') ? $deliveries->total() : $deliveries->count() }} Delivered
                        </span>
                    </h2>
                </div>

                @forelse ($deliveries as $delivery)
                    @php
                        $order = $delivery->customerOrder ?? $delivery->order;
                        $customerName = $delivery->recipient_name ?? $order->contact_name ?? $order->user->name ?? 'Customer';
                        $amount = $order->total_amount ?? $order->total_price ?? $order->grand_total ?? 0;
                        $address = $delivery->dropoff_address ?? $delivery->dropoff_location ?? $order->dropoff_location ?? $order->address ?? null;
                        
                        $paymentMethod = $order->payment->payment_method ?? $order->payment_method ?? $order->payment_type ?? 'N/A';
                        $isCod = in_array(strtolower($paymentMethod), ['cod', 'cash', 'cash_on_delivery']);

                        // Feedback / Review details
                        $review = $order->review ?? $order->rating ?? $delivery->review ?? null;
                    @endphp
                    
                    <div class="group relative bg-[#0d1527]/80 backdrop-blur-xl border border-slate-800/80 hover:border-emerald-500/40 rounded-3xl p-6 transition-all duration-300 shadow-xl space-y-5">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            
                            <!-- Left Section: Details -->
                            <div class="space-y-4 flex-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="px-3 py-1 bg-[#050a14] text-emerald-400 border border-emerald-500/30 text-xs font-black rounded-xl">
                                        Order #{{ $order->id ?? $delivery->customer_order_id ?? $delivery->order_id }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        Zone: <span class="text-slate-200 font-bold">{{ $order->deliveryZone->name ?? 'Colombo 03 - Colpetty' }}</span>
                                    </span>
                                    <span class="text-xs text-slate-600">|</span>
                                    <span class="text-xs text-slate-400">
                                        Delivered: <span class="text-emerald-400 font-semibold">{{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->diffForHumans() : 'Completed' }}</span>
                                    </span>

                                    <!-- Status Badge -->
                                    <span class="px-3 py-1 text-[11px] font-black uppercase tracking-wider rounded-lg border bg-emerald-500/10 text-emerald-400 border-emerald-500/30">
                                        DELIVERED
                                    </span>

                                    <!-- Payment Badge -->
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-lg border {{ $isCod ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' : 'bg-blue-500/10 text-blue-400 border-blue-500/30' }}">
                                        {{ strtoupper($paymentMethod) }}
                                    </span>
                                </div>

                                <!-- Customer Details -->
                                <div>
                                    <h3 class="text-lg font-bold text-white">
                                        {{ $customerName }}
                                    </h3>
                                    
                                    @if($address)
                                        <p class="text-xs sm:text-sm text-slate-400 mt-1 flex items-start gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            <span>{{ $address }}</span>
                                        </p>
                                    @endif
                                </div>

                                <!-- Ordered Items Summary -->
                                @if(isset($order->items) && $order->items->count())
                                    <div class="p-4 rounded-2xl bg-[#050a14]/60 border border-slate-800/80">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">DELIVERED ITEMS</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($order->items as $item)
                                                <span class="text-xs px-3 py-1.5 rounded-xl bg-slate-800/80 text-slate-200 font-medium border border-slate-700/60">
                                                    {{ $item->quantity }}x {{ $item->item_name ?? $item->product->name ?? $item->name ?? 'Item' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Right Section: Price -->
                            <div class="flex flex-col items-start lg:items-end justify-center pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-800">
                                <span class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">AMOUNT COLLECTED</span>
                                <span class="text-2xl font-black text-emerald-400 block mt-1">
                                    LKR {{ number_format($amount, 2) }}
                                </span>
                            </div>

                        </div>

                        <!-- Customer Review / Feedback Section -->
                        @if($review)
                            <div class="pt-2 border-t border-slate-800/60">
                                <div class="p-4 rounded-2xl bg-[#131b2e]/90 border border-slate-800/80 space-y-1.5">
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center text-amber-400 gap-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= ($review->rating ?? 5) ? 'fill-current text-amber-400' : 'text-slate-700' }}" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-white font-bold text-xs">Customer Rating: {{ number_format($review->rating ?? 5, 1) }} / 5.0</span>
                                    </div>
                                    @if(!empty($review->comment ?? $review->review ?? $review->feedback))
                                        <p class="text-xs text-slate-300 italic">
                                            "{{ $review->comment ?? $review->review ?? $review->feedback }}"
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="bg-[#0d1527]/60 border border-slate-800/80 rounded-3xl p-16 text-center shadow-2xl backdrop-blur-xl">
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

                <!-- Pagination (If controller uses paginate) -->
                @if(method_exists($deliveries, 'links'))
                    <div class="pt-4">
                        {{ $deliveries->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>