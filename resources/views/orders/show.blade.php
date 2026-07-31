<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 text-center">
        @if (session('message'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('message') }}</div>
        @endif
        <h1 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h1>
        <p>Status: {{ $order->status }}</p>
        <p>Total: Rs. {{ number_format($order->total_amount, 2) }}</p>
        <p class="mt-4"><a href="{{ route('menu') }}" class="text-green-600">Order more</a></p>
    </div>
</x-app-layout>