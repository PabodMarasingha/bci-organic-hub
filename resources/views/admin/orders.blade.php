<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">All Orders</h1>
        <p class="mb-4 text-lg">Total Sales: <strong>Rs. {{ number_format($totalSales, 2) }}</strong></p>

        @foreach ($orders as $order)
            <div class="border-b py-2">
                Order #{{ $order->id }} — {{ $order->user->name }} — Rs. {{ number_format($order->total_amount, 2) }} — {{ $order->status }}
            </div>
        @endforeach
    </div>
</x-app-layout>