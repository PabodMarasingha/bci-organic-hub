<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('My Orders') }}
            </h2>
            <a href="{{ route('menu') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                + Order More Food
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message -->
            @if(session('message'))
                <div class="p-4 bg-green-100 border border-green-200 text-green-800 rounded-2xl shadow-sm text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-md transition">
                            
                            <!-- Order Basic Info -->
                            <div class="space-y-2">
                                <div class="flex items-center space-x-3">
                                    <span class="font-extrabold text-slate-800 text-lg">Order #{{ $order->id }}</span>
                                    
                                    <!-- Status Badges -->
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'cooking' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'ready' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'completed' => 'bg-green-100 text-green-800 border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                        $badgeStyle = $statusColors[$order->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                                    @endphp

                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $badgeStyle }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                <p class="text-xs text-slate-500">
                                    Placed on {{ $order->created_at->format('M d, Y - h:i A') }} &bull; {{ $order->items->count() }} Item(s)
                                </p>
                            </div>

                            <!-- Price and Action -->
                            <div class="flex items-center justify-between w-full md:w-auto gap-6 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100">
                                <div>
                                    <span class="text-xs text-slate-400 block uppercase font-semibold">Total Amount</span>
                                    <span class="font-bold text-slate-800 text-base">LKR {{ number_format($order->total_amount, 2) }}</span>
                                </div>

                                <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                                    View Details &rarr;
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <!-- No Orders State -->
                <div class="bg-white p-12 rounded-2xl border border-slate-100 shadow-sm text-center space-y-4 max-w-lg mx-auto">
                    <svg class="w-16 h-16 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-slate-800">No Orders Placed Yet</h3>
                    <p class="text-sm text-slate-500">You haven't placed any food orders yet. Check out our menu!</p>
                    <a href="{{ route('menu') }}" class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl shadow transition">
                        Order Food Now
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>