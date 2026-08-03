<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                🍔 Delicious Menu
            </h2>
            
            <!-- Live Search Bar -->
            <form method="GET" action="{{ route('menu') }}" class="w-full sm:w-72">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search organic foods..." 
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Category Filter Badges -->
            <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 scrollbar-none">
                <a href="{{ route('menu') }}" 
                   class="px-5 py-2 rounded-full text-xs font-bold transition duration-200 whitespace-nowrap {{ !request('category') || request('category') == 'all' ? 'bg-green-600 text-white shadow-md shadow-green-500/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    All Items
                </a>
                <a href="{{ route('menu', ['category' => 'burgers']) }}" 
                   class="px-5 py-2 rounded-full text-xs font-bold transition duration-200 whitespace-nowrap {{ request('category') == 'burgers' ? 'bg-green-600 text-white shadow-md shadow-green-500/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    Burgers
                </a>
                <a href="{{ route('menu', ['category' => 'organic_bowls']) }}" 
                   class="px-5 py-2 rounded-full text-xs font-bold transition duration-200 whitespace-nowrap {{ request('category') == 'organic_bowls' ? 'bg-green-600 text-white shadow-md shadow-green-500/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    Organic Bowls
                </a>
                <a href="{{ route('menu', ['category' => 'beverages']) }}" 
                   class="px-5 py-2 rounded-full text-xs font-bold transition duration-200 whitespace-nowrap {{ request('category') == 'beverages' ? 'bg-green-600 text-white shadow-md shadow-green-500/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    Beverages
                </a>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl transition duration-300 flex flex-col overflow-hidden group">
                            
                            <!-- Image Container -->
                            <div class="relative h-48 bg-slate-100 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold text-4xl">
                                        🥗
                                    </div>
                                @endif
                                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-extrabold text-green-700 shadow-sm">
                                    LKR {{ number_format($product->price, 2) }}
                                </span>
                            </div>

                            <!-- Product Details -->
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-slate-800 text-lg mb-1 group-hover:text-green-600 transition">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
                                        {{ $product->description ?? 'Freshly prepared organic meal with handpicked ingredients.' }}
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-2 border-t border-slate-50 flex items-center gap-2">
                                    <!-- Customize / Build Button -->
                                    <a href="{{ route('build', $product->id) }}" 
                                       class="flex-1 text-center py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                                        Customize 🛠️
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="text-5xl mb-3">🔍</div>
                    <h3 class="text-lg font-bold text-slate-700">No food items found</h3>
                    <p class="text-xs text-slate-400 mt-1">Try searching for something else or clear filters.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>