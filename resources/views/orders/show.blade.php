<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-800 tracking-tight">
                    Order #{{ $order->id }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Placed on {{ $order->created_at ? $order->created_at->format('M d, Y \a\t h:i A') : 'N/A' }}
                </p>
            </div>
            
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-green-600 bg-white hover:bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to My Orders
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Flash Messages -->
            @if(session('message'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm font-semibold flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('message') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            <!-- VISUAL ORDER PROGRESS TIMELINE TRACKER -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm transition-all duration-500 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6">Order Status Live Tracker</h3>

                @php
                    $status = strtolower($order->status);
                    $steps = [
                        'pending'   => ['title' => 'Order Placed', 'desc' => 'Waiting for kitchen'],
                        'cooking'   => ['title' => 'Preparing', 'desc' => 'Food in kitchen'],
                        'ready'     => ['title' => 'Ready', 'desc' => 'Packed & ready'],
                        'completed' => ['title' => 'Completed', 'desc' => 'Enjoy your meal!'],
                    ];
                    
                    $statusOrder = ['pending' => 1, 'cooking' => 2, 'ready' => 3, 'completed' => 4];
                    $currentStep = $statusOrder[$status] ?? 0;
                    $isCancelled = $status === 'cancelled';
                    $progressWidth = (($currentStep - 1) / 3) * 100;
                @endphp

                @if($isCancelled)
                    <!-- Cancelled Banner -->
                    <div class="p-6 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-4">
                        <div class="w-12 h-12 bg-rose-500 text-white rounded-full flex items-center justify-center font-bold text-xl shadow-md">
                            ✕
                        </div>
                        <div>
                            <h4 class="font-extrabold text-rose-900 text-base">This order was cancelled</h4>
                            <p class="text-xs text-rose-600 mt-0.5">If you have any issues or questions, please contact support.</p>
                        </div>
                    </div>
                @else
                    <!-- Progress Bar Component -->
                    <div class="relative" x-data="{ width: '{{ $progressWidth }}%' }">
                        <!-- Progress Line Base -->
                        <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 rounded-full z-0"></div>
                        
                        <!-- Progress Active Bar (Alpine :style binding used to prevent VS Code CSS errors) -->
                        <div class="hidden md:block absolute top-1/2 left-0 h-1 bg-emerald-500 -translate-y-1/2 rounded-full transition-all duration-700 z-0"
                             :style="{ width: width }"></div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
                            @foreach($steps as $key => $step)
                                @php
                                    $stepNum = $statusOrder[$key];
                                    $isPassed = $currentStep >= $stepNum;
                                    $isCurrent = $currentStep === $stepNum;
                                @endphp
                                <div class="flex md:flex-col items-center gap-4 md:gap-3 text-left md:text-center">
                                    <!-- Step Circle Badge -->
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-sm transition-all duration-300 shadow-sm shrink-0
                                        {{ $isCurrent ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 scale-110' : ($isPassed ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400') }}">
                                        @if($isPassed && !$isCurrent)
                                            ✓
                                        @else
                                            {{ $stepNum }}
                                        @endif
                                    </div>

                                    <!-- Step Details -->
                                    <div>
                                        <h4 class="font-bold text-sm {{ $isPassed ? 'text-slate-800' : 'text-slate-400' }}">
                                            {{ $step['title'] }}
                                        </h4>
                                        <p class="text-xs {{ $isCurrent ? 'text-emerald-600 font-semibold' : 'text-slate-400' }}">
                                            {{ $step['desc'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Details Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Items List (2 Columns) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-500 transform delay-100" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-bold text-slate-800 text-base">Items Summary</h3>
                            <span class="text-xs font-semibold px-3 py-1 bg-slate-100 text-slate-600 rounded-full">
                                {{ $order->items->count() }} {{ Str::plural('Item', $order->items->count()) }}
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach($order->items as $item)
                                <div class="p-6 flex justify-between items-center hover:bg-slate-50/40 transition">
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-slate-800 text-base">{{ $item->item_name }}</h4>
                                        
                                        <!-- Customization display if available -->
                                        @if(!empty($item->customizations) && is_array($item->customizations))
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach($item->customizations as $custom)
                                                    <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-medium">
                                                        + {{ $custom }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <p class="text-xs text-slate-400 font-medium pt-1">
                                            LKR {{ number_format($item->unit_price, 2) }} &times; {{ $item->quantity }}
                                        </p>
                                    </div>
                                    <span class="font-extrabold text-slate-800 text-base">
                                        LKR {{ number_format(($item->unit_price ?? 0) * ($item->quantity ?? 1), 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    @if($order->special_instructions)
                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-2">
                            <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Special Note / Instructions</h4>
                            <p class="text-xs text-slate-600 italic bg-amber-50/50 p-4 rounded-2xl border border-amber-100/60 leading-relaxed">
                                "{{ $order->special_instructions }}"
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Order Sidebar (1 Column) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Cancel Action Box -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Actions</h3>
                        
                        @if(strtolower($order->status) === 'pending')
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full py-3 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs rounded-2xl border border-rose-200 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Cancel Order
                                </button>
                            </form>
                        @else
                            <p class="text-xs text-slate-400 text-center italic py-2">
                                Order cancellation is no longer available as the order is being processed.
                            </p>
                        @endif
                    </div>

                    <!-- Delivery & Payment Info -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4 text-xs text-slate-600">
                        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Delivery & Payment</h3>
                        
                        <div class="space-y-1">
                            <span class="font-bold text-slate-700 block">Dropoff Location:</span>
                            <p class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-slate-800 font-medium leading-relaxed">
                                {{ $order->delivery->dropoff_location ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="flex justify-between items-center border-t border-slate-100 pt-3">
                            <span class="font-bold text-slate-700">Payment Method:</span>
                            <span class="uppercase font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg">
                                {{ $order->payment->payment_method ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center font-black text-base text-slate-800 border-t border-slate-100 pt-3">
                            <span>Total Amount:</span>
                            <span class="text-emerald-600">LKR {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>