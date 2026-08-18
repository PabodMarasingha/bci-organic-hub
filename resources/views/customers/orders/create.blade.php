<x-app-layout>
    <!-- Alpine.js for Card Modal & Interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="py-12 bg-[#0b1329] min-h-screen text-slate-100 font-sans selection:bg-emerald-500 selection:text-slate-950"
         x-data="{ 
            paymentMethod: '{{ old('payment_method', 'cod') }}', 
            showCardModal: false,
            cardNumber: '{{ old('card_number', '') }}',
            cardExpiry: '{{ old('card_expiry', '') }}',
            cardCvc: '{{ old('card_cvc', '') }}',
            cardHolder: '{{ old('card_holder', '') }}',
            cardError: '',
            isCustomZone: false,
            customZoneName: '{{ old('custom_zone_name', '') }}',
            selectedZoneFee: 0,
            
            // Delivery zone change handle කිරීම
            handleZoneChange(e) {
                const value = e.target.value;
                if (value === 'other') {
                    this.isCustomZone = true;
                    this.selectedZoneFee = 0;
                } else {
                    this.isCustomZone = false;
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    this.selectedZoneFee = selectedOption && selectedOption.dataset.fee ? parseFloat(selectedOption.dataset.fee) : 0;
                }
            },

            // Custom zone එක switch back කිරීම
            resetZoneSelect() {
                this.isCustomZone = false;
                this.customZoneName = '';
                this.selectedZoneFee = 0;
                $nextTick(() => {
                    const select = document.getElementById('delivery_zone_id');
                    if(select) select.value = '';
                });
            },

            // Validate main fields before opening Card Modal
            openCardModal() {
                const form = document.getElementById('checkout-form');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                this.showCardModal = true;
            },

            // Format Card Number
            formatCardNumber(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.substring(0, 16);
                this.cardNumber = value.replace(/(.{4})/g, '$1 ').trim();
            },

            // Format Expiry Date
            formatExpiry(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 2) {
                    this.cardExpiry = value.substring(0, 2) + '/' + value.substring(2, 4);
                } else {
                    this.cardExpiry = value;
                }
            },

            // Submit Card Form with Validation
            submitCardPayment() {
                if (!this.cardHolder || !this.cardNumber || !this.cardExpiry || !this.cardCvc) {
                    this.cardError = 'කරුණාකර සියලුම කාඩ්පත් තොරතුරු ඇතුළත් කරන්න.';
                    return;
                }
                this.cardError = '';
                document.getElementById('checkout-form').submit();
            }
         }">
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header section -->
            <div class="flex items-center justify-between pb-6 border-b border-slate-800/80">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Final Step
                    </span>
                    <h1 class="text-3xl font-black text-white tracking-tight mt-1">Checkout & Delivery</h1>
                </div>
                <a href="{{ route('cart') }}" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center gap-1">
                    &larr; Edit Cart
                </a>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-2xl text-red-400 text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @php
                $subtotal = collect($cart)->sum(fn($i) => $i['unit_price'] * $i['quantity']);
            @endphp

            <form method="POST" action="{{ route('orders.store') }}" id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf

                <!-- Left Column: Form Details -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. Contact / Recipient Information -->
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 space-y-4 shadow-xl">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Recipient Contact Info
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Recipient Name</label>
                                <input type="text" name="contact_name" value="{{ old('contact_name', auth()->user()->name ?? '') }}" required placeholder="e.g. John Doe"
                                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contact Number</label>
                                <input type="tel" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone_number ?? '') }}" required placeholder="0712345678"
                                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Delivery Zone & Address Selection -->
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 space-y-4 shadow-xl">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            Delivery Location
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Delivery Zone</label>
                                
                                <!-- Select Box Zone Mode -->
                                <div x-show="!isCustomZone">
                                    <select name="delivery_zone_id" id="delivery_zone_id" @change="handleZoneChange" :required="!isCustomZone" :disabled="isCustomZone" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition cursor-pointer">
                                        <option value="" disabled {{ old('delivery_zone_id') ? '' : 'selected' }}>Select your delivery zone...</option>
                                        @foreach($zones as $zone)
                                            @php
                                                $fee = $zone->delivery_fee ?? $zone->fee ?? 0;
                                            @endphp
                                            <option value="{{ $zone->id }}" data-fee="{{ $fee }}" {{ old('delivery_zone_id') == $zone->id ? 'selected' : '' }}>
                                                {{ $zone->zone_name ?? $zone->name }} 
                                                @if($fee > 0) - (LKR {{ number_format($fee, 2) }}) @else - (Free Delivery) @endif
                                            </option>
                                        @endforeach
                                        <option value="other" class="text-emerald-400 font-bold">+ Type custom location...</option>
                                    </select>
                                </div>

                                <!-- Custom Text Input Mode -->
                                <div x-show="isCustomZone" class="flex gap-2">
                                    <input type="text" name="custom_zone_name" x-model="customZoneName" :required="isCustomZone" placeholder="Type your area or town (e.g. Maharagama)..." 
                                           class="w-full bg-slate-950 border border-emerald-500/50 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition">
                                    <button type="button" @click="resetZoneSelect()" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition">
                                        Cancel
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Dropoff Address / Location Details</label>
                                <textarea name="dropoff_location" rows="3" required placeholder="Enter full address or specific dropoff instructions..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition">{{ old('dropoff_location') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Payment Method Selection -->
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 space-y-4 shadow-xl">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Payment Method
                        </h2>

                        <input type="hidden" name="payment_method" :value="paymentMethod">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label @click="paymentMethod = 'cod'" 
                                   :class="paymentMethod === 'cod' ? 'border-emerald-500 bg-emerald-500/10' : 'border-slate-800 bg-slate-950'"
                                   class="relative border rounded-2xl p-4 flex items-center gap-3 cursor-pointer transition">
                                <input type="radio" value="cod" :checked="paymentMethod === 'cod'" class="text-emerald-500 focus:ring-emerald-500">
                                <span class="text-xs font-bold text-slate-200">Cash on Delivery</span>
                            </label>
                            
                            <label @click="paymentMethod = 'card'" 
                                   :class="paymentMethod === 'card' ? 'border-emerald-500 bg-emerald-500/10' : 'border-slate-800 bg-slate-950'"
                                   class="relative border rounded-2xl p-4 flex items-center gap-3 cursor-pointer transition">
                                <input type="radio" value="card" :checked="paymentMethod === 'card'" class="text-emerald-500 focus:ring-emerald-500">
                                <span class="text-xs font-bold text-slate-200">Card Payment</span>
                            </label>
                        </div>
                    </div>

                    <!-- 4. Special Instructions -->
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 space-y-2 shadow-xl">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Kitchen Instructions (Optional)</label>
                        <input type="text" name="special_instructions" value="{{ old('special_instructions') }}" placeholder="e.g. Less spicy, extra cutlery..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition">
                    </div>

                </div>

                <!-- Right Column: Order Summary -->
                <div class="space-y-6">
                    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 space-y-4 shadow-2xl sticky top-6">
                        <h3 class="text-lg font-bold text-white border-b border-slate-800 pb-3">Order Summary</h3>

                        <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                            @foreach($cart as $item)
                                <div class="flex items-start justify-between text-xs pb-2 border-b border-slate-800/40 last:border-0">
                                    <div>
                                        <p class="font-bold text-slate-200">{{ $item['item_name'] }}</p>
                                        <p class="text-slate-500">Qty: {{ $item['quantity'] }}</p>
                                        @if(!empty($item['customizations']))
                                            <p class="text-[10px] text-emerald-400/80 mt-0.5">
                                                + {{ implode(', ', array_column($item['customizations'], 'name')) }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="font-semibold text-emerald-400 ml-2">LKR {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-slate-800 pt-4 space-y-2">
                            <div class="flex justify-between text-slate-400 text-xs font-semibold">
                                <span>Subtotal</span>
                                <span>LKR {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div class="flex justify-between text-slate-400 text-xs font-semibold">
                                <span>Delivery Fee</span>
                                <span x-text="'LKR ' + selectedZoneFee.toFixed(2)">LKR 0.00</span>
                            </div>

                            <div class="flex justify-between text-white text-base font-black pt-2 border-t border-slate-800/60">
                                <span>Total Payable</span>
                                <span class="text-emerald-400" x-text="'LKR ' + ({{ $subtotal }} + selectedZoneFee).toFixed(2)">LKR {{ number_format($subtotal, 2) }}</span>
                            </div>
                        </div>

                        <!-- Dynamic Action Buttons -->
                        <template x-if="paymentMethod === 'cod'">
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-black text-sm rounded-2xl shadow-xl shadow-emerald-500/20 transform active:scale-95 transition duration-200 flex items-center justify-center gap-2">
                                <span>Confirm Order & Dispatch</span>
                            </button>
                        </template>

                        <template x-if="paymentMethod === 'card'">
                            <button type="button" @click="openCardModal()" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-black text-sm rounded-2xl shadow-xl shadow-emerald-500/20 transform active:scale-95 transition duration-200 flex items-center justify-center gap-2">
                                <span>Pay with Card</span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Card Payment Modal Window -->
                <div x-show="showCardModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
                    <div @click.away="showCardModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative">
                        
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-base font-extrabold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                Credit / Debit Card
                            </h3>
                            <button type="button" @click="showCardModal = false" class="text-slate-400 hover:text-white text-xl font-bold">&times;</button>
                        </div>

                        <!-- Card Graphical Representation -->
                        <div class="bg-gradient-to-tr from-slate-950 to-slate-800 border border-slate-700/60 rounded-2xl p-5 mb-6 shadow-inner text-slate-200">
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-[10px] font-bold tracking-widest uppercase text-emerald-400">BCI Secure Checkout</span>
                                <span class="text-xs italic font-bold">VISA / MASTER</span>
                            </div>
                            <div class="text-base font-mono tracking-widest mb-4" x-text="cardNumber ? cardNumber : '•••• •••• •••• ••••'"></div>
                            <div class="flex justify-between text-xs">
                                <div>
                                    <span class="block text-[9px] text-slate-400 uppercase">Card Holder</span>
                                    <span class="font-bold uppercase text-white" x-text="cardHolder ? cardHolder : 'YOUR NAME'"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-400 uppercase">Expires</span>
                                    <span class="font-bold text-white" x-text="cardExpiry ? cardExpiry : 'MM/YY'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Error Alert -->
                        <template x-if="cardError">
                            <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-xs" x-text="cardError"></div>
                        </template>

                        <!-- Card Inputs -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cardholder Name</label>
                                <input type="text" x-model="cardHolder" name="card_holder" placeholder="John Doe" class="w-full bg-slate-950 border border-slate-800 text-slate-100 text-sm rounded-xl p-3 focus:border-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Card Number</label>
                                <input type="text" x-model="cardNumber" @input="formatCardNumber" maxlength="19" name="card_number" placeholder="4532 1122 3344 5566" class="w-full bg-slate-950 border border-slate-800 text-slate-100 text-sm rounded-xl p-3 focus:border-emerald-500 outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Expiry Date</label>
                                    <input type="text" x-model="cardExpiry" @input="formatExpiry" maxlength="5" name="card_expiry" placeholder="MM/YY" class="w-full bg-slate-950 border border-slate-800 text-slate-100 text-sm rounded-xl p-3 focus:border-emerald-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">CVC / CVV</label>
                                    <input type="password" x-model="cardCvc" maxlength="4" name="card_cvc" placeholder="123" class="w-full bg-slate-950 border border-slate-800 text-slate-100 text-sm rounded-xl p-3 focus:border-emerald-500 outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Action Controls -->
                        <div class="mt-6 flex gap-3">
                            <button type="button" @click="showCardModal = false" class="w-1/2 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl text-xs uppercase tracking-wider">Cancel</button>
                            <button type="button" @click="submitCardPayment" class="w-1/2 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20">Pay & Confirm</button>
                        </div>

                    </div>
                </div>

            </form>

        </div>
    </div>

    <!-- JavaScript Prevent Double Submit -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkoutForm = document.getElementById('checkout-form');

            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function (e) {
                    const submitBtns = checkoutForm.querySelectorAll('button[type="submit"]');
                    submitBtns.forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                        btn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-slate-950 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        `;
                    });
                });
            }
        });
    </script>
</x-app-layout>