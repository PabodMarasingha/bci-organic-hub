<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen text-slate-100 font-sans" x-data="{ showCancelModal: false, selectedReason: '', customReason: '' }">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-slate-900/80 backdrop-blur-md p-6 sm:p-10 rounded-3xl border border-slate-800 shadow-2xl relative overflow-hidden">
                
                <!-- Session Alert -->
                @if (session('message'))
                    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-2xl mb-6 backdrop-blur-md text-sm font-bold flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('message') }}</span>
                    </div>
                @endif

                @if ($errors->has('cancellation'))
                    <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-2xl mb-6 backdrop-blur-md text-sm font-bold flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $errors->first('cancellation') }}</span>
                    </div>
                @endif

                <!-- Header Info -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-6 mb-8">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-wider mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Order Status Tracking
                        </span>
                        <h1 class="text-3xl font-black text-white tracking-tight">Order #{{ $order->id }}</h1>
                        <p class="text-xs text-slate-400 mt-1">Placed on {{ $order->created_at ? $order->created_at->format('M d, Y – h:i A') : 'N/A' }}</p>
                    </div>

                    <div class="text-left sm:text-right flex flex-col items-start sm:items-end gap-3">
                        <div>
                            <span class="text-xs text-slate-400 uppercase tracking-widest block">Total Amount</span>
                            <div class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">
                                Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                            </div>
                        </div>

                        <!-- Cancel Button (Only if Order is Pending) -->
                        @if(strtolower($order->status ?? '') === 'pending')
                            <button @click="showCancelModal = true" 
                                    type="button" 
                                    class="px-4 py-2 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500 hover:text-white font-bold text-xs uppercase tracking-wider transition duration-300 shadow-lg flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel Order
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Cancelled Order Banner -->
                @if(strtolower($order->status ?? '') === 'cancelled')
                    <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-start gap-3 text-rose-400 text-xs font-semibold">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span class="font-bold text-sm">This order has been cancelled.</span>
                            @if(!empty($order->cancellation_reason))
                                <span class="block text-slate-400 font-normal mt-1">Reason: <strong class="text-slate-300">{{ $order->cancellation_reason }}</strong></span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Order Delivery Process Tracker -->
                @php
                    $status = strtolower($order->status ?? 'pending');
                    
                    $steps = [
                        'pending' => ['label' => 'Order Placed', 'desc' => 'We have received your order'],
                        'preparing' => ['label' => 'Preparing', 'desc' => 'Kitchen is preparing your meal'],
                        'out_for_delivery' => ['label' => 'Out for Delivery', 'desc' => 'Driver is on the way'],
                        'delivered' => ['label' => 'Delivered', 'desc' => 'Enjoy your fresh meal']
                    ];

                    $currentIndex = 0;
                    if ($status === 'preparing') {
                        $currentIndex = 1;
                    } elseif (in_array($status, ['out_for_delivery', 'on_delivery', 'ready'])) {
                        $currentIndex = 2;
                    } elseif ($status === 'delivered') {
                        $currentIndex = 3;
                    }
                @endphp

                <div class="mb-10">
                    <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-6">Delivery Process Flow</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        @php $i = 0; @endphp
                        @foreach($steps as $key => $data)
                            @php
                                $isCompleted = $i <= $currentIndex && $status !== 'cancelled';
                                $isCurrent = $i === $currentIndex && $status !== 'cancelled';
                            @endphp
                            <div class="p-4 rounded-2xl border transition-all duration-300 {{ $isCurrent ? 'bg-emerald-500/10 border-emerald-500/50 shadow-lg shadow-emerald-500/10' : ($isCompleted ? 'bg-slate-800/40 border-slate-700' : 'bg-slate-950/40 border-slate-800/60 opacity-40') }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black {{ $isCompleted ? 'bg-emerald-500 text-slate-950' : 'bg-slate-800 text-slate-400' }}">
                                        {{ $i + 1 }}
                                    </span>
                                    @if($isCurrent)
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-sm text-slate-200">{{ $data['label'] }}</h4>
                                <p class="text-[11px] text-slate-400 mt-1">{{ $data['desc'] }}</p>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                </div>

                <!-- Ordered Items List -->
                @if(isset($order->items) && count($order->items) > 0)
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">Ordered Items</h3>
                        <div class="bg-slate-950/60 rounded-2xl border border-slate-800/80 p-4 divide-y divide-slate-800/60">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-center py-3 first:pt-0 last:pb-0">
                                    <div>
                                        <div class="font-bold text-slate-200 text-sm">{{ $item->item_name ?? $item->menuItem->name ?? 'Item' }}</div>
                                        <div class="text-xs text-slate-400">Qty: {{ $item->quantity }} &times; Rs. {{ number_format($item->unit_price ?? $item->price ?? 0, 2) }}</div>
                                    </div>
                                    <div class="font-semibold text-slate-300 text-sm">
                                        Rs. {{ number_format($item->quantity * ($item->unit_price ?? $item->price ?? 0), 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Order Additional Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 text-sm">
                    <div class="bg-slate-950/60 p-4 rounded-2xl border border-slate-800/80 space-y-2">
                        <span class="text-xs text-slate-400 uppercase font-semibold">Delivery Zone</span>
                        <div class="font-bold text-slate-200">{{ $order->deliveryZone->name ?? 'Standard Zone' }}</div>
                    </div>
                    <div class="bg-slate-950/60 p-4 rounded-2xl border border-slate-800/80 space-y-2">
                        <span class="text-xs text-slate-400 uppercase font-semibold">Dropoff Location</span>
                        <div class="font-bold text-slate-200">{{ $order->delivery->dropoff_location ?? $order->dropoff_location ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-800">
                    <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-slate-400 hover:text-slate-200 transition">
                        &larr; Back to My Orders
                    </a>

                    <a href="{{ route('menu') }}" 
                       class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-extrabold rounded-xl shadow-lg shadow-emerald-500/20 text-sm transition">
                        Order More
                    </a>
                </div>

            </div>

        </div>

        <!-- Professional Cancellation Reason Modal -->
        <div x-show="showCancelModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" 
             style="display: none;">

            <!-- Modal Content Box -->
            <div @click.away="showCancelModal = false" 
                 class="w-full max-w-lg bg-[#0b0f19] border border-slate-800 rounded-3xl p-6 shadow-2xl relative text-left">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-rose-500/10 text-rose-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Cancel Order #{{ $order->id }}</h3>
                            <p class="text-xs text-slate-400">Please select a reason for cancelling this order.</p>
                        </div>
                    </div>
                    <button @click="showCancelModal = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- Reason Options -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Select Reason</label>
                        
                        <template x-for="reason in [
                            'Changed my mind',
                            'Ordered by mistake',
                            'Delivery time takes too long',
                            'Want to change order items',
                            'Other'
                        ]">
                            <label class="flex items-center p-3 rounded-xl border border-slate-800 bg-[#141a26]/50 hover:border-emerald-500/40 cursor-pointer transition">
                                <input type="radio" 
                                       name="cancellation_reason" 
                                       :value="reason" 
                                       x-model="selectedReason" 
                                       required
                                       class="text-emerald-500 focus:ring-emerald-500/20 bg-slate-900 border-slate-700">
                                <span class="ms-3 text-xs font-semibold text-slate-200" x-text="reason"></span>
                            </label>
                        </template>
                    </div>

                    <!-- Custom Text Input if 'Other' is chosen -->
                    <div x-show="selectedReason === 'Other'" x-transition class="pt-2">
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Please specify your reason</label>
                        <textarea name="custom_reason" 
                                  x-model="customReason" 
                                  rows="2" 
                                  placeholder="Provide details..."
                                  class="w-full px-3 py-2 text-xs rounded-xl bg-[#141a26] border border-slate-800 text-slate-100 focus:border-emerald-500 focus:ring-0"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                        <button type="button" 
                                @click="showCancelModal = false" 
                                class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs uppercase tracking-wider transition">
                            Keep Order
                        </button>
                        
                        <button type="submit" 
                                :disabled="!selectedReason"
                                :class="{'opacity-50 cursor-not-allowed': !selectedReason}"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-rose-950/50 transition">
                            Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>