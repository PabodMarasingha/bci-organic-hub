<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen text-slate-100 font-sans">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span class="p-2.5 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Checkout Order
                </h1>
                <p class="text-slate-400 text-sm mt-1">Review your meal details and complete delivery info.</p>
            </div>

            <!-- Validation Error Alert -->
            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-2xl mb-6 backdrop-blur-md flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-semibold">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('orders.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                @csrf

                <!-- Left Column: Delivery Form -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-slate-900/80 backdrop-blur-md p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-2xl space-y-6">
                        
                        <h2 class="text-lg font-bold text-slate-200 border-b border-slate-800 pb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Delivery Information
                        </h2>

                        <!-- Delivery Zone Dropdown -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Delivery Zone</label>
                            <select name="delivery_zone_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition" required>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropoff Location -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Dropoff Location / Room No.</label>
                            <input type="text" name="dropoff_location" placeholder="e.g., Lab 201" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition shadow-inner" required>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Payment Method</label>
                            <select name="payment_method" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition" required>
                                <option value="digital_wallet">Campus Digital Wallet</option>
                                <option value="card">Credit / Debit Card</option>
                                <option value="cash_on_delivery">Cash on Delivery</option>
                            </select>
                        </div>

                        <!-- Special Instructions -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Special Instructions</label>
                            <input type="text" name="special_instructions" placeholder="e.g., Less dressing" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition shadow-inner">
                        </div>

                    </div>
                </div>

                <!-- Right Column: Order Summary Card -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-slate-900/80 backdrop-blur-md p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-2xl flex flex-col justify-between">
                        
                        <div>
                            <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-5">
                                <h2 class="text-lg font-bold text-slate-200 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"></path>
                                    </svg>
                                    Order Summary
                                </h2>
                                @php
                                    $totalCalories = collect($cart)->sum('total_calories');
                                @endphp
                                @if($totalCalories > 0)
                                    <span class="text-xs px-2.5 py-1 bg-orange-500/10 border border-orange-500/30 text-orange-400 font-bold rounded-full">
                                        🔥 {{ number_format($totalCalories) }} kcal
                                    </span>
                                @endif
                            </div>

                            <!-- Cart Items List -->
                            <div class="space-y-3 mb-6 max-h-60 overflow-y-auto pr-1">
                                @foreach ($cart as $item)
                                    <div class="p-3.5 bg-slate-950/60 rounded-2xl border border-slate-800/80 flex justify-between items-center">
                                        <div>
                                            <h4 class="font-bold text-slate-200 text-sm">{{ $item['item_name'] }}</h4>
                                            @if(isset($item['quantity']))
                                                <span class="text-xs text-slate-400">Qty: {{ $item['quantity'] }}</span>
                                            @endif
                                        </div>
                                        <span class="font-extrabold text-emerald-400 text-sm">
                                            Rs. {{ number_format($item['unit_price'] * ($item['quantity'] ?? 1), 2) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total Price -->
                            <div class="pt-4 border-t border-slate-800 text-base">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-300">Total Price</span>
                                    <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">
                                        Rs. {{ number_format(collect($cart)->sum(fn($i) => $i['unit_price'] * ($i['quantity'] ?? 1)), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit" 
                                class="w-full mt-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-extrabold rounded-2xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transform active:scale-95 transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Place Order Now</span>
                        </button>

                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>