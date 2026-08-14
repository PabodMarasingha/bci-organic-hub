<x-app-layout>
    <div class="py-12 bg-slate-950 min-h-screen text-slate-100 font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="text-center mb-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-widest mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> BCI Organic Menu
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Build Your Own Custom Meal</h1>
                <p class="text-slate-400 mt-2 text-sm sm:text-base max-w-lg mx-auto">
                    Select a base dish below to customize with your favorite organic ingredients and toppings.
                </p>
            </div>

            <!-- Product Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($products as $product)
                    <a href="{{ route('build', $product) }}" 
                       class="group relative bg-slate-900/80 backdrop-blur-md border border-slate-800 hover:border-emerald-500/50 rounded-3xl p-6 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/10 flex flex-col justify-between overflow-hidden">
                        
                        <div class="flex items-start gap-4">
                            <!-- Dummy Image with Glow -->
                            <div class="relative flex-shrink-0">
                                <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-20 group-hover:opacity-60 transition duration-300"></div>
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&q=80&w=300" 
                                     alt="{{ $product->name }}" 
                                     class="relative w-20 h-20 rounded-2xl object-cover transform group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-white group-hover:text-emerald-400 transition-colors">
                                    {{ $product->name }}
                                </h2>
                                <p class="text-slate-400 text-xs line-clamp-2 mt-1">
                                    {{ $product->description }}
                                </p>
                            </div>
                        </div>

                        <!-- Price & Action Button Bar -->
                        <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-500 uppercase tracking-wider block font-semibold">Starting From</span>
                                <span class="text-lg font-black text-emerald-400">
                                    LKR {{ number_format($product->price, 2) }}
                                </span>
                            </div>

                            <span class="px-4 py-2 bg-emerald-500/10 border border-emerald-500/30 group-hover:bg-emerald-500 group-hover:text-slate-950 text-emerald-400 text-xs font-bold rounded-xl transition-all duration-300 flex items-center gap-1">
                                Customize
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </div>

                    </a>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>