<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Our Delicious Menu') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Explore our fresh, organic and healthy food options.</p>
            </div>
            
            <!-- Cart Shortcut Button -->
            <a href="{{ route('cart') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                </svg>
                View Cart ({{ count(session('cart', [])) }})
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success Message Alert -->
            @if(session('message'))
                <div class="p-4 bg-green-100 border border-green-200 text-green-800 rounded-2xl shadow-sm flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium text-sm">{{ session('message') }}</span>
                    </div>
                    <a href="{{ route('cart') }}" class="text-xs font-bold underline hover:text-green-900">Go to Cart &rarr;</a>
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
                            $categories = ['all' => 'All Items', 'meals' => 'Meals', 'healthy' => 'Healthy Options', 'beverages' => 'Beverages', 'desserts' => 'Desserts'];
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
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        
                        <div>
                            <!-- Product Image / Placeholder -->
                            <div class="h-48 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                                @if(isset($product->image_url) && $product->image_url)
                                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-slate-300 text-center p-4">
                                        <svg class="w-16 h-16 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <span class="text-xs font-semibold uppercase tracking-wider">No Image</span>
                                    </div>
                                @endif

                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur-md text-slate-700 shadow-sm">
                                    LKR {{ number_format($product->price, 2) }}
                                </span>
                            </div>

                            <!-- Product Info -->
                            <div class="p-5 space-y-2">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-bold text-slate-800 text-lg leading-snug">{{ $product->name }}</h3>
                                </div>
                                <p class="text-slate-500 text-xs leading-relaxed line-clamp-2">
                                    {{ $product->description ?? 'Deliciously prepared with organic ingredients.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Add to Cart Form -->
                        <div class="p-5 pt-0">
                            <form action="{{ route('cart.add') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold text-slate-400 uppercase">Qty:</span>
                                    <input type="number" name="quantity" value="1" min="1" max="10" 
                                        class="w-20 px-2 py-1 text-center text-sm border border-slate-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                                </div>

                                <button type="submit" class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded-xl shadow transition duration-150 flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span>Add to Cart</span>
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