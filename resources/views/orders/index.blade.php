<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen text-slate-100 font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        <span class="p-2.5 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </span>
                        My Orders
                    </h1>
                    <p class="text-slate-400 text-sm mt-1">Track and review your past food orders.</p>
                </div>

                <a href="{{ route('menu') }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 border border-slate-800 rounded-xl text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Order
                </a>
            </div>

            <!-- Orders List -->
            <div class="space-y-4">
                @forelse ($orders as $order)
                    <a href="{{ route('orders.show', $order->id) }}" 
                       class="block bg-slate-900/80 backdrop-blur-md border border-slate-800 hover:border-emerald-500/40 p-5 rounded-2xl transition-all duration-300 hover:scale-[1.01] hover:shadow-xl group">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            
                            <!-- Left: Order Info -->
                            <div class="space-y-1">
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-slate-100 text-base group-hover:text-emerald-400 transition-colors">
                                        Order #{{ $order->id }}
                                    </span>
                                </div>
                                
                                <p class="text-xs text-slate-400 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $order->created_at->format('M d, Y – h:i A') }}
                                </p>

                                @if ($order->deliveryZone)
                                    <p class="text-xs text-slate-500 flex items-center gap-1.5 pt-1">
                                        <svg class="w-3.5 h-3.5 text-emerald-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        Zone: <span class="text-slate-300 font-medium">{{ $order->deliveryZone->name }}</span>
                                    </p>
                                @endif
                            </div>

                            <!-- Right: Amount & Dynamic Status Badge -->
                            <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-800/60">
                                <div class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">
                                    Rs. {{ number_format($order->total_amount, 2) }}
                                </div>

                                <div class="mt-1">
                                    @if ($order->status === 'delivered')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            Delivered
                                        </span>
                                    @elseif ($order->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-500/10 border border-sky-500/30 text-sky-400 text-xs font-bold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </a>
                @empty
                    <div class="bg-slate-900/40 border border-slate-800 rounded-3xl p-12 text-center">
                        <svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"></path>
                        </svg>
                        <p class="text-slate-400 font-medium">No orders found yet.</p>
                        <a href="{{ route('menu') }}" class="inline-block mt-4 text-emerald-400 hover:text-emerald-300 text-sm font-semibold">
                            Browse Menu & Order Now &rarr;
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>