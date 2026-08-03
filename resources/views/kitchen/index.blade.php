<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Kitchen Dashboard</h1>

        @if (session('message'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('message') }}</div>
        @endif

        <h2 class="font-semibold mb-2">Orders</h2>
        @forelse ($orders as $order)
            <div class="border p-4 rounded mb-3">
                <p class="font-semibold">Order #{{ $order->id }} — {{ $order->user->name }} — {{ $order->status }}</p>
                @foreach ($order->items as $item)
                    <p class="text-sm text-gray-600">
                        {{ $item->quantity }}x {{ $item->item_name }}
                        @if (!empty($item->customizations))
                            ({{ implode(', ', $item->customizations) }})
                        @endif
                        — Rs. {{ number_format($item->unit_price, 2) }}
                    </p>
                @endforeach

                <div class="mt-2 flex gap-2">
                    @if ($order->status === 'pending')
                        <form method="POST" action="{{ route('kitchen.updateStatus', $order->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="preparing">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded">Start Preparing</button>
                        </form>
                    @endif
                    @if ($order->status === 'preparing')
                        <form method="POST" action="{{ route('kitchen.updateStatus', $order->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="ready">
                            <button class="bg-blue-600 text-white px-3 py-1 rounded">Mark Ready</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500">No pending orders.</p>
        @endforelse

        <h2 class="font-semibold mt-8 mb-2">Ingredient Stock</h2>
        @foreach ($ingredients as $ingredient)
            <div class="flex justify-between items-center border-b py-2">
                <span>{{ $ingredient->name }} — {{ $ingredient->in_stock ? 'In Stock' : 'OUT OF STOCK' }}</span>
                <form method="POST" action="{{ route('kitchen.toggleStock', $ingredient->id) }}">
                    @csrf
                    <button class="text-sm underline">{{ $ingredient->in_stock ? 'Mark Out of Stock' : 'Mark In Stock' }}</button>
                </form>
            </div>
        @endforeach
    </div>
</x-app-layout>