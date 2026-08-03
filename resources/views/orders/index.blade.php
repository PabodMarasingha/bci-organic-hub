<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">My Orders</h1>

        @forelse ($orders as $order)
            <a href="{{ route('orders.show', $order->id) }}" class="block border p-4 rounded mb-3 hover:bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">Order #{{ $order->id }}</p>
                        <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y — h:i A') }}</p>
                        @if ($order->deliveryZone)
                            <p class="text-sm text-gray-600">Zone: {{ $order->deliveryZone->name }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">Rs. {{ number_format($order->total_amount, 2) }}</p>
                        <span class="inline-block px-2 py-1 text-xs rounded
                            @if ($order->status === 'delivered') bg-green-100 text-green-800
                            @elseif ($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <p>You haven't placed any orders yet. <a href="{{ route('menu') }}" class="text-green-600">Browse the menu</a></p>
        @endforelse
    </div>
</x-app-layout>