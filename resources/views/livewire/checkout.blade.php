<div class="py-10 bg-slate-950 min-h-screen text-slate-100 font-sans">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                <span class="p-2.5 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
                Checkout Order
            </h1>
            <p class="text-slate-400 text-sm mt-1">Review your healthy custom meal order and complete payment details.</p>
        </div>

        <form wire:submit.prevent="placeOrder" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Order & Delivery Details Form -->
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
                        <select wire:model="deliveryZone" 
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                            <option value="">Select a Delivery Zone</option>
                            <option value="Zone A - Downtown">Zone A - Downtown</option>
                            <option value="Zone B - Main Campus">Zone B - Main Campus</option>
                            <option value="Zone C - Science Block">Zone C - Science Block</option>
                        </select>
                        @error('deliveryZone') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Dropoff Location -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Dropoff Location / Room No.</label>
                        <input type="text" 
                               wire:model="dropoffLocation" 
                               placeholder="e.g., Lab 201, Building B - 2nd Floor" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition shadow-inner">
                        @error('dropoffLocation') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Payment Method</label>
                        <select wire:model="paymentMethod" 
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                            <option value="Campus Digital Wallet">Campus Digital Wallet</option>
                            <option value="Cash on Delivery">Cash on Delivery</option>
                            <option value="Online Card Payment">Credit / Debit Card</option>
                        </select>
                        @error('paymentMethod') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Special Instructions -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Special Instructions</label>
                        <textarea wire:model="specialInstructions" 
                                  rows="3" 
                                  placeholder="e.g., Less dressing, call upon arrival..." 
                                  class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200 placeholder-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition shadow-inner"></textarea>
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
                            <span class="text-xs px-2.5 py-1 bg-orange-500/10 border border-orange-500/30 text-orange-400 font-bold rounded-full">
                                🔥 {{ number_format($totalCalories) }} kcal
                            </span>
                        </div>

                        <!-- Cart Items List -->
                        <div class="space-y-4 mb-6 max-h-60 overflow-y-auto pr-1">
                            @forelse($cart as $item)
                                <div class="p-3.5 bg-slate-950/60 rounded-2xl border border-slate-800/80 flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-slate-200 text-sm">{{ $item['item_name'] }}</h4>
                                        <div class="text-xs text-slate-400 mt-1">
                                            Qty: <span class="font-bold text-slate-200">{{ $item['quantity'] }}</span>
                                        </div>
                                        @if(!empty($item['customizations']))
                                            <div class="text-[11px] text-slate-500 mt-1">
                                                Add-ons: {{ implode(', ', array_column($item['customizations'], 'name')) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="font-extrabold text-emerald-400 text-sm">
                                        Rs. {{ number_format($item['unit_price'] * $item['quantity'], 2) }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-500 text-sm">
                                    Your order cart is currently empty.
                                </div>
                            @endforelse
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 pt-4 border-t border-slate-800 text-sm">
                            <div class="flex justify-between text-slate-400">
                                <span>Subtotal</span>
                                <span class="font-semibold text-slate-200">Rs. {{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Delivery Fee</span>
                                <span class="font-semibold text-slate-200">Rs. {{ number_format($deliveryFee, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Total Energy</span>
                                <span class="font-semibold text-orange-400">🔥 {{ number_format($totalCalories) }} kcal</span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-slate-800 text-base">
                                <span class="font-bold text-slate-100">Grand Total</span>
                                <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">
                                    Rs. {{ number_format($grandTotal, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
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