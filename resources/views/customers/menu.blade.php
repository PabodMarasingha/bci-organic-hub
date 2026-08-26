<x-app-layout>
    <div class="min-h-screen bg-[#070d19] text-slate-100 py-10 px-4 sm:px-6 lg:px-8 selection:bg-cyan-500 selection:text-slate-950">
        <div class="max-w-7xl mx-auto space-y-10">

            <!-- Hero & Header Banner -->
            <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-[#10192d] to-slate-900 border border-slate-800/80 p-8 sm:p-10 rounded-3xl shadow-2xl backdrop-blur-xl">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="space-y-3 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-widest animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                            Fresh & Organic Ingredients
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                            Nourish Your Body With <span class="bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400 bg-clip-text text-transparent">Healthy Meals</span>
                        </h1>
                        <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                            Crafted with premium organic ingredients. Choose from our curated standard menu or customize your meal to fit your exact macro needs.
                        </p>
                    </div>

                    <!-- Cart Summary Quick Button -->
                    <a href="{{ route('cart') }}" class="group relative inline-flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-cyan-500 to-emerald-400 hover:from-cyan-400 hover:to-emerald-300 text-slate-950 font-black text-sm rounded-2xl shadow-xl shadow-cyan-500/10 transform hover:-translate-y-0.5 transition duration-300 shrink-0">
                        <div class="relative">
                            <svg class="w-5 h-5 text-slate-950 transition group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span id="cart-count-badge" class="{{ (session('cart') && count(session('cart')) > 0) ? '' : 'hidden' }} absolute -top-2 -right-2.5 w-5 h-5 bg-slate-950 text-cyan-400 text-[11px] font-black rounded-full flex items-center justify-center border-2 border-cyan-400 animate-bounce">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                        </div>
                        <span>View Cart</span>
                    </a>
                </div>
            </div>

            <!-- Dynamic Alert Message (AJAX Toast Notification) -->
            <div id="ajax-toast" class="hidden fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-emerald-500 border border-emerald-400 text-slate-950 p-4 rounded-2xl shadow-2xl backdrop-blur-md transition-all duration-300">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="ajax-toast-message" class="text-xs sm:text-sm font-black">Item added to cart!</span>
            </div>

            <!-- Session Alert Messages (Standard Fallback) -->
            @if (session('success') || session('message'))
                <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-2xl shadow-lg backdrop-blur-md">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs sm:text-sm font-bold">{{ session('success') ?? session('message') }}</span>
                </div>
            @endif

            <!-- Category Filters Section -->
            @if(isset($types))
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($types as $type)
                        <a href="{{ route('customer.menu', ['type' => $type]) }}" 
                           class="px-5 py-2.5 rounded-xl text-xs font-bold transition duration-300 border {{ request('type', 'All') == $type ? 'bg-cyan-500 border-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'bg-slate-900/80 border-slate-800 text-slate-400 hover:border-slate-700 hover:text-slate-200' }}">
                            {{ $type }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Section Title -->
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-wide">Signature Healthy Menu</h2>
                    <p class="text-xs text-slate-400 mt-1">Select an item to add directly or customize ingredients</p>
                </div>
                <span class="text-xs font-bold text-slate-400 bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-xl">
                    {{ count($products) }} Items Available
                </span>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($products as $product)
                    <div class="group relative bg-slate-900/80 border border-slate-800 hover:border-cyan-500/40 rounded-3xl p-6 shadow-xl hover:shadow-cyan-500/10 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5">
                        
                        <div>
                            <!-- Product Image -->
                            <div class="relative w-full h-48 bg-slate-800/80 rounded-2xl overflow-hidden mb-5 border border-slate-700/50 flex items-center justify-center group-hover:border-cyan-500/30 transition">
                                @php
                                    $imageSrc = $product->image ?? $product->image_url ?? null;
                                @endphp

                                @if(!empty($imageSrc))
                                    <img src="{{ Str::startsWith($imageSrc, 'http') ? $imageSrc : asset('storage/' . $imageSrc) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="flex flex-col items-center gap-2 text-slate-500 group-hover:text-cyan-400 transition">
                                        <svg class="w-12 h-12 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span class="text-[11px] font-semibold tracking-wider uppercase">Organic Prep</span>
                                    </div>
                                @endif

                                <!-- Price Badge -->
                                <div class="absolute top-3 right-3 bg-slate-950/80 backdrop-blur-md border border-slate-700/80 px-3 py-1 rounded-xl">
                                    <span class="text-xs font-black text-cyan-400">Rs. {{ number_format($product->price, 2) }}</span>
                                </div>
                            </div>

                            <!-- Title & Description -->
                            <h3 class="text-lg font-bold text-white group-hover:text-cyan-300 transition line-clamp-1">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-2 line-clamp-2 leading-relaxed">
                                {{ $product->description ?? 'Nutritious organic meal prepared fresh upon order with balanced protein and macros.' }}
                            </p>

                            <!-- ========================================== -->
                            <!-- PERFECTLY CLIPPED STAR RATING DISPLAY      -->
                            <!-- ========================================== -->
                            <div class="flex items-center gap-2.5 mt-4">
                                <!-- Star Visual Container -->
                                <div class="relative inline-block text-slate-500 text-sm">
                                    <!-- Background Stars (Empty) -->
                                    <div class="flex gap-1">
                                        @for ($i = 0; $i < 5; $i++)
                                            <!-- shrink-0 අනිවාර්යයෙන්ම තිබිය යුතුය -->
                                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    
                                    <!-- Foreground Stars Container (Clipped using overflow-hidden) -->
                                    <div class="absolute top-0 left-0 overflow-hidden h-full" 
                                         style="width: {{ (($product->average_rating ?? 0) / 5) * 100 }}%;">
                                        <!-- w-max දමා තරු මිරිකීම වැළැක්වීම -->
                                        <div class="flex gap-1 w-max text-amber-400">
                                            @for ($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <!-- Text Rating Display -->
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xs font-black text-slate-200">{{ number_format($product->average_rating ?? 0, 1) }} <span class="text-[10px] text-slate-500 font-bold">/ 5</span></span>
                                    <span class="text-[10px] font-medium text-slate-500">({{ $product->reviews_count ?? 0 }})</span>
                                </div>
                            </div>
                            <!-- ========================================== -->
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-6 mt-6 border-t border-slate-800/80 space-y-3">
                            <!-- Direct Quick Add Form (AJAX Handled) -->
                            <form onsubmit="handleQuickAdd(event, this)">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="quick-add-btn w-full py-3 bg-slate-800 hover:bg-cyan-500 hover:text-slate-950 text-slate-200 font-bold text-xs rounded-xl border border-slate-700 hover:border-cyan-400 transition duration-300 flex items-center justify-center gap-2 group/btn shadow-md">
                                    <svg class="w-4 h-4 text-cyan-400 group-hover/btn:text-slate-950 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>+ Quick Add Standard</span>
                                </button>
                            </form>

                            <!-- Customize Button -->
                            <a href="{{ route('customer.build', $product) }}" class="w-full py-3 bg-emerald-500/10 hover:bg-emerald-500 text-emerald-400 hover:text-slate-950 font-black text-xs rounded-xl border border-emerald-500/30 hover:border-emerald-400 transition duration-300 flex items-center justify-center gap-2 group/custom shadow-md">
                                <svg class="w-4 h-4 text-emerald-400 group-hover/custom:text-slate-950 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Customize Ingredients
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full bg-slate-900/50 border border-slate-800 rounded-3xl p-12 text-center">
                        <h3 class="text-lg font-bold text-slate-300">No Meals Available</h3>
                        <p class="text-xs text-slate-500 mt-1">Check back shortly for new organic menu updates.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- JavaScript for Background AJAX Add To Cart -->
    <script>
        function handleQuickAdd(event, form) {
            event.preventDefault();

            const button = form.querySelector('.quick-add-btn');
            const originalHTML = button.innerHTML;

            // Loading UI state
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin w-4 h-4 text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Adding...</span>
            `;

            const formData = new FormData(form);

            fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(response => {
                // Return status update or simple success text
                return response.ok ? response.text() : Promise.reject('Error adding item');
            })
            .then(() => {
                // Update badge count dynamically
                const badge = document.getElementById('cart-count-badge');
                if (badge) {
                    let currentCount = parseInt(badge.innerText) || 0;
                    badge.innerText = currentCount + 1;
                    badge.classList.remove('hidden');
                }

                // Show Toast Notification
                showToast("Item added to cart successfully!");
            })
            .catch(error => {
                console.error("Cart error:", error);
                showToast("Failed to add item. Try again.");
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalHTML;
            });
        }

        function showToast(message) {
            const toast = document.getElementById('ajax-toast');
            const toastMessage = document.getElementById('ajax-toast-message');
            
            toastMessage.innerText = message;
            toast.classList.remove('hidden');

            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
    </script>
</x-app-layout>