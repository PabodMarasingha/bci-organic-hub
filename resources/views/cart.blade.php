<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Your Cart</h1>

        @forelse ($cart as $index => $item)
            <div class="border p-4 rounded mb-2 flex justify-between items-center">
                <div>
                    <p class="font-semibold">{{ $item['item_name'] }}</p>
                    @if (!empty($item['customizations']))
                        <p class="text-sm text-gray-500">{{ implode(', ', $item['customizations']) }}</p>
                    @endif
                    <p class="text-sm text-gray-500">Rs. {{ number_format($item['unit_price'], 2) }}</p>
                </div>
                <form method="POST" action="{{ route('cart.remove', $index) }}">
                    @csrf
                    <button class="text-red-600">Remove</button>
                </form>
            </div>
        @empty
            <p>Your cart is empty. <a href="{{ route('menu') }}" class="text-green-600">Browse the menu</a></p>
        @endforelse

        @if (count($cart) > 0)
            <div class="mt-6 border-t pt-4 flex justify-between items-center">
                <p class="text-lg font-semibold">
                    Total: Rs. {{ number_format(collect($cart)->sum(fn($i) => $i['unit_price'] * $i['quantity']), 2) }}
                </p>
                <a href="{{ route('orders.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">
                    Proceed to Checkout
                </a>
            </div>
        @endif
    </div>
</x-app-layout>