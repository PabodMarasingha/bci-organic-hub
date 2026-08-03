<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Order #{{ $order->id }} Details
            </h2>
            
            <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-green-600 hover:text-green-700">
                &larr; Back to My Orders
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('message'))
                <div class="p-4 bg-green-100 border border-green-200 text-green-800 rounded-2xl shadow-sm text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Items List (2 Columns) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-bold text-slate-800 text-lg">Ordered Items</h3>
                            <span class="text-xs text-slate-400 font-medium">{{ $order->created_at ? $order->created_at->format('M d, Y - h:i A') : '' }}</span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach($order->items as $item)
                                <div class="p-6 flex justify-between items-center">
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-base">{{ $item->item_name }}</h4>
                                        <p class="text-xs text-slate-500">LKR {{ number_format($item->unit_price, 2) }} x {{ $item->quantity }}</p>
                                    </div>
                                    <span class="font-bold text-slate-800 text-base">
                                        LKR {{ number_format(($item->unit_price ?? 0) * ($item->quantity ?? 1), 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Special Instructions if any -->
                    @if($order->special_instructions)
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                            <h4 class="font-bold text-slate-800 text-sm">Special Instructions:</h4>
                            <p class="text-xs text-slate-600 italic bg-slate-50 p-3 rounded-xl border border-slate-100">
                                "{{ $order->special_instructions }}"
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Order Status & Summary Sidebar (1 Column) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Status Card -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Order Status</h3>
                        
                        <div class="text-center p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Current Status</span>
                            
                            @php
                                $statusColors = [
                                    'pending'   => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'cooking'   => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'ready'     => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                                $badgeStyle = $statusColors[strtolower($order->status)] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                            @endphp

                            <span class="inline-block px-4 py-1.5 rounded-full text-sm font-extrabold uppercase border {{ $badgeStyle }}">
                                {{ $order->status }}
                            </span>
                        </div>

                        <!-- Cancel Order Button (Only if Pending) -->
                        @if(strtolower($order->status) === 'pending')
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full py-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-xl border border-red-200 transition cursor-pointer">
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Delivery & Payment Info -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4 text-xs text-slate-600">
                        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Delivery & Payment</h3>
                        
                        <div>
                            <span class="font-bold text-slate-700 block mb-1">Dropoff Address:</span>
                            <p class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">{{ $order->delivery->dropoff_location ?? 'N/A' }}</p>
                        </div>

                        <div class="flex justify-between border-t border-slate-100 pt-3">
                            <span class="font-bold text-slate-700">Payment Method:</span>
                            <span class="uppercase font-semibold text-slate-800">{{ $order->payment->payment_method ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between font-bold text-sm text-slate-800 border-t border-slate-100 pt-3">
                            <span>Total Amount:</span>
                            <span class="text-green-600">LKR {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>