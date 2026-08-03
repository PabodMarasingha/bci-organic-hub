<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Delivery Dashboard</h1>

        @if (session('message'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('message') }}</div>
        @endif

        @forelse ($deliveries as $delivery)
            <div class="border p-4 rounded mb-3">
                <p class="font-semibold">Order #{{ $delivery->order->id }} — {{ $delivery->order->user->name }}</p>
                <p class="text-sm text-gray-600">Zone: {{ $delivery->order->deliveryZone->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600">Drop-off: {{ $delivery->dropoff_location }}</p>
                <p class="text-sm text-gray-600">Status: {{ $delivery->delivery_status }}</p>

                <div class="mt-2 flex gap-2">
                    @if ($delivery->delivery_status === 'unassigned')
                        <form method="POST" action="{{ route('delivery.updateStatus', $delivery->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="delivery_status" value="picked_up">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded">Picked Up</button>
                        </form>
                    @elseif ($delivery->delivery_status === 'picked_up')
                        <form method="POST" action="{{ route('delivery.updateStatus', $delivery->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="delivery_status" value="delivered">
                            <button class="bg-green-600 text-white px-3 py-1 rounded">Mark Delivered</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500">No deliveries waiting.</p>
        @endforelse
    </div>
</x-app-layout>