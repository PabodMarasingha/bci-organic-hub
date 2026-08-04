<div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-100 shadow-sm max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <!-- Left: Product Details & Price Card -->
    <div class="space-y-4">
        <div class="h-64 bg-slate-100 rounded-xl overflow-hidden relative flex items-center justify-center">
            @if($product->image_url)
                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="text-slate-300 text-center">
                    <svg class="w-16 h-16 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-xs font-semibold uppercase">No Image</span>
                </div>
            @endif
        </div>

        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $product->name }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $product->description }}</p>
        </div>

        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center">
            <span class="text-sm font-semibold text-slate-600">Total Price:</span>
            <span class="text-xl font-extrabold text-green-600">
                LKR {{ number_format($this->totalPrice, 2) }}
            </span>
        </div>
    </div>

    <!-- Right: Customization Options & Add to Cart -->
    <div class="space-y-6 flex flex-col justify-between">
        <div class="space-y-4">
            <h3 class="font-bold text-slate-700 text-lg border-b pb-2">Customize Your Order</h3>

            <!-- Size Options (Example Customization) -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase">Portion Size</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['Regular' => 0, 'Medium' => 150, 'Large' => 300] as $size => $extra)
                        <button type="button" 
                                wire:click="$set('selectedSize', '{{ $size }}')"
                                class="py-2 text-xs font-bold rounded-xl border transition {{ $selectedSize === $size ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                            {{ $size }} {{ $extra > 0 ? '(+'.$extra.')' : '' }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Extra Add-ons (Example Customization) -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase">Extra Add-ons</label>
                <div class="space-y-2">
                    <label class="flex items-center space-x-3 p-3 border rounded-xl hover:bg-slate-50 cursor-pointer text-sm">
                        <input type="checkbox" wire:model.live="selectedAddons" value="Extra Cheese" class="rounded text-green-600 focus:ring-green-500">
                        <span class="font-medium text-slate-700">Extra Cheese (+LKR 100)</span>
                    </label>
                    <label class="flex items-center space-x-3 p-3 border rounded-xl hover:bg-slate-50 cursor-pointer text-sm">
                        <input type="checkbox" wire:model.live="selectedAddons" value="Spicy Sauce" class="rounded text-green-600 focus:ring-green-500">
                        <span class="font-medium text-slate-700">Spicy Sauce (+LKR 50)</span>
                    </label>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase">Quantity</label>
                <div class="flex items-center space-x-3">
                    <button type="button" wire:click="decrementQty" class="w-9 h-9 bg-slate-100 hover:bg-slate-200 rounded-lg text-lg font-bold text-slate-600 flex items-center justify-center">-</button>
                    <span class="font-bold text-slate-800 text-base">{{ $quantity }}</span>
                    <button type="button" wire:click="incrementQty" class="w-9 h-9 bg-slate-100 hover:bg-slate-200 rounded-lg text-lg font-bold text-slate-600 flex items-center justify-center">+</button>
                </div>
            </div>
        </div>

        <!-- Add to Cart Action -->
        <button type="button" wire:click="addToCart" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow transition flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Add Customized Item to Cart</span>
        </button>
    </div>

</div>