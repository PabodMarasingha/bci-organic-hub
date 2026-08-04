<div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <!-- Left: Product Details & Price Card -->
    <div class="space-y-5">
        <div class="h-64 sm:h-72 bg-slate-100 rounded-2xl overflow-hidden relative flex items-center justify-center">
            @if(isset($product->image_url) && $product->image_url)
                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <!-- Image එකක් නැති විට auto-load වන High-Quality Dummy Food Image එක -->
                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @endif

            <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur-md text-slate-800 shadow-sm border border-slate-100">
                Base: LKR {{ number_format($product->price ?? $product->base_price ?? 0, 2) }}
            </span>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $product->name }}</h1>
            <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                {{ $product->description ?? 'Deliciously prepared with fresh organic ingredients. Customization allows you to choose extra toppings and sizes.' }}
            </p>
        </div>

        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center">
            <span class="text-sm font-semibold text-slate-600">Total Price:</span>
            <span class="text-2xl font-extrabold text-green-600">
                LKR {{ number_format($this->totalPrice, 2) }}
            </span>
        </div>
    </div>

    <!-- Right: Customization Options & Add to Cart -->
    <div class="space-y-6 flex flex-col justify-between">
        <div class="space-y-5">
            <h3 class="font-bold text-slate-800 text-lg border-b border-slate-100 pb-3">Customize Your Order</h3>

            <!-- Size Options -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Portion Size</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['Regular' => 0, 'Medium' => 150, 'Large' => 300] as $size => $extra)
                        <button type="button" 
                                wire:click="$set('selectedSize', '{{ $size }}')"
                                class="py-2.5 px-2 text-xs font-bold rounded-xl border transition flex flex-col items-center justify-center {{ $selectedSize === $size ? 'bg-green-600 text-white border-green-600 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                            <span>{{ $size }}</span>
                            <span class="text-[10px] opacity-80 font-normal">{{ $extra > 0 ? '(+LKR '.$extra.')' : 'Standard' }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Extra Add-ons -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Extra Add-ons</label>
                <div class="space-y-2">
                    <!-- Extra Cheese -->
                    <label class="flex items-center justify-between p-3 border rounded-xl hover:bg-slate-50 cursor-pointer transition text-sm {{ in_array('Extra Cheese', $selectedAddons) ? 'border-green-300 bg-green-50/40' : 'border-slate-200 bg-white' }}">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" wire:model.live="selectedAddons" value="Extra Cheese" class="rounded text-green-600 focus:ring-green-500 border-slate-300">
                            <span class="font-medium text-slate-700">Extra Cheese</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500">+LKR 100</span>
                    </label>

                    <!-- Spicy Sauce -->
                    <label class="flex items-center justify-between p-3 border rounded-xl hover:bg-slate-50 cursor-pointer transition text-sm {{ in_array('Spicy Sauce', $selectedAddons) ? 'border-green-300 bg-green-50/40' : 'border-slate-200 bg-white' }}">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" wire:model.live="selectedAddons" value="Spicy Sauce" class="rounded text-green-600 focus:ring-green-500 border-slate-300">
                            <span class="font-medium text-slate-700">Spicy Sauce</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500">+LKR 50</span>
                    </label>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Quantity</label>
                <div class="flex items-center space-x-3">
                    <button type="button" wire:click="decrementQty" class="w-9 h-9 bg-slate-100 hover:bg-slate-200 active:scale-95 rounded-xl text-lg font-bold text-slate-700 flex items-center justify-center transition">-</button>
                    <span class="font-bold text-slate-800 text-base px-2">{{ $quantity }}</span>
                    <button type="button" wire:click="incrementQty" class="w-9 h-9 bg-slate-100 hover:bg-slate-200 active:scale-95 rounded-xl text-lg font-bold text-slate-700 flex items-center justify-center transition">+</button>
                </div>
            </div>
        </div>

        <!-- Add to Cart Action -->
        <button type="button" wire:click="addToCart" wire:loading.attr="disabled" class="w-full py-3.5 bg-green-600 hover:bg-green-700 active:scale-[0.99] disabled:opacity-50 text-white font-bold rounded-xl shadow-md transition flex items-center justify-center space-x-2 mt-4">
            <svg wire:loading.remove wire:target="addToCart" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <svg wire:loading wire:target="addToCart" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span wire:loading.remove wire:target="addToCart">Add Customized Item to Cart</span>
            <span wire:loading wire:target="addToCart">Adding to Cart...</span>
        </button>
    </div>

</div>