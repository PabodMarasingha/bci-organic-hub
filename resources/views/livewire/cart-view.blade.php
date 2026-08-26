<div class="py-12 bg-slate-950 min-h-screen text-slate-100 font-sans selection:bg-emerald-500 selection:text-slate-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header Section -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-800">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-widest mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Order Review
                </span>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Your Cart</h1>
            </div>
            <a href="{{ route('menu') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 flex items-center gap-1 transition">
                &larr; Back to Menu
            </a>
        </div>

        <!-- Session Message -->
        @if (session()->has('message'))
            <div class="p-4 text-sm text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl flex items-center gap-2" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        @if (!empty($cart))
            <!-- Select All Checkbox Bar -->
            <div class="flex items-center justify-between px-2">
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" id="select-all" checked onchange="toggleSelectAll(this)" 
                           class="w-5 h-5 rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500/30 focus:ring-offset-0 cursor-pointer">
                    <span class="text-xs font-bold text-slate-300">Select All Items</span>
                </label>
                <span class="text-xs text-slate-400 font-semibold" id="selected-count">0 items selected</span>
            </div>
        @endif

        <!-- Main Checkout Form -->
        <form id="cart-checkout-form" method="POST" action="{{ route('orders.create') }}">
            @csrf
            
            <!-- Cart Items List -->
            <div class="space-y-4">
                @forelse ($cart as $index => $item)
                    @php
                        $unitPrice = $item['unit_price'] ?? 0;
                        $qty = $item['quantity'] ?? 1;
                    @endphp
                    <div class="cart-item bg-slate-900/80 backdrop-blur-md border border-slate-800 hover:border-slate-700 rounded-2xl p-5 sm:p-6 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-5 shadow-lg"
                         data-index="{{ $index }}" data-price="{{ $unitPrice }}">
                        
                        <!-- Checkbox & Item Info -->
                        <div class="flex items-start gap-4 flex-1">
                            <input type="checkbox" name="selected_items[]" value="{{ $index }}" checked onchange="recalculateTotal()"
                                   class="item-checkbox mt-1 w-5 h-5 rounded border-slate-700 bg-slate-950 text-emerald-500 focus:ring-emerald-500/30 focus:ring-offset-0 cursor-pointer shrink-0">

                            <div class="space-y-1.5 flex-1">
                                <h3 class="text-lg font-bold text-white">{{ $item['item_name'] ?? 'Custom Healthy Item' }}</h3>

                                <!-- Customizations Display -->
                                @if (!empty($item['customizations']))
                                    <div class="flex flex-wrap gap-1.5 items-center">
                                        <span class="text-xs text-emerald-400 font-semibold">Add-ons:</span>
                                        @foreach ($item['customizations'] as $custom)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-[11px] font-medium rounded-md">
                                                + {{ is_array($custom) ? ($custom['name'] ?? '') : $custom }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <p class="text-xs text-slate-400">
                                    Unit Price: <span class="text-emerald-400 font-medium">LKR {{ number_format($unitPrice, 2) }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Quantity, Subtotal & Actions -->
                        <div class="flex items-center justify-between sm:justify-end gap-5 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-800">
                            
                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-1 bg-slate-950 border border-slate-800 rounded-xl px-2 py-1">
                                <button type="button" 
                                        wire:click="updateQuantity({{ $index }}, {{ max(1, $qty - 1) }})" 
                                        onclick="adjustQtyUI('{{ $index }}', -1)"
                                        class="w-6 h-6 flex items-center justify-center bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition">-</button>
                                
                                <span id="qty-val-{{ $index }}" class="item-qty w-7 text-center text-xs font-black text-emerald-400">{{ $qty }}</span>
                                
                                <button type="button" 
                                        wire:click="updateQuantity({{ $index }}, {{ $qty + 1 }})" 
                                        onclick="adjustQtyUI('{{ $index }}', 1)"
                                        class="w-6 h-6 flex items-center justify-center bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition">+</button>
                            </div>

                            <!-- Subtotal Display -->
                            <div class="text-left sm:text-right min-w-[90px]">
                                <span class="text-[10px] text-slate-500 uppercase tracking-wider block font-semibold">Subtotal</span>
                                <span class="item-subtotal text-lg font-black text-emerald-400" id="subtotal-{{ $index }}">
                                    LKR {{ number_format($unitPrice * $qty, 2) }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2">
                                <!-- Single Item Order Button -->
                                <button type="button" 
                                        onclick="orderSingleItem('{{ $index }}')"
                                        class="px-3 py-2 bg-emerald-500/10 hover:bg-emerald-500 hover:text-slate-950 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs font-bold transition duration-150">
                                    Order
                                </button>

                                <!-- Remove Button -->
                                <button type="button" 
                                        wire:click="removeFromCart({{ $index }})" 
                                        class="p-2 text-slate-500 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition duration-150"
                                        title="Remove item">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>

                        </div>

                    </div>
                @empty
                    <!-- Empty Cart State -->
                    <div class="bg-slate-900/50 border border-slate-800 rounded-3xl p-12 text-center">
                        <div class="w-16 h-16 bg-slate-800 text-slate-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Your Cart is Empty</h3>
                        <p class="text-slate-400 text-sm mb-6 max-w-sm mx-auto">
                            Browse our menu and build your custom meal!
                        </p>
                        <a href="{{ route('menu') }}" class="inline-flex px-6 py-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold text-sm rounded-xl hover:bg-emerald-500 hover:text-slate-950 transition">
                            Browse the Menu &rarr;
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Footer Summary Bar -->
            @if (!empty($cart))
                <div class="mt-8 bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-2xl">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Total Selected Amount</span>
                        <div id="selected-total" class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200 mt-1">
                            LKR 0.00
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="button" 
                                wire:click="clearCart" 
                                class="px-5 py-4 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-2xl transition duration-150 text-sm">
                            Clear Cart
                        </button>

                        <button type="submit" id="checkout-btn"
                                class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-extrabold rounded-2xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transform active:scale-95 transition-all duration-200 text-center flex items-center justify-center space-x-2">
                            <span>Proceed to Checkout</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </form>

    </div>
</div>

<!-- Dynamic Logic Script -->
<script>
    document.addEventListener('DOMContentLoaded', recalculateTotal);
    document.addEventListener('livewire:load', recalculateTotal);

    function recalculateTotal() {
        let grandTotal = 0;
        let selectedCount = 0;

        document.querySelectorAll('.cart-item').forEach(item => {
            const checkbox = item.querySelector('.item-checkbox');
            if (checkbox && checkbox.checked) {
                const price = parseFloat(item.dataset.price) || 0;
                const qty = parseInt(item.querySelector('.item-qty').innerText) || 1;
                grandTotal += price * qty;
                selectedCount++;
            }
        });

        const totalDisplay = document.getElementById('selected-total');
        if (totalDisplay) {
            totalDisplay.innerText = 'LKR ' + grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        const countDisplay = document.getElementById('selected-count');
        if (countDisplay) {
            countDisplay.innerText = selectedCount + ' items selected';
        }

        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            if (selectedCount === 0) {
                checkoutBtn.disabled = true;
                checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                checkoutBtn.disabled = false;
                checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    function toggleSelectAll(master) {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = master.checked);
        recalculateTotal();
    }

    function adjustQtyUI(index, change) {
        const item = document.querySelector(`.cart-item[data-index="${index}"]`);
        const qtyElement = item.querySelector('.item-qty');
        let currentQty = parseInt(qtyElement.innerText) || 1;
        let newQty = currentQty + change;

        if (newQty < 1) return;

        qtyElement.innerText = newQty;
        const price = parseFloat(item.dataset.price) || 0;
        const subtotalElement = document.getElementById(`subtotal-${index}`);
        if (subtotalElement) {
            subtotalElement.innerText = 'LKR ' + (price * newQty).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        recalculateTotal();
    }

    function orderSingleItem(index) {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = false);
        const targetItem = document.querySelector(`.cart-item[data-index="${index}"] .item-checkbox`);
        if (targetItem) targetItem.checked = true;
        recalculateTotal();
        document.getElementById('cart-checkout-form').submit();
    }
</script>