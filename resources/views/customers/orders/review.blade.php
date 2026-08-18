<x-app-layout>
    <div class="max-w-2xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold text-white mb-6">Rate & Review Order #{{ $order->id }}</h1>

        <form action="{{ route('orders.review.store', $order->id) }}" method="POST" class="bg-gray-800 p-6 rounded-lg space-y-6">
            @csrf

            <!-- Food Rating -->
            <div>
                <label class="block text-gray-300 font-medium mb-2">Food Quality Rating (1 to 5)</label>
                <select name="food_rating" required class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-2">
                    <option value="5">⭐⭐⭐⭐⭐ (5/5) - Excellent</option>
                    <option value="4">⭐⭐⭐⭐ (4/5) - Good</option>
                    <option value="3">⭐⭐⭐ (3/3) - Average</option>
                    <option value="2">⭐⭐ (2/5) - Poor</option>
                    <option value="1">⭐ (1/5) - Very Bad</option>
                </select>
            </div>

            <!-- Delivery Rating -->
            <div>
                <label class="block text-gray-300 font-medium mb-2">Delivery Service Rating (1 to 5)</label>
                <select name="delivery_rating" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-2">
                    <option value="">Select Delivery Rating</option>
                    <option value="5">⭐⭐⭐⭐⭐ (5/5) - Fast & Friendly</option>
                    <option value="4">⭐⭐⭐⭐ (4/5) - Good</option>
                    <option value="3">⭐⭐⭐ (3/5) - On Time</option>
                    <option value="2">⭐⭐ (2/5) - Delayed</option>
                    <option value="1">⭐ (1/5) - Very Bad</option>
                </select>
            </div>

            <!-- Comments -->
            <div>
                <label class="block text-gray-300 font-medium mb-2">Additional Comments</label>
                <textarea name="comment" rows="4" placeholder="Write your feedback here..." class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-2"></textarea>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-md transition duration-200">
                Submit Review
            </button>
        </form>
    </div>
</x-app-layout>