<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Manage Ingredients</h1>

        @if (session('message'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('message') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.ingredients.store') }}" class="mb-6 border p-4 rounded">
            @csrf
            <input name="name" placeholder="Name" class="border p-2 rounded w-full mb-2" required>
            <select name="type" class="border p-2 rounded w-full mb-2" required>
                <option value="salad_base">Salad Base</option>
                <option value="topping">Topping</option>
                <option value="juice_base">Juice Base</option>
                <option value="fruit">Fruit</option>
            </select>
            <input name="price" type="number" step="0.01" placeholder="Price" class="border p-2 rounded w-full mb-2" required>
            <input name="calories" type="number" placeholder="Calories" class="border p-2 rounded w-full mb-2" required>
            <button class="bg-green-600 text-white px-4 py-2 rounded">Add Ingredient</button>
        </form>

        @foreach ($ingredients as $ingredient)
            <div class="flex justify-between items-center border-b py-2">
                <span>{{ $ingredient->name }} ({{ $ingredient->type }}) — Rs. {{ $ingredient->price }} — {{ $ingredient->in_stock ? 'In Stock' : 'Out of Stock' }}</span>
                <form method="POST" action="{{ route('admin.ingredients.destroy', $ingredient) }}">
                    @csrf @method('DELETE')
                    <button class="text-red-600">Delete</button>
                </form>
            </div>
        @endforeach
    </div>
</x-app-layout>