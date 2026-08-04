<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="animate-fade-in-up" style="animation-delay: 0ms;">
                <h2 class="font-black text-3xl text-transparent bg-clip-text bg-gradient-to-r from-slate-800 to-slate-500 tracking-tight">
                    My Orders
                </h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">
                    View and track all your order history in real-time
                </p>
            </div>
            
            <a href="{{ route('menu') }}" class="group animate-fade-in-up inline-flex items-center gap-2 text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 px-6 py-3 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50" style="animation-delay: 100ms;">
                <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Order Food Now
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/80 min-h-screen relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-white to-transparent opacity-60 pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] border border-white/50 shadow-xl shadow-slate-200/50 overflow-hidden animate-fade-in-up" style="animation-delay: 200ms;">
                
                <div class="p-6 sm:px-8 sm:py-6 border-b border-slate-100 bg-white/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        All Order History
                    </h3>
                    <span class="text-xs font-bold px-4 py-1.5 bg-slate-100 text-slate-600 rounded-full shadow-inner border border-slate-200/60">
                        {{ $orders->count() }} Total {{ Str::plural('Order', $orders->count()) }}
                    </span>
                </div>

                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 text-[11px] uppercase tracking-widest font-extrabold text-slate-400 border-b border-slate-100">
                                    <th class="py-5 px-8">Order ID</th>
                                    <th class="py-5 px-8">Date</th>
                                    <th class="py-5 px-8">Items</th>
                                    <th class="py-5 px-8">Total Amount</th>
                                    <th class="py-5 px-8">Status</th>
                                    <th class="py-5 px-8 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                @foreach($orders as $order)
                                    @php
                                        $statusColors = [
                                            'pending'   => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500'],
                                            'cooking'   => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500 animate-pulse'],
                                            'ready'     => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'dot' => 'bg-purple-500'],
                                            'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                            'cancelled' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'dot' => 'bg-rose-500'],
                                        ];
                                        $style = $statusColors[strtolower($order->status)] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400'];
                                    @endphp
                                    <tr class="group hover:bg-white transition-all duration-300 hover:shadow-[0_0_20px_-3px_rgba(0,0,0,0.05)] hover:-translate-y-0.5 animate-fade-in-up relative z-0 hover:z-10 bg-transparent" @style(['animation-delay: ' . (300 + ($loop->index * 100)) . 'ms'])>
                                        <td class="py-5 px-8 font-black text-slate-800">
                                            <span class="text-slate-400 font-medium">#</span>{{ $order->id }}
                                        </td>
                                        <td class="py-5 px-8 text-slate-500 font-medium">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="py-5 px-8 text-slate-600 font-medium">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                                    {{ $order->items->count() }}
                                                </div>
                                                {{ Str::plural('Item', $order->items->count()) }}
                                            </div>
                                        </td>
                                        <td class="py-5 px-8">
                                            <span class="font-black text-slate-800 text-base">LKR {{ number_format($order->total_amount, 2) }}</span>
                                        </td>
                                        <td class="py-5 px-8">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-extrabold uppercase tracking-wide border {{ $style['bg'] }} {{ $style['text'] }} {{ $style['border'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }}"></span>
                                                {{ $order->status }}
                                            </div>
                                        </td>
                                        <td class="py-5 px-8 text-right">
                                            <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center justify-center gap-2 text-xs font-bold text-emerald-600 hover:text-white bg-emerald-50 hover:bg-emerald-500 border border-emerald-100 hover:border-emerald-500 px-4 py-2 rounded-xl transition-all duration-300">
                                                Track
                                                <svg class="w-3.5 h-3.5 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-24 px-6 text-center flex flex-col items-center justify-center animate-fade-in-up" style="animation-delay: 300ms;">
                        <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mb-6 shadow-inner animate-float">
                            <span class="text-4xl filter drop-shadow-sm">🛍️</span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2">No orders yet</h4>
                        <p class="text-sm text-slate-500 max-w-sm mb-8">Looks like you haven't made your first order. Delicious food is just a few clicks away!</p>
                        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-2xl transition-all shadow-lg shadow-slate-900/20 hover:shadow-slate-900/40 hover:-translate-y-0.5">
                            Browse Menu & Order
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>