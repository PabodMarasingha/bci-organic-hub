<x-app-layout>
    <div class="py-8 bg-[#060913] min-h-screen text-slate-100 font-sans" 
         x-data="{ 
            editModalOpen: false, 
            editItem: {}, 
            notificationOpen: true,
            get updateUrl() {
                return '{{ route('admin.ingredients.update', ':id') }}'.replace(':id', this.editItem.id || '');
            }
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Title & Subtitle -->
            <div>
                <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-emerald-400 to-green-400">
                    Manage Ingredients Inventory
                </h2>
                <p class="text-xs text-slate-400 mt-1">Track calories, stock levels, reorder thresholds, and pricing for kitchen materials.</p>
            </div>

            <!-- Success Notification -->
            @if (session('success') || session('message'))
                <div x-show="notificationOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold shadow-lg flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('success') ?? session('message') }}</span>
                    </div>
                    <button @click="notificationOpen = false" class="text-emerald-400 hover:text-emerald-200 text-base font-bold">&times;</button>
                </div>
            @endif

            <!-- Add New Ingredient Form -->
            <div class="p-6 rounded-2xl bg-[#0c121e] border border-slate-800/80 shadow-xl transition-all duration-300 hover:border-slate-700/80">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    Add New Ingredient
                </h3>
                <form method="POST" action="{{ route('admin.ingredients.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Ingredient Name</label>
                        <input name="name" type="text" placeholder="e.g. Avocado Slices" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition duration-200" required>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Type</label>
                        <select name="type" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition duration-200" required>
                            <option value="salad_base">Salad Base</option>
                            <option value="protein">Protein</option>
                            <option value="topping">Topping</option>
                            <option value="fruit">Fruit</option>
                            <option value="juice_base">Juice Base</option>
                            <option value="dressing">Dressing</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Current Stock</label>
                        <input name="quantity" type="number" step="0.01" placeholder="e.g. 25.00" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition duration-200" required>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Unit</label>
                        <select name="unit" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition duration-200" required>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="g">Gram (g)</option>
                            <option value="l">Liter (l)</option>
                            <option value="ml">Milliliter (ml)</option>
                            <option value="pcs">Pieces (pcs)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Low Stock Threshold (Reorder Level)</label>
                        <input name="reorder_level" type="number" step="0.01" placeholder="e.g. 5.00" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition duration-200" required>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Calories (kcal)</label>
                        <input name="calories" type="number" placeholder="e.g. 150" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition duration-200" required>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Unit Price (LKR)</label>
                        <input name="price" type="number" step="0.01" placeholder="e.g. 250.00" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition duration-200" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-lg hover:shadow-emerald-500/20 active:scale-95 transition-all duration-200">
                            Add Ingredient
                        </button>
                    </div>
                </form>
            </div>

            <!-- Existing Ingredients Table -->
            <div class="p-6 rounded-2xl bg-[#0c121e] border border-slate-800/80 shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-slate-200">Current Ingredients Stock</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-[11px] uppercase text-slate-400 font-semibold tracking-wider">
                                <th class="py-3 px-4">Name</th>
                                <th class="py-3 px-4">Type</th>
                                <th class="py-3 px-4">Stock Level</th>
                                <th class="py-3 px-4">Calories</th>
                                <th class="py-3 px-4">Price</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-xs text-slate-300">
                            @forelse ($ingredients ?? [] as $ingredient)
                                @php
                                    $isLow = method_exists($ingredient, 'isLowStock') ? $ingredient->isLowStock() : ($ingredient->quantity <= $ingredient->reorder_level);
                                @endphp
                                <tr class="hover:bg-slate-800/40 transition-colors duration-200">
                                    <td class="py-3.5 px-4 font-semibold text-slate-100">{{ $ingredient->name }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-300 border border-slate-700">
                                            {{ str_replace('_', ' ', $ingredient->type) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono">
                                        <span class="{{ $isLow ? 'text-rose-400 font-bold' : 'text-teal-400 font-bold' }}">
                                            {{ $ingredient->quantity }} {{ $ingredient->unit }}
                                        </span>
                                        @if($isLow)
                                            <span class="ml-1.5 px-1.5 py-0.5 text-[9px] bg-rose-500/10 text-rose-400 border border-rose-500/30 rounded font-bold uppercase">Low Stock</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-400">{{ $ingredient->calories }} kcal</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-200">Rs. {{ number_format($ingredient->price, 2) }}</td>
                                    <td class="py-3.5 px-4">
                                        @if($ingredient->in_stock)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Available</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30">Unavailable</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right space-x-3">
                                        <button 
                                            @click="editItem = { 
                                                id: {{ $ingredient->id }}, 
                                                name: '{{ addslashes($ingredient->name) }}', 
                                                type: '{{ $ingredient->type }}',
                                                quantity: '{{ $ingredient->quantity }}',
                                                unit: '{{ $ingredient->unit }}',
                                                reorder_level: '{{ $ingredient->reorder_level }}',
                                                calories: '{{ $ingredient->calories }}',
                                                price: '{{ $ingredient->price }}',
                                                in_stock: {{ $ingredient->in_stock ? 'true' : 'false' }}
                                            }; editModalOpen = true"
                                            class="text-teal-400 hover:text-teal-300 font-semibold text-xs transition hover:underline">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.ingredients.destroy', $ingredient->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this ingredient?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-400 font-semibold text-xs transition hover:underline">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-slate-500">No ingredients found in inventory.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Edit Ingredient Modal -->
        <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="editModalOpen" @click="editModalOpen = false" class="fixed inset-0 bg-black/80 backdrop-blur-md"></div>
            <div x-show="editModalOpen" class="bg-[#0c121e] border border-slate-800 rounded-2xl w-full max-w-lg p-6 shadow-2xl relative z-10 space-y-4">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-slate-100">Edit Ingredient</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-white text-xl">&times;</button>
                </div>
                <form :action="updateUrl" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Name</label>
                        <input type="text" name="name" x-model="editItem.name" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Type</label>
                            <select name="type" x-model="editItem.type" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                                <option value="salad_base">Salad Base</option>
                                <option value="protein">Protein</option>
                                <option value="topping">Topping</option>
                                <option value="fruit">Fruit</option>
                                <option value="juice_base">Juice Base</option>
                                <option value="dressing">Dressing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Unit</label>
                            <select name="unit" x-model="editItem.unit" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                                <option value="kg">kg</option>
                                <option value="g">g</option>
                                <option value="l">l</option>
                                <option value="ml">ml</option>
                                <option value="pcs">pcs</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Quantity</label>
                            <input type="number" step="0.01" name="quantity" x-model="editItem.quantity" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Reorder Threshold</label>
                            <input type="number" step="0.01" name="reorder_level" x-model="editItem.reorder_level" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Calories</label>
                            <input type="number" name="calories" x-model="editItem.calories" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Price (LKR)</label>
                            <input type="number" step="0.01" name="price" x-model="editItem.price" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pt-2">
                        <!-- Checkbox Unchecked විට 0 යැවීමට Hidden input එකක් යොදා ඇත -->
                        <input type="hidden" name="in_stock" value="0">
                        <input type="checkbox" name="in_stock" value="1" x-model="editItem.in_stock" class="rounded bg-[#121a29] border-slate-700 text-teal-500 focus:ring-teal-500">
                        <label class="text-xs text-slate-300">Available in Stock</label>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 text-slate-950 font-bold text-xs uppercase">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>