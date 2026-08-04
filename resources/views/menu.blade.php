<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 max-w-7xl mx-auto">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Our Delicious Menu') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Explore our fresh, organic, and healthy food options.</p>
            </div>
            
            <!-- Cart Shortcut Button -->
            <a href="{{ route('cart') }}" class="inline-flex items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                </svg>
                <span>View Cart</span>
                <span class="ml-2 bg-white text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ count(session('cart', [])) }}
                </span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-[calc(100vh-160px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('message'))
                <div class="p-4 bg-green-100 border border-green-200 text-green-800 rounded-2xl shadow-sm flex justify-between items-center transition">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium text-sm">{{ session('message') }}</span>
                    </div>
                    <a href="{{ route('cart') }}" class="text-xs font-bold underline hover:text-green-900 shrink-0">Go to Cart &rarr;</a>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-100 border border-red-200 text-red-800 rounded-2xl shadow-sm text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search & Filter Bar -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <form method="GET" action="{{ route('menu') }}" class="flex flex-col md:flex-row gap-4 justify-between items-center">
                    
                    <!-- Search Input -->
                    <div class="relative w-full md:w-1/2">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search food by name or ingredient..." 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-green-500 text-sm">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Category Buttons -->
                    <div class="flex flex-wrap gap-2 w-full md:w-auto">
                        @php
                            $categories = [
                                'all' => 'All Items', 
                                'meals' => 'Meals', 
                                'healthy' => 'Healthy Options', 
                                'beverages' => 'Beverages', 
                                'desserts' => 'Desserts'
                            ];
                            $currentCat = request('category', 'all');
                        @endphp

                        @foreach($categories as $key => $label)
                            <a href="{{ route('menu', array_merge(request()->query(), ['category' => $key])) }}" 
                               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentCat === $key ? 'bg-green-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <!-- Animation Card Wrapper -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between transform transition-all duration-300 hover:-translate-y-2 hover:scale-105 hover:shadow-2xl z-10 hover:z-20 group">
                        
                        <!-- Clickable Header & Image directing to Customization -->
                        <a href="{{ route('build', $product) }}" class="block">
                            <!-- Product Image / Dummy Image Fallback -->
                            <div class="h-48 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                                @if(isset($product->image_url) && $product->image_url)
                                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <!-- High quality Dummy Food Image based on Product ID -->
                                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80&sig={{ $product->id ?? rand(1, 100) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @endif

                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur-md text-slate-800 shadow-sm border border-slate-100">
                                    LKR {{ number_format($product->price ?? $product->base_price ?? 0, 2) }}
                                </span>
                            </div>

                            <!-- Product Info -->
                            <div class="p-5 space-y-2">
                                <h3 class="font-bold text-slate-800 text-lg leading-snug group-hover:text-green-600 transition">{{ $product->name }}</h3>
                                <p class="text-slate-500 text-xs leading-relaxed line-clamp-2">
                                    {{ $product->description ?? 'Deliciously prepared with organic ingredients.' }}
                                </p>
                            </div>
                        </a>

                        <!-- Actions Section -->
                        <div class="p-5 pt-0 space-y-2">
                            <!-- Customize Button (Primary Action) -->
                            <a href="{{ route('build', $product) }}" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center space-x-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                <span>Customize & Select</span>
                            </a>

                            <!-- Quick Direct Add to Cart Form -->
                            <form action="{{ route('cart.add') }}" method="POST" class="flex gap-2 items-center">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">

                                <button type="submit" class="w-full py-2 bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 font-bold text-xs rounded-xl transition flex items-center justify-center space-x-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span>Quick Add</span>
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-100 text-center space-y-3">
                        <svg class="w-16 h-16 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-slate-700">No products found!</h3>
                        <p class="text-sm text-slate-400">Try adjusting your search terms or category filter.</p>
                        <a href="{{ route('menu') }}" class="inline-block mt-2 text-xs font-bold text-green-600 underline">Reset Filters</a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>