<x-app-layout>
    <div class="min-h-screen bg-organic-cream dark:bg-organic-charcoal text-organic-charcoal dark:text-organic-cream py-10 px-4 sm:px-6 lg:px-8 transition-colors duration-300 selection:bg-organic-gold selection:text-organic-charcoal">
        <div class="max-w-7xl mx-auto space-y-10">

            <!-- Hero & Header Banner -->
            <div class="relative overflow-hidden bg-organic-green dark:bg-organic-green-light p-8 sm:p-10 rounded-3xl shadow-xl">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-organic-gold/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="space-y-3 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-organic-gold/20 border border-organic-gold/30 text-organic-gold text-xs font-bold uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-organic-gold"></span>
                            Fresh & Organic Ingredients
                        </div>
                        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                            Nourish Your Body With <span class="text-organic-gold">Healthy Meals</span>
                        </h1>
                        <p class="text-white/80 text-sm sm:text-base leading-relaxed">
                            Crafted with premium organic ingredients. Choose from our curated standard menu or customize your meal to fit your exact macro needs.
                        </p>
                    </div>

                    <a href="{{ route('cart') }}" class="group relative inline-flex items-center gap-3 px-6 py-4 bg-organic-gold hover:bg-organic-gold/90 text-organic-charcoal font-black text-sm rounded-2xl shadow-xl transform hover:-translate-y-0.5 transition duration-300 shrink-0">
                        <div class="relative">
                            <svg class="w-6 h-6 transition group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute -top-2 -right-2 w-5 h-5 bg-organic-tomato text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-organic-gold">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </div>
                        <span>View My Cart</span>
                    </a>
                </div>
            </div>

            @if (session('message'))
                <div class="flex items-center gap-3 bg-organic-green/10 dark:bg-organic-green-light/20 border border-organic-green/30 text-organic-green dark:text-organic-gold p-4 rounded-2xl">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs sm:text-sm font-bold">{{ session('message') }}</span>
                </div>
            @endif

            @if(isset($types))
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($types as $type)
                        <a href="{{ route('customer.menu', ['type' => $type]) }}"
                           class="px-5 py-2.5 rounded-xl text-xs font-bold transition duration-300 border {{ request('type', 'All') == $type ? 'bg-organic-green text-white border-organic-green' : 'bg-white dark:bg-organic-charcoal border-organic-green/20 dark:border-white/10 text-organic-charcoal dark:text-organic-cream hover:border-organic-green' }}">
                            {{ $type }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center justify-between border-b border-organic-green/10 dark:border-white/10 pb-4">
                <div>
                    <h2 class="font-display text-xl font-bold tracking-wide">Signature Healthy Menu</h2>
                    <p class="text-xs text-organic-charcoal/60 dark:text-organic-cream/60 mt-1">Select an item to add directly or build your custom bowl</p>
                </div>
                <span class="text-xs font-bold bg-white dark:bg-organic-charcoal border border-organic-green/20 dark:border-white/10 px-3 py-1.5 rounded-xl">
                    {{ count($products) }} Items Available
                </span>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <div class="group relative bg-white dark:bg-[#243b30] border border-organic-green/10 dark:border-white/10 hover:border-organic-gold/60 rounded-2xl p-5 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col justify-between hover:-translate-y-1">

                        <div>
                            <!-- Product Image — capped, consistent aspect ratio -->
                            <div class="relative w-full aspect-[4/3] max-h-40 bg-organic-cream dark:bg-organic-charcoal rounded-xl overflow-hidden mb-4 border border-organic-green/10 dark:border-white/10 flex items-center justify-center">
                                @php
                                    $imageSrc = $product->image ?? $product->image_url ?? null;
                                @endphp

                                @if(!empty($imageSrc))
                                    <img src="{{ Str::startsWith($imageSrc, 'http') ? $imageSrc : asset($imageSrc) }}" 
     alt="{{ $product->name }}" 
     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="flex flex-col items-center gap-2 text-organic-green/40 dark:text-organic-cream/30">
                                        <svg class="w-10 h-10 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span class="text-[10px] font-semibold tracking-wider uppercase">Organic Prep</span>
                                    </div>
                                @endif

                                <div class="absolute top-2 right-2 bg-organic-charcoal/90 backdrop-blur-md px-2.5 py-1 rounded-lg">
                                    <span class="text-xs font-black text-organic-gold">Rs. {{ number_format($product->price, 2) }}</span>
                                </div>
                            </div>

                            <h3 class="font-display text-lg font-bold group-hover:text-organic-green dark:group-hover:text-organic-gold transition line-clamp-1">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-organic-charcoal/60 dark:text-organic-cream/60 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $product->description ?? 'Nutritious organic meal prepared fresh upon order with balanced protein and macros.' }}
                            </p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-organic-green/10 dark:border-white/10 space-y-2">
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full py-2.5 bg-organic-cream dark:bg-organic-charcoal hover:bg-organic-green hover:text-white text-organic-charcoal dark:text-organic-cream font-bold text-xs rounded-lg border border-organic-green/20 dark:border-white/10 transition duration-300 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Quick Add
                                </button>
                            </form>

                            <a href="{{ route('customer.build', $product->id) }}" class="w-full py-2.5 bg-organic-gold/10 hover:bg-organic-gold text-organic-gold hover:text-organic-charcoal font-black text-xs rounded-lg border border-organic-gold/30 transition duration-300 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Customize
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-organic-charcoal border border-organic-green/10 dark:border-white/10 rounded-3xl p-12 text-center">
                        <h3 class="font-display text-lg font-bold">No Meals Available</h3>
                        <p class="text-xs text-organic-charcoal/60 dark:text-organic-cream/60 mt-1">Check back shortly!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>