<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Checkout Order') }}
            </h2>
            <a href="{{ route('cart') }}" class="text-sm font-semibold text-green-600 hover:text-green-700 transition">
                &larr; Return to Cart
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="p-4 bg-red-100 border border-red-200 text-red-800 rounded-2xl shadow-sm text-sm font-medium">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <!-- Pass selected cart item indexes to controller -->
                @if(isset($selectedIndexes))
                    @foreach($selectedIndexes as $index)
                        <input type="hidden" name="selected_indexes[]" value="{{ $index }}">
                    @endforeach
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Checkout Details Form (2 Columns) -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Delivery Information -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                            <h3 class="font-bold text-slate-800 text-lg border-b border-slate-100 pb-3 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>1. Delivery Details</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Delivery Zone -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Delivery Zone *</label>
                                    <select name="delivery_zone_id" id="delivery-zone-select" required class="w-full rounded-xl border border-slate-200 text-sm focus:ring-green-500 focus:border-green-500">
                                        <option value="" data-fee="0" disabled selected>Select Your Zone</option>
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}" data-fee="{{ $zone->delivery_fee ?? 0 }}">
                                                {{ $zone->name ?? $zone->zone_name }} 
                                                @if(isset($zone->delivery_fee))
                                                    (+ LKR {{ number_format($zone->delivery_fee, 2) }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Dropoff Location -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Dropoff Address / Location *</label>
                                    <textarea name="dropoff_location" rows="3" required placeholder="Enter full address, street name, house number..." 
                                        class="w-full rounded-xl border border-slate-200 text-sm focus:ring-green-500 focus:border-green-500">{{ old('dropoff_location') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                            <h3 class="font-bold text-slate-800 text-lg border-b border-slate-100 pb-3 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <span>2. Payment Method</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="border border-slate-200 p-4 rounded-xl flex items-center space-x-3 cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="payment_method" value="cod" checked class="text-green-600 focus:ring-green-500">
                                    <div>
                                        <span class="block font-bold text-slate-800 text-sm">Cash on Delivery</span>
                                        <span class="text-xs text-slate-400">Pay when your order arrives</span>
                                    </div>
                                </label>

                                <label class="border border-slate-200 p-4 rounded-xl flex items-center space-x-3 cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="payment_method" value="card" class="text-green-600 focus:ring-green-500">
                                    <div>
                                        <span class="block font-bold text-slate-800 text-sm">Card Payment</span>
                                        <span class="text-xs text-slate-400">Online card payment</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Special Instructions -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                            <h3 class="font-bold text-slate-800 text-lg border-b border-slate-100 pb-3 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>3. Special Instructions (Optional)</span>
                            </h3>

                            <div>
                                <textarea name="special_instructions" rows="2" placeholder="Any allergy warnings, food spice preference, or delivery notes..." 
                                    class="w-full rounded-xl border border-slate-200 text-sm focus:ring-green-500 focus:border-green-500">{{ old('special_instructions') }}</textarea>
                            </div>
                        </div>

                    </div>

                    <!-- Order Summary Sidebar (1 Column) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6 sticky top-6">
                            <h3 class="font-bold text-slate-800 text-lg border-b border-slate-100 pb-4">Order Items</h3>

                            <div class="divide-y divide-slate-100 space-y-3 max-h-60 overflow-y-auto pr-1">
                                @php $subtotal = 0; @endphp
                                @foreach($cart as $item)
                                    @php 
                                        $itemTotal = $item['unit_price'] * $item['quantity'];
                                        $subtotal += $itemTotal;
                                    @endphp
                                    <div class="pt-3 flex justify-between text-xs">
                                        <div>
                                            <span class="font-bold text-slate-800 block">{{ $item['item_name'] }}</span>
                                            <span class="text-slate-400">Qty: {{ $item['quantity'] }} x LKR {{ number_format($item['unit_price'], 2) }}</span>
                                        </div>
                                        <span class="font-bold text-slate-700">LKR {{ number_format($itemTotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Subtotal element with data-subtotal attribute for JS access -->
                            <div class="border-t border-slate-100 pt-4 space-y-2 text-sm" id="order-summary-box" data-subtotal="{{ $subtotal }}">
                                <div class="flex justify-between text-slate-600">
                                    <span>Items Subtotal</span>
                                    <span class="font-semibold text-slate-800">LKR {{ number_format($subtotal, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-slate-600">
                                    <span>Delivery Fee</span>
                                    <span class="font-semibold text-slate-800" id="delivery-fee-display">LKR 0.00</span>
                                </div>

                                <div class="border-t border-slate-100 pt-3 flex justify-between font-bold text-base text-slate-800">
                                    <span>Total Amount</span>
                                    <span class="text-green-600" id="grand-total-display">LKR {{ number_format($subtotal, 2) }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow transition duration-150 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Confirm & Place Order</span>
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- Live Delivery Fee Calculation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderSummaryBox = document.getElementById('order-summary-box');
            const itemsSubtotal = orderSummaryBox ? parseFloat(orderSummaryBox.dataset.subtotal || 0) : 0;
            
            const zoneSelect = document.getElementById('delivery-zone-select');
            const deliveryFeeDisplay = document.getElementById('delivery-fee-display');
            const grandTotalDisplay = document.getElementById('grand-total-display');

            if (zoneSelect) {
                zoneSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const deliveryFee = parseFloat(selectedOption.dataset.fee || 0);
                    const grandTotal = itemsSubtotal + deliveryFee;

                    if (deliveryFeeDisplay) {
                        deliveryFeeDisplay.textContent = 'LKR ' + deliveryFee.toLocaleString('en-US', { 
                            minimumFractionDigits: 2, 
                            maximumFractionDigits: 2 
                        });
                    }

                    if (grandTotalDisplay) {
                        grandTotalDisplay.textContent = 'LKR ' + grandTotal.toLocaleString('en-US', { 
                            minimumFractionDigits: 2, 
                            maximumFractionDigits: 2 
                        });
                    }
                });
            }
        });
    </script>
</x-app-layout>