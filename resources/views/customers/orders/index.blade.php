<x-app-layout>
    <div class="py-12 bg-[#0b1329] min-h-screen text-slate-100 font-sans selection:bg-emerald-500 selection:text-slate-950">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-800/80 gap-4">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> History & Tracking
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">My Orders</h1>
                </div>

                <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-emerald-400 hover:text-emerald-300 text-xs font-bold rounded-xl transition duration-300 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Order
                </a>
            </div>

            <!-- Flash Message Alert -->
            @if (session()->has('message'))
                <div class="flex items-center gap-3 p-4 text-xs sm:text-sm text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl shadow-lg backdrop-blur-md">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold">{{ session('message') }}</span>
                </div>
            @endif

            <!-- Orders List -->
            <div class="space-y-4">
                @forelse ($orders as $order)
                    <a href="{{ route('orders.show', $order->id) }}" 
                       class="block bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 hover:border-emerald-500/40 p-6 rounded-3xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-2xl shadow-lg group">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                            
                            <!-- Left: Order Info & Details -->
                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <span class="font-black text-white text-lg group-hover:text-emerald-300 transition-colors">
                                        Order #{{ $order->id }}
                                    </span>
                                    <span class="text-[11px] font-bold text-slate-400 bg-slate-800/80 px-2.5 py-0.5 rounded-lg border border-slate-700/60">
                                        {{ $order->items ? $order->items->count() : 0 }} {{ Str::plural('Item', $order->items ? $order->items->count() : 0) }}
                                    </span>
                                </div>
                                
                                <p class="text-xs text-slate-400 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $order->created_at ? $order->created_at->format('M d, Y – h:i A') : 'N/A' }}
                                </p>

                                @if ($order->deliveryZone)
                                    <p class="text-xs text-slate-500 flex items-center gap-1.5 pt-0.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        Zone: <span class="text-slate-300 font-medium">{{ $order->deliveryZone->name ?? $order->deliveryZone->zone_name ?? 'N/A' }}</span>
                                    </p>
                                @endif
                            </div>

                            <!-- Right: Amount & Status Badge -->
                            <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 pt-4 sm:pt-0 border-slate-800/80">
                                <div class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-200">
                                    LKR {{ number_format($order->total_amount ?? 0, 2) }}
                                </div>

                                <div class="mt-2">
                                    @php $status = strtolower($order->status ?? 'pending'); @endphp

                                    @if ($status === 'delivered')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-extrabold rounded-full">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                            Delivered
                                        </span>
                                    @elseif ($status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-extrabold rounded-full">
                                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                            Pending
                                        </span>
                                    @elseif ($status === 'cancelled')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-extrabold rounded-full">
                                            <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-500/10 border border-sky-500/30 text-sky-400 text-xs font-extrabold rounded-full">
                                            <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </a>
                @empty
                    <!-- Empty Orders State -->
                    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-12 text-center shadow-2xl backdrop-blur-xl">
                        <div class="w-20 h-20 bg-slate-800/80 border border-slate-700/60 text-slate-500 rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                            <svg class="w-10 h-10 stroke-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2">No Orders Placed Yet</h3>
                        <p class="text-slate-400 text-xs sm:text-sm mb-6 max-w-sm mx-auto leading-relaxed">
                            You haven't placed any orders yet. Browse our healthy meals and make your first customized order today!
                        </p>
                        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-black text-xs rounded-2xl shadow-xl shadow-emerald-500/20 transition duration-300 transform hover:-translate-y-0.5">
                            Browse Menu & Order Now &rarr;
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>