<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Checkout</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-6">
            <h2 class="font-semibold mb-2">Order Summary</h2>
            @foreach ($cart as $item)
                <p class="text-sm text-gray-600">
                    {{ $item['item_name'] }} — Rs. {{ number_format($item['unit_price'], 2) }}
                </p>
            @endforeach
            <p class="font-semibold mt-2">
                Total: Rs. {{ number_format(collect($cart)->sum(fn($i) => $i['unit_price'] * $i['quantity']), 2) }}
            </p>
        </div>

        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <label class="block font-medium text-sm text-gray-700">Delivery Zone</label>
            <select name="delivery_zone_id" class="w-full border-gray-300 rounded-md shadow-sm mb-4" required>
                @foreach ($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                @endforeach
            </select>

            <label class="block font-medium text-sm text-gray-700">Dropoff Location / Room No.</label>
            <input type="text" name="dropoff_location" placeholder="e.g., Lab 201" class="w-full border-gray-300 rounded-md shadow-sm mb-4" required>

            <label class="block font-medium text-sm text-gray-700">Payment Method</label>
            <select name="payment_method" class="w-full border-gray-300 rounded-md shadow-sm mb-4" required>
                <option value="digital_wallet">Campus Digital Wallet</option>
                <option value="card">Credit / Debit Card</option>
                <option value="cash_on_delivery">Cash on Delivery</option>
            </select>

            <label class="block font-medium text-sm text-gray-700">Special Instructions</label>
            <input type="text" name="special_instructions" placeholder="e.g., Less dressing" class="w-full border-gray-300 rounded-md shadow-sm mb-4">

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded font-semibold">
                Place Order Now
            </button>
        </form>
    </div>
</x-app-layout>