<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Your Food Cart') }}
            </h2>
            <a href="{{ route('menu') }}" class="text-sm font-semibold text-green-600 hover:text-green-700 transition flex items-center gap-1">
                &larr; Back to Menu
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/60 min-h-[calc(100vh-160px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('message'))
                <div class="p-4 bg-green-100 border border-green-200 text-green-800 rounded-2xl shadow-sm text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-red-100 border border-red-200 text-red-800 rounded-2xl shadow-sm text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(count($cart) > 0)
                <form action="{{ route('orders.create') }}" method="GET" id="checkout-form">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Cart Items List (2 Columns) -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                                
                                <!-- Header with Select All Toggle -->
                                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                                    <h3 class="font-bold text-slate-800 text-lg">
                                        Cart Items (<span id="selected-count">{{ count($cart) }}</span>/{{ count($cart) }})
                                    </h3>
                                    <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer">
                                        <input type="checkbox" id="select-all" checked class="w-4 h-4 text-green-600 rounded border-slate-300 focus:ring-green-500">
                                        Select All
                                    </label>
                                </div>

                                <div class="divide-y divide-slate-100">
                                    @foreach($cart as $index => $item)
                                        @php 
                                            $itemTotal = $item['unit_price'] * $item['quantity'];
                                        @endphp
                                        <div class="p-6 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                            
                                            <!-- Checkbox & Item Details -->
                                            <div class="flex items-start sm:items-center gap-4">
                                                <input type="checkbox" 
                                                       name="selected_items[]" 
                                                       value="{{ $index }}" 
                                                       checked 
                                                       data-price="{{ $itemTotal }}"
                                                       class="item-checkbox w-5 h-5 mt-1 sm:mt-0 text-green-600 rounded border-slate-300 focus:ring-green-500 cursor-pointer transition">

                                                <div class="space-y-1">
                                                    <h4 class="font-bold text-slate-800 text-base">{{ $item['item_name'] }}</h4>
                                                    <p class="text-xs text-slate-500">Unit Price: LKR {{ number_format($item['unit_price'], 2) }}</p>
                                                    
                                                    @if(!empty($item['customizations']))
                                                        <div class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded-md inline-block font-medium">
                                                            Customized
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Quantity Update Form & Subtotal -->
                                            <div class="flex items-center justify-between sm:justify-end gap-6 pl-9 sm:pl-0">
                                                
                                                <!-- Update Quantity Form -->
                                                <div onclick="event.stopPropagation()">
                                                    <div class="flex items-center space-x-2">
                                                        <input type="number" 
                                                               name="quantity_{{ $index }}" 
                                                               value="{{ $item['quantity'] }}" 
                                                               min="1" max="10" 
                                                               form="update-form-{{ $index }}"
                                                               class="w-16 px-2 py-1 text-center text-sm border border-slate-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                                                        <button type="submit" form="update-form-{{ $index }}" class="text-xs font-semibold px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                                                            Update
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Price & Remove -->
                                                <div class="text-right">
                                                    <span class="font-bold text-slate-800 block text-base">
                                                        LKR {{ number_format($itemTotal, 2) }}
                                                    </span>

                                                    <button type="submit" form="remove-form-{{ $index }}" class="text-xs font-semibold text-red-500 hover:text-red-700 transition mt-1">
                                                        Remove
                                                    </button>
                                                </div>

                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary Sidebar (1 Column) -->
                        <div class="lg:col-span-1">
                            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6 sticky top-6">
                                <h3 class="font-bold text-slate-800 text-lg border-b border-slate-100 pb-4">Order Summary</h3>

                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between text-slate-600">
                                        <span>Selected Subtotal</span>
                                        <span class="font-semibold text-slate-800" id="display-subtotal">LKR 0.00</span>
                                    </div>
                                    <div class="flex justify-between text-slate-600">
                                        <span>Estimated Delivery Fee</span>
                                        <span class="text-xs text-slate-400 italic">Calculated at Checkout</span>
                                    </div>
                                    <div class="border-t border-slate-100 pt-3 flex justify-between font-bold text-lg text-slate-800">
                                        <span>Total</span>
                                        <span class="text-green-600" id="display-total">LKR 0.00</span>
                                    </div>
                                </div>

                                <button type="submit" id="checkout-btn" class="w-full text-center py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow transition duration-150">
                                    Proceed to Checkout &rarr;
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

                <!-- Hidden forms for Quantity Update and Remove to avoid nesting forms -->
                @foreach($cart as $index => $item)
                    <form id="update-form-{{ $index }}" action="{{ route('cart.update', $index) }}" method="POST" class="hidden">
                        @csrf
                        @method('PATCH')
                    </form>

                    <form id="remove-form-{{ $index }}" action="{{ route('cart.remove', $index) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach

            @else
                <!-- Empty Cart State -->
                <div class="max-w-2xl mx-auto space-y-8 py-4">
                    <div class="bg-white p-10 sm:p-12 rounded-3xl border border-slate-100 shadow-sm text-center space-y-5">
                        <div class="p-4 bg-green-50 text-green-600 rounded-2xl w-20 h-20 mx-auto flex items-center justify-center">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-2xl font-black text-slate-800">Your Cart is Empty</h3>
                            <p class="text-sm text-slate-400 max-w-sm mx-auto">Looks like you haven't added any fresh organic food items to your cart yet.</p>
                        </div>
                        <a href="{{ route('menu') }}" class="inline-block px-8 py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl shadow-md hover:shadow-green-100 transition">
                            Explore Menu Now &rarr;
                        </a>
                    </div>

                    <!-- Highlights Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 text-center space-y-1 shadow-sm">
                            <div class="text-2xl">🌱</div>
                            <h4 class="font-bold text-slate-800 text-xs">100% Organic</h4>
                            <p class="text-[11px] text-slate-400">Fresh from local organic farms</p>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 text-center space-y-1 shadow-sm">
                            <div class="text-2xl">⚡</div>
                            <h4 class="font-bold text-slate-800 text-xs">Fast Delivery</h4>
                            <p class="text-[11px] text-slate-400">Quick & safe order delivery</p>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 text-center space-y-1 shadow-sm">
                            <div class="text-2xl">👨‍🍳</div>
                            <h4 class="font-bold text-slate-800 text-xs">Chef Crafted</h4>
                            <p class="text-[11px] text-slate-400">Healthy & delicious meals</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Dynamic Subtotal & Selection Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const selectAll = document.getElementById('select-all');
            const subtotalEl = document.getElementById('display-subtotal');
            const totalEl = document.getElementById('display-total');
            const countEl = document.getElementById('selected-count');
            const checkoutBtn = document.getElementById('checkout-btn');

            function updateTotals() {
                let total = 0;
                let selectedCount = 0;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseFloat(cb.dataset.price);
                        selectedCount++;
                    }
                });

                const formatted = 'LKR ' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                
                if(subtotalEl) subtotalEl.textContent = formatted;
                if(totalEl) totalEl.textContent = formatted;
                if(countEl) countEl.textContent = selectedCount;

                // Disable checkout button if no items selected
                if(checkoutBtn) {
                    if (selectedCount === 0) {
                        checkoutBtn.disabled = true;
                        checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        checkoutBtn.disabled = false;
                        checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }

                // Update select-all state
                if(selectAll) {
                    selectAll.checked = selectedCount === checkboxes.length;
                }
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateTotals));

            if(selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateTotals();
                });
            }

            // Initial calculation
            updateTotals();
        });
    </script>
</x-app-layout>