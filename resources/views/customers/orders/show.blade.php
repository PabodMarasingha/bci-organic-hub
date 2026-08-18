<x-app-layout>
    <div class="py-12 bg-[#090d16] min-h-screen text-slate-100 font-sans selection:bg-emerald-500 selection:text-slate-950" 
         x-data="{ showCancelModal: false, selectedReason: '', customReason: '', rating: 0, hoverRating: 0 }">
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-slate-900/90 backdrop-blur-2xl p-6 sm:p-10 rounded-3xl border border-slate-800/80 shadow-2xl shadow-slate-950/50 relative overflow-hidden">
                
                <!-- Ambient Glow Background Effect -->
                <div class="absolute -top-24 -right-24 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Alert Messages -->
                @if (session('message'))
                    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-2xl mb-8 backdrop-blur-md text-sm font-semibold flex items-center gap-3 animate-fade-in">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('message') }}</span>
                    </div>
                @endif

                @if ($errors->has('cancellation'))
                    <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-2xl mb-8 backdrop-blur-md text-sm font-semibold flex items-center gap-3 animate-fade-in">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $errors->first('cancellation') }}</span>
                    </div>
                @endif

                <!-- Header Info -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-slate-800/80 pb-6 mb-8">
                    <div>
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-wider mb-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Order Status Tracking
                        </span>
                        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Order #{{ $order->id }}</h1>
                        <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Placed on {{ $order->created_at ? $order->created_at->format('M d, Y – h:i A') : 'N/A' }}
                        </p>
                    </div>

                    <div class="text-left sm:text-right flex flex-col items-start sm:items-end gap-3">
                        <div>
                            <span class="text-[11px] text-slate-400 uppercase tracking-widest block font-bold">Total Amount</span>
                            <div class="text-2xl sm:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-200">
                                LKR {{ number_format($order->total_amount ?? 0, 2) }}
                            </div>
                        </div>

                        <!-- Cancel Button (Only if Order is Pending) -->
                        @if(strtolower($order->status ?? '') === 'pending')
                            <button @click="showCancelModal = true" 
                                    type="button" 
                                    class="px-4 py-2 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500 hover:text-white font-bold text-xs uppercase tracking-wider transition duration-300 shadow-lg hover:shadow-rose-500/20 flex items-center gap-2 group">
                                <svg class="w-4 h-4 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel Order
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Cancelled Order Banner -->
                @if(strtolower($order->status ?? '') === 'cancelled')
                    <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-start gap-3.5 text-rose-400 text-xs font-semibold">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span class="font-bold text-sm block">This order has been cancelled.</span>
                            @if(!empty($order->cancellation_reason))
                                <span class="block text-slate-400 font-normal mt-1">Reason: <strong class="text-slate-300">{{ $order->cancellation_reason }}</strong></span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Assigned Driver Details Section -->
                @if(isset($order->delivery->driver) && $order->delivery->driver)
                    @php $driver = $order->delivery->driver; @endphp
                    <div class="mb-8 p-5 bg-gradient-to-r from-emerald-950/40 via-slate-900/90 to-slate-900/90 rounded-2xl border border-emerald-500/30 relative overflow-hidden shadow-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-400 font-black text-lg shrink-0">
                                    {{ strtoupper(substr($driver->name ?? 'D', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20">
                                        Assigned Delivery Driver
                                    </span>
                                    <h4 class="text-base font-bold text-white mt-1">{{ $driver->name ?? 'Delivery Partner' }}</h4>
                                    @if(!empty($driver->vehicle_number) || !empty($driver->vehicle_type))
                                        <p class="text-xs text-slate-400 flex items-center gap-2 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            {{ $driver->vehicle_type ?? 'Vehicle' }}: <span class="text-slate-200 font-semibold">{{ $driver->vehicle_number ?? 'N/A' }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($driver->phone))
                                <a href="tel:{{ $driver->phone }}" 
                                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs rounded-xl transition shadow-md shadow-emerald-500/20 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    Call Driver ({{ $driver->phone }})
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Order Delivery Process Tracker -->
                @php
                    $status = strtolower($order->status ?? 'pending');
                    
                    $steps = [
                        'pending'          => ['label' => 'Order Placed', 'desc' => 'We have received your order'],
                        'preparing'        => ['label' => 'Preparing', 'desc' => 'Kitchen is preparing your meal'],
                        'out_for_delivery' => ['label' => 'Out for Delivery', 'desc' => 'Rider is on the way'],
                        'delivered'        => ['label' => 'Delivered', 'desc' => 'Enjoy your fresh meal']
                    ];

                    $currentIndex = 0;
                    if (in_array($status, ['preparing', 'processing'])) {
                        $currentIndex = 1;
                    } elseif (in_array($status, ['out_for_delivery', 'on_delivery', 'ready', 'dispatched'])) {
                        $currentIndex = 2;
                    } elseif (in_array($status, ['delivered', 'completed'])) {
                        $currentIndex = 3;
                    }
                @endphp

                <div class="mb-10">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        Delivery Process Flow
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        @php $i = 0; @endphp
                        @foreach($steps as $key => $data)
                            @php
                                $isCompleted = $i <= $currentIndex && $status !== 'cancelled';
                                $isCurrent = $i === $currentIndex && $status !== 'cancelled';
                            @endphp
                            <div class="p-4 rounded-2xl border transition-all duration-300 relative {{ $isCurrent ? 'bg-emerald-500/10 border-emerald-500/50 shadow-lg shadow-emerald-500/10' : ($isCompleted ? 'bg-slate-800/40 border-slate-700/80' : 'bg-slate-950/40 border-slate-800/60 opacity-45') }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black {{ $isCompleted ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                                        @if($isCompleted && $i < $currentIndex)
                                            ✓
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </span>
                                    @if($isCurrent && !in_array($status, ['completed', 'delivered']))
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                        </span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-sm text-slate-200">{{ $data['label'] }}</h4>
                                <p class="text-[11px] text-slate-400 mt-1 leading-tight">{{ $data['desc'] }}</p>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                </div>

                <!-- Customer Review & Rating Section -->
                @if(in_array($status, ['delivered', 'completed']))
                    <div class="mb-8 p-6 bg-slate-950/70 border border-emerald-500/30 rounded-3xl relative overflow-hidden shadow-xl">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 rounded-2xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Rate & Review Your Experience</h3>
                                <p class="text-xs text-slate-400">Let us know how your order and delivery were!</p>
                            </div>
                        </div>

                        @if($order->review)
                            <!-- Already Reviewed View -->
                            <div class="p-4 bg-slate-900/80 rounded-2xl border border-slate-800">
                                <div class="flex items-center gap-1 mb-2 text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $order->review->rating ? 'fill-current' : 'text-slate-700' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                    <span class="text-xs font-bold text-slate-300 ml-2">{{ $order->review->rating }}.0 / 5.0</span>
                                </div>
                                @if($order->review->comment)
                                    <p class="text-xs text-slate-300 italic">"{{ $order->review->comment }}"</p>
                                @endif
                                <span class="text-[10px] text-emerald-400 font-semibold block mt-2">✓ Thank you for your review!</span>
                            </div>
                        @else
                            <!-- Review Submission Form -->
                            <form action="{{ route('orders.review.store', $order->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Rating</label>
                                    <div class="flex items-center gap-2">
                                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                            <button type="button" 
                                                    @click="rating = star" 
                                                    @mouseenter="hoverRating = star" 
                                                    @mouseleave="hoverRating = 0"
                                                    class="text-2xl transition-transform hover:scale-125 focus:outline-none">
                                                <svg class="w-8 h-8" :class="(hoverRating || rating) >= star ? 'text-amber-400 fill-current' : 'text-slate-700'" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </button>
                                        </template>
                                        <input type="hidden" name="rating" :value="rating" required>
                                    </div>
                                    @error('rating')
                                        <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Comment (Optional)</label>
                                    <textarea name="comment" 
                                              rows="3" 
                                              placeholder="Share details about your meal or delivery experience..." 
                                              class="w-full px-4 py-2.5 text-xs rounded-2xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-500 focus:border-emerald-500 focus:ring-0 transition"></textarea>
                                    @error('comment')
                                        <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" 
                                        :disabled="rating === 0"
                                        :class="{'opacity-50 cursor-not-allowed': rating === 0}"
                                        class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition">
                                    Submit Review
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

                <!-- Ordered Items List -->
                @if(isset($order->items) && count($order->items) > 0)
                    <div class="mb-8">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Ordered Items</h3>
                        <div class="bg-slate-950/60 rounded-2xl border border-slate-800/80 p-5 divide-y divide-slate-800/60">
                            @foreach($order->items as $item)
                                @php
                                    $customizations = is_string($item->customizations) 
                                        ? json_decode($item->customizations, true) 
                                        : $item->customizations;
                                @endphp
                                <div class="py-4 first:pt-0 last:pb-0">
                                    <div class="flex justify-between items-start gap-4">
                                        <div class="space-y-1">
                                            <div class="font-bold text-slate-200 text-sm sm:text-base">{{ $item->item_name ?? ($item->product->name ?? 'Product Item') }}</div>
                                            <div class="text-xs text-slate-400">
                                                Qty: <span class="font-bold text-slate-300">{{ $item->quantity }}</span> &times; LKR {{ number_format($item->unit_price ?? 0, 2) }}
                                            </div>
                                            
                                            <!-- Custom Extra Ingredients -->
                                            @if(!empty($customizations) && is_array($customizations))
                                                <div class="pt-1.5 flex flex-wrap gap-1.5">
                                                    @foreach($customizations as $custom)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-[10px] font-medium text-emerald-400">
                                                            + {{ $custom['name'] ?? 'Extra Ingredient' }} (LKR {{ number_format($custom['price'] ?? 0, 2) }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if(!empty($item->special_instructions))
                                                <p class="text-[11px] text-amber-400/90 italic pt-1 flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                                    Note: {{ $item->special_instructions }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="font-extrabold text-slate-200 text-sm sm:text-base whitespace-nowrap">
                                            LKR {{ number_format($item->quantity * ($item->unit_price ?? 0), 2) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Order Additional Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 text-sm">
                    <div class="bg-slate-950/60 p-4 rounded-2xl border border-slate-800/80 space-y-1">
                        <span class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">Delivery Zone</span>
                        <div class="font-bold text-slate-200">{{ $order->deliveryZone->name ?? ($order->deliveryZone->zone_name ?? 'Standard Delivery Zone') }}</div>
                    </div>
                    <div class="bg-slate-950/60 p-4 rounded-2xl border border-slate-800/80 space-y-1">
                        <span class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">Dropoff Location</span>
                        <div class="font-bold text-slate-200 break-words">{{ $order->delivery->dropoff_address ?? ($order->delivery->dropoff_location ?? ($order->dropoff_location ?? 'N/A')) }}</div>
                    </div>
                </div>

                <!-- Special Order Note -->
                @if(!empty($order->special_instructions))
                    <div class="mb-8 p-4 bg-slate-950/60 rounded-2xl border border-slate-800/80">
                        <span class="text-[11px] text-slate-400 uppercase font-bold tracking-wider block mb-1">Order Notes</span>
                        <p class="text-xs text-slate-300 italic">{{ $order->special_instructions }}</p>
                    </div>
                @endif

                <!-- Footer Navigation -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-800/80">
                    <a href="{{ route('orders.index') }}" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center gap-2 group">
                        <span class="transition-transform group-hover:-translate-x-1 duration-200">&larr;</span> Back to My Orders
                    </a>

                    <a href="{{ route('menu') }}" 
                       class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black rounded-xl shadow-lg shadow-emerald-500/20 text-xs transition transform hover:-translate-y-0.5 active:translate-y-0">
                        Order More
                    </a>
                </div>

            </div>

        </div>

        <!-- Cancellation Reason Modal -->
        <div x-show="showCancelModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-md"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-md"
             x-transition:leave-end="opacity-0 backdrop-blur-none"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" 
             style="display: none;"
             x-cloak>

            <!-- Modal Box -->
            <div @click.away="showCancelModal = false" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="w-full max-w-lg bg-[#0d1322] border border-slate-800 rounded-3xl p-6 shadow-2xl relative text-left overflow-hidden">
                
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
                    <button @click="showCancelModal = false" class="text-slate-400 hover:text-white transition p-1 rounded-lg hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Select Reason</label>
                        
                        <template x-for="reason in [
                            'Changed my mind',
                            'Ordered by mistake',
                            'Delivery time takes too long',
                            'Want to change order items',
                            'Other'
                        ]" :key="reason">
                            <label class="flex items-center p-3 rounded-xl border border-slate-800/80 bg-[#141a26]/40 hover:border-emerald-500/40 cursor-pointer transition">
                                <input type="radio" 
                                       name="cancellation_reason" 
                                       :value="reason" 
                                       x-model="selectedReason" 
                                       required
                                       class="text-emerald-500 focus:ring-emerald-500/20 focus:ring-offset-0 bg-slate-900 border-slate-700">
                                <span class="ms-3 text-xs font-semibold text-slate-200" x-text="reason"></span>
                            </label>
                        </template>
                    </div>

                    <div x-show="selectedReason === 'Other'" x-transition class="pt-2">
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Please specify your reason</label>
                        <textarea name="custom_reason" 
                                  x-model="customReason" 
                                  rows="2" 
                                  placeholder="Provide details..."
                                  class="w-full px-3 py-2 text-xs rounded-xl bg-[#141a26] border border-slate-800 text-slate-100 focus:border-emerald-500 focus:ring-0 transition"></textarea>
                    </div>

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