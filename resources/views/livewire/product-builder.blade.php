<div class="py-10 bg-slate-950 min-h-screen text-slate-100 font-sans">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <!-- Main Card with Dark Glassmorphism Effect (Livewire Cart Form) -->
        <div class="bg-slate-900/80 backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 transition-all duration-300">
            
            <!-- Hero Header Section -->
            <div class="relative group overflow-hidden bg-gradient-to-r from-emerald-950/60 to-slate-900 p-8 sm:p-10 border-b border-slate-800">
                <div class="flex flex-col md:flex-row items-center gap-8 z-10 relative">
                    
                    <!-- Food Image -->
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
                            
                            <!-- TOP STAR RATING BADGE -->
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold rounded-full">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                                {{ number_format($product->average_rating ?? 0, 1) }} / 5
                                <span class="text-[10px] text-amber-400/70 font-medium ml-1">({{ $product->reviews_count ?? 0 }} Reviews)</span>
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
                        
                        <label class="inline-flex items-center gap-2 cursor-pointer bg-slate-800/80 hover:bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-700 transition">
                            <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 rounded border-slate-600 bg-slate-900 text-emerald-500 focus:ring-emerald-500/30 focus:ring-offset-slate-900">
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
                                    <input type="checkbox" value="{{ $ingredient->id }}" wire:model.live="selectedIngredients"
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
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Quantity</label>
                        <input type="number" wire:model.live="quantity" min="1" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 font-bold focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition shadow-inner outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Special Instructions</label>
                        <textarea wire:model="specialInstructions" rows="2" 
                                  class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200 placeholder-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition shadow-inner outline-none" 
                                  placeholder="E.g. Less spicy, sauce on the side, extra crispy..."></textarea>
                    </div>
                </div>

                <!-- Footer Summary Bar with Live Price & Calories -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-6 border-t border-slate-800 bg-slate-950/40 -mx-6 sm:-mx-10 -mb-6 sm:-mb-10 p-6 sm:p-8 rounded-b-3xl">
                    <div class="flex items-center gap-6">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Total Estimated Price</span>
                            <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200 tracking-tight mt-1">
                                LKR {{ number_format($runningTotal, 2) }}
                            </div>
                        </div>
                        <div class="h-10 w-[1px] bg-slate-800 hidden sm:block"></div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Total Energy</span>
                            <div class="text-2xl sm:text-3xl font-black text-orange-400 flex items-center gap-1 mt-1">
                                🔥 <span>{{ number_format($runningCalories) }}</span> <span class="text-xs text-orange-400/70 font-normal">kcal</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
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


        <!-- ========================================== -->
        <!-- CUSTOMER REVIEWS & RATINGS DISPLAY (DARAZ STYLE) -->
        <!-- ========================================== -->
        <style>
            .star-rating input:checked ~ label { color: #fbbf24; }
            .star-rating label:hover,
            .star-rating label:hover ~ label { color: #fcd34d; }
            
            /* Custom Scrollbar for Comments Section */
            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
        </style>

        <div class="bg-slate-900/50 backdrop-blur-md p-6 sm:p-8 rounded-3xl border border-slate-800/80 shadow-xl transition-all duration-300">
            <h3 class="text-xl font-bold text-white tracking-wide mb-6">Ratings & Reviews of {{ $product->name }}</h3>

            <!-- Rating Summary Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center border-b border-slate-800/80 pb-8 mb-8">
                
                <!-- Left Side: Big Average Rating -->
                <div class="flex flex-col items-center justify-center space-y-2 md:border-r border-slate-800/80">
                    <div class="flex items-baseline gap-1">
                        <span class="text-5xl font-black text-white">{{ number_format($product->average_rating ?? 0, 1) }}</span>
                        <span class="text-2xl font-bold text-slate-500">/5</span>
                    </div>
                    
                    <!-- Stars Display -->
                    <div class="flex gap-1 text-amber-400">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= round($product->average_rating ?? 0) ? 'fill-current' : 'text-slate-700' }}" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-xs text-slate-400 font-medium">{{ $product->reviews_count ?? 0 }} Ratings</span>
                </div>

                <!-- Right Side: Progress Bars -->
                <div class="md:col-span-2 space-y-2.5">
                    @php
                        $totalReviews = $product->reviews_count ?? 0;
                        $reviewsList = collect($product->reviews ?? []);
                    @endphp

                    @for ($star = 5; $star >= 1; $star--)
                        @php
                            $starCount = $reviewsList->where('rating', $star)->count();
                            $percentage = $totalReviews > 0 ? ($starCount / $totalReviews) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-3 text-sm">
                            <!-- Star Icons -->
                            <div class="flex gap-0.5 text-amber-400 w-24">
                                @for ($j = 1; $j <= 5; $j++)
                                    <svg class="w-4 h-4 {{ $j <= $star ? 'fill-current' : 'text-slate-700' }}" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                @endfor
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="flex-1 h-2.5 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ $percentage }}%;"></div>
                            </div>
                            
                            <!-- Count -->
                            <div class="text-xs text-slate-400 font-medium w-8 text-right">{{ $starCount }}</div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Customer Comments List -->
            <div>
                <h4 class="text-sm font-bold text-slate-300 uppercase tracking-widest mb-6">Product Reviews</h4>
                
                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse ($product->reviews()->latest()->get() as $review)
                        <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-800 transition hover:border-slate-700">
                            <!-- User & Stars -->
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <div class="flex gap-1 text-amber-400 mb-1.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-700' }}" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <div class="text-xs font-semibold text-slate-300 flex items-center gap-1.5">
                                        {{ $review->user->name ?? 'Customer' }}
                                        <span class="text-emerald-500 flex items-center gap-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Verified
                                        </span>
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-500 font-medium">{{ $review->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Comment Text -->
                            @if($review->comment)
                                <p class="text-sm text-slate-400 leading-relaxed">
                                    {{ $review->comment }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 bg-slate-900/30 rounded-2xl border border-slate-800 border-dashed">
                            <svg class="w-10 h-10 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-sm text-slate-500 font-medium">No reviews yet. Be the first to review!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CUSTOMER RATING & FEEDBACK FORM AREA       -->
        <!-- ========================================== -->
        <div class="bg-slate-900/50 backdrop-blur-md p-6 sm:p-8 rounded-3xl border border-slate-800/80 shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white tracking-wide">Write a Review</h3>
                    <p class="text-xs text-slate-400 mt-1">Share your experience to help others make a healthy choice.</p>
                </div>
            </div>

            <form action="{{ route('review.store', $product->id) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Your Rating</label>
                    <div class="flex flex-row-reverse justify-end star-rating w-max">
                        <input type="radio" id="star5" name="rating" value="5" class="hidden" required />
                        <label for="star5" class="cursor-pointer text-slate-700 transition duration-150 p-1">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </label>
                        <input type="radio" id="star4" name="rating" value="4" class="hidden" />
                        <label for="star4" class="cursor-pointer text-slate-700 transition duration-150 p-1">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </label>
                        <input type="radio" id="star3" name="rating" value="3" class="hidden" />
                        <label for="star3" class="cursor-pointer text-slate-700 transition duration-150 p-1">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </label>
                        <input type="radio" id="star2" name="rating" value="2" class="hidden" />
                        <label for="star2" class="cursor-pointer text-slate-700 transition duration-150 p-1">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </label>
                        <input type="radio" id="star1" name="rating" value="1" class="hidden" />
                        <label for="star1" class="cursor-pointer text-slate-700 transition duration-150 p-1">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Write a Review <span class="text-slate-600 normal-case font-normal">(Optional)</span></label>
                    <textarea name="feedback_text" rows="3" 
                        class="w-full bg-slate-950 border border-slate-800/80 text-slate-200 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-4 transition duration-200 outline-none placeholder:text-slate-600 resize-none shadow-inner" 
                        placeholder="How was the taste and freshness of the ingredients?"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500 hover:text-slate-950 font-bold rounded-xl transition duration-300 text-sm flex items-center gap-2 shadow-lg hover:shadow-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
        <!-- ========================================== -->

    </div>
</div>