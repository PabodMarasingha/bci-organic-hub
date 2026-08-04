<div class="py-8 bg-slate-50 min-h-[calc(100vh-160px)]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Back Header -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('menu') }}" class="p-2.5 bg-white hover:bg-slate-100 rounded-xl transition text-slate-600 border border-slate-200 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Customize Your Meal
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Tailor {{ $product->name }} to match your taste!</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Column: Product Overview -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm overflow-hidden sticky top-6">
                    <div class="h-64 sm:h-80 bg-slate-100 rounded-2xl relative overflow-hidden flex items-center justify-center">
                        @if(isset($product->image_url) && $product->image_url)
                            <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @endif

                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur-md text-slate-800 shadow border border-slate-100">
                            Base: LKR {{ number_format($product->price ?? $product->base_price ?? 0, 2) }}
                        </span>
                    </div>

                    <div class="p-4 space-y-2">
                        <h3 class="text-2xl font-bold text-slate-800">{{ $product->name }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            {{ $product->description ?? 'Deliciously prepared with fresh organic ingredients. Customization allows you to choose extra toppings and sizes.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Livewire Options -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Portion / Size Selection -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                    <h4 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <span>1. Choose Portion Size</span>
                        <span class="text-xs font-normal text-slate-400">(Required)</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Regular -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition {{ $selectedSize === 'Regular' ? 'border-green-600 bg-green-50/50 text-green-900' : 'border-slate-100 bg-white hover:border-slate-200 text-slate-700' }}">
                            <input type="radio" wire:model.live="selectedSize" value="Regular" class="sr-only">
                            <span class="font-bold text-sm">Regular</span>
                            <span class="text-xs text-slate-500 mt-1">Standard</span>
                        </label>

                        <!-- Medium -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition {{ $selectedSize === 'Medium' ? 'border-green-600 bg-green-50/50 text-green-900' : 'border-slate-100 bg-white hover:border-slate-200 text-slate-700' }}">
                            <input type="radio" wire:model.live="selectedSize" value="Medium" class="sr-only">
                            <span class="font-bold text-sm">Medium</span>
                            <span class="text-xs text-slate-500 mt-1">+ LKR 150.00</span>
                        </label>

                        <!-- Large -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition {{ $selectedSize === 'Large' ? 'border-green-600 bg-green-50/50 text-green-900' : 'border-slate-100 bg-white hover:border-slate-200 text-slate-700' }}">
                            <input type="radio" wire:model.live="selectedSize" value="Large" class="sr-only">
                            <span class="font-bold text-sm">Large</span>
                            <span class="text-xs text-slate-500 mt-1">+ LKR 300.00</span>
                        </label>
                    </div>
                </div>

                <!-- Extra Add-ons Selection -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                    <h4 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <span>2. Extra Toppings & Add-ons</span>
                        <span class="text-xs font-normal text-slate-400">(Optional)</span>
                    </h4>

                    <div class="space-y-2.5">
                        <!-- Extra Cheese -->
                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-100 hover:border-slate-200 cursor-pointer transition {{ in_array('Extra Cheese', $selectedAddons) ? 'bg-green-50/30 border-green-200' : 'bg-white' }}">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" wire:model.live="selectedAddons" value="Extra Cheese" class="w-4 h-4 text-green-600 rounded border-slate-300 focus:ring-green-500">
                                <span class="text-sm font-semibold text-slate-700">Extra Cheese</span>
                            </div>
                            <span class="text-xs font-bold text-slate-600">+ LKR 100.00</span>
                        </label>

                        <!-- Spicy Sauce -->
                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-100 hover:border-slate-200 cursor-pointer transition {{ in_array('Spicy Sauce', $selectedAddons) ? 'bg-green-50/30 border-green-200' : 'bg-white' }}">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" wire:model.live="selectedAddons" value="Spicy Sauce" class="w-4 h-4 text-green-600 rounded border-slate-300 focus:ring-green-500">
                                <span class="text-sm font-semibold text-slate-700">Spicy Sauce</span>
                            </div>
                            <span class="text-xs font-bold text-slate-600">+ LKR 50.00</span>
                        </label>
                    </div>
                </div>

                <!-- Quantity & Add to Cart Action -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <!-- Livewire Quantity Buttons -->
                        <div class="flex items-center space-x-3 bg-slate-100 p-1.5 rounded-2xl">
                            <button type="button" wire:click="decrementQty" class="w-8 h-8 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-200 transition shadow-sm flex items-center justify-center">-</button>
                            <span class="font-bold text-sm px-2 text-slate-800">{{ $quantity }}</span>
                            <button type="button" wire:click="incrementQty" class="w-8 h-8 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-200 transition shadow-sm flex items-center justify-center">+</button>
                        </div>

                        <!-- Real-time Calculated Total Price -->
                        <div class="text-right">
                            <p class="text-xs text-slate-400 font-medium">Total Price</p>
                            <p class="text-2xl font-extrabold text-green-600">LKR {{ number_format($this->totalPrice, 2) }}</p>
                        </div>
                    </div>

                    <!-- Livewire Add to Cart Button -->
                    <button type="button" wire:click="addToCart" wire:loading.attr="disabled" class="w-full py-3.5 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-bold text-sm rounded-2xl shadow-lg shadow-green-600/20 transition flex items-center justify-center space-x-2">
                        <svg wire:loading.remove wire:target="addToCart" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                        </svg>
                        <svg wire:loading wire:target="addToCart" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="addToCart">Add Customized Meal to Cart</span>
                        <span wire:loading wire:target="addToCart">Adding...</span>
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>