<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('BCI Organic Hub - Order Customization') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <form id="orderForm" onsubmit="submitOrder(event)">
                    @csrf
                    
                    <!-- Item Selection -->
                    <h3 class="text-lg font-bold mb-4">1. Select Item & Ingredients</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Category</label>
                            <select id="itemName" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="Custom Organic Salad">Custom Organic Salad</option>
                                <option value="Fresh Organic Juice">Fresh Organic Juice</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Quantity</label>
                            <input type="number" id="itemQuantity" value="1" min="1" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <!-- Customization Options -->
                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Custom Add-ons / Ingredients</label>
                        <div class="flex flex-wrap gap-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="customizations" value="Extra Greens" class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-600">Extra Greens (+Rs. 150)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="customizations" value="Organic Dressing" class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-600">Organic Dressing (+Rs. 100)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="customizations" value="Chia Seeds" class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-600">Chia Seeds (+Rs. 80)</span>
                            </label>
                        </div>
                    </div>

                    <hr class="my-6">

                    <!-- Delivery Details -->
                    <h3 class="text-lg font-bold mb-4">2. Delivery & Payment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Campus Zone</label>
                            <select id="deliveryZone" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="Main Campus Building">Main Campus Building</option>
                                <option value="IT Department Wing">IT Department Wing</option>
                                <option value="Library Complex">Library Complex</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Dropoff Location / Room No.</label>
                            <input type="text" id="dropoffLocation" placeholder="e.g., Lab 201" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Payment Method</label>
                            <select id="paymentMethod" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="digital_wallet">Campus Digital Wallet</option>
                                <option value="card">Credit / Debit Card</option>
                                <option value="cash_on_delivery">Cash on Delivery</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Special Instructions</label>
                            <input type="text" id="specialInstructions" placeholder="e.g., Less dressing" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <!-- Explicitly Styled Submit Button -->
                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" style="background-color: #16a34a !important; color: #ffffff !important; padding: 12px 24px !important; font-weight: bold !important; border-radius: 8px !important; border: none !important; cursor: pointer !important; font-size: 16px !important;">
                            Place Order Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        async function submitOrder(e) {
            e.preventDefault();

            const selectedCustomizations = Array.from(document.querySelectorAll('input[name="customizations"]:checked'))
                .map(cb => cb.value);

            const payload = {
                items: [{
                    item_name: document.getElementById('itemName').value,
                    customizations: selectedCustomizations,
                    quantity: parseInt(document.getElementById('itemQuantity').value),
                    unit_price: 650.00
                }],
                total_amount: 650.00 * parseInt(document.getElementById('itemQuantity').value),
                delivery_zone: document.getElementById('deliveryZone').value,
                dropoff_location: document.getElementById('dropoffLocation').value,
                payment_method: document.getElementById('paymentMethod').value,
                special_instructions: document.getElementById('specialInstructions').value
            };

            const response = await fetch('/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();
            if (response.ok) {
                alert('Order Placed Successfully! Order ID: ' + result.order_id);
            } else {
                alert('Failed to place order: ' + (result.message || 'Check validation fields.'));
            }
        }
    </script>
</x-app-layout>