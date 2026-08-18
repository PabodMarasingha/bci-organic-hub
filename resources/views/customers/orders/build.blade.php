<x-app-layout>
    <div class="min-h-screen bg-[#0b1329] text-slate-100 py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-6">
            <h1 class="text-3xl font-black text-white">Customize {{ $product->name }}</h1>
            <p class="text-slate-400">{{ $product->description }}</p>

            <div class="bg-slate-900 border border-slate-800 p-6 rounded-3xl">
                <h3 class="text-xl font-bold mb-4 text-cyan-400">Select Ingredients</h3>
                
                @foreach($customizationOptions as $type => $ingredients)
                    <div class="mb-6">
                        <h4 class="text-md font-bold text-slate-300 border-b border-slate-800 pb-2 mb-3">{{ $type }}</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($ingredients as $ingredient)
                                <label class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-xl border border-slate-700 cursor-pointer hover:border-cyan-500">
                                    <input type="checkbox" name="ingredients[]" value="{{ $ingredient->id }}" class="rounded bg-slate-900 border-slate-700 text-cyan-500 focus:ring-cyan-500">
                                    <span class="text-sm font-semibold text-slate-200">{{ $ingredient->name }}</span>
                                    <span class="text-xs text-cyan-400 ml-auto">+Rs. {{ number_format($ingredient->price, 2) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>