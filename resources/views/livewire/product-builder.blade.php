<div class="py-10 bg-slate-950 min-h-screen text-slate-100 font-sans">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Main Card with Dark Glassmorphism Effect -->
        <div class="bg-slate-900/80 backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 transition-all duration-300">
            
            <!-- Hero Header Section -->
            <div class="relative group overflow-hidden bg-gradient-to-r from-emerald-950/60 to-slate-900 p-8 sm:p-10 border-b border-slate-800">
                <div class="flex flex-col md:flex-row items-center gap-8 z-10 relative">
                    
                    <!-- Food Image (With Fallback for Broken Images) -->
                    <div class="relative flex-shrink-0">
                        <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-70 transition duration-500"></div>
                        <img src="{{ !empty($product->image) && file_exists(public_path('storage/' . $product->image)) ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&q=80&w=600' }}" 
                             alt="{{ $product->name }}" 
                             class="relative w-40 h-40 md:w-48 md:h-48 rounded-2xl object-cover shadow-2xl transform group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Product Details & Base Nutrition -->
                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-wider">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Custom Meal Builder
                            </span>
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-500/10 border border-orange-500/30 text-orange-400 text-xs font-bold rounded-full">
                                🔥 Base: {{ $product->base_calories ?? 0 }} kcal
                            </span>
                        </div>

                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                            {{ $product->name }}
                        </h1>
                        <p class="text-slate-400 mt-3 text-sm sm:text-base leading-relaxed max-w-xl">
                            {{ $product->description }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- Form Section -->
            <form wire:submit.prevent="addToCart" class="p-6 sm:p-10 space-y-8">
                
                <!-- Ingredients Grid -->
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-bold text-slate-200 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Select Your Add-ons / Ingredients
                        </h3>
                        
                        <!-- Select All / Deselect All Option -->
                        <label class="inline-flex items-center gap-2 cursor-pointer bg-slate-800/80 hover:bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-700 transition">
                            <input type="checkbox" 
                                   wire:model.live="selectAll" 
                                   class="w-4 h-4 rounded border-slate-600 bg-slate-900 text-emerald-500 focus:ring-emerald-500/30 focus:ring-offset-slate-900">
                            <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Select All</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($ingredients as $ingredient)
                            @php 
                                $isSelected = in_array((string)$ingredient->id, array_map('strval', $selectedIngredients)); 
                            @endphp
                            
                            <label class="relative flex items-center justify-between p-4 rounded-2xl cursor-pointer transition-all duration-200 group border
                                {{ $isSelected ? 'bg-emerald-950/40 border-emerald-500/80 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'bg-slate-800/40 border-slate-800 hover:border-emerald-500/50 hover:bg-slate-800/80' }}">
                                
                                <div class="flex items-center space-x-3.5">
                                    <input type="checkbox" 
                                           value="{{ $ingredient->id }}" 
                                           wire:model.live="selectedIngredients"
                                           class="w-5 h-5 rounded-md border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500/30 focus:ring-offset-slate-900 transition">
                                    
                                    <div>
                                        <span class="font-medium text-slate-200 group-hover:text-emerald-400 transition-colors text-sm sm:text-base block">
                                            {{ $ingredient->name }}
                                        </span>
                                        <span class="text-[11px] text-orange-400/90 font-semibold">
                                            +{{ $ingredient->calories ?? 0 }} kcal
                                        </span>
                                    </div>
                                </div>

                                <span class="text-xs sm:text-sm font-semibold text-emerald-400/90 bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">
                                    + LKR {{ number_format($ingredient->price, 2) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Quantity & Special Instructions Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-slate-800/60">
                    
                    <!-- Quantity -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Quantity</label>
                        <div class="relative">
                            <input type="number" 
                                   wire:model.live="quantity" 
                                   min="1" 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 font-bold focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition shadow-inner outline-none">
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Special Instructions</label>
                        <textarea wire:model="specialInstructions" 
                                  rows="2" 
                                  class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200 placeholder-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition shadow-inner outline-none" 
                                  placeholder="E.g. Less spicy, sauce on the side, extra crispy..."></textarea>
                    </div>

                </div>

                <!-- Footer Summary Bar with Live Price & Calories -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-6 border-t border-slate-800 bg-slate-950/40 -mx-6 sm:-mx-10 -mb-6 sm:-mb-10 p-6 sm:p-8 rounded-b-3xl">
                    
                    <div class="flex items-center gap-6">
                        <!-- Total Price -->
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Total Estimated Price</span>
                            <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200 tracking-tight mt-1">
                                LKR {{ number_format($runningTotal, 2) }}
                            </div>
                        </div>

                        <div class="h-10 w-[1px] bg-slate-800 hidden sm:block"></div>

                        <!-- Live Calories Display -->
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Total Energy</span>
                            <div class="text-2xl sm:text-3xl font-black text-orange-400 flex items-center gap-1 mt-1">
                                🔥 <span>{{ number_format($runningCalories) }}</span> <span class="text-xs text-orange-400/70 font-normal">kcal</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-extrabold rounded-2xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transform active:scale-95 transition-all duration-200 flex items-center justify-center space-x-2 cursor-pointer disabled:opacity-50">
                        
                        <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                        </svg>
                        
                        <span wire:loading.remove>Add To Order Cart</span>
                        <span wire:loading>Adding to Cart...</span>
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>