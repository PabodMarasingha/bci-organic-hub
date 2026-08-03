<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Your Food Cart') }}
            </h2>
            <a href="{{ route('menu') }}" class="text-sm font-semibold text-green-600 hover:text-green-700">
                &larr; Back to Menu
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('message'))
                <div class="p-4 bg-green-100 border border-green-200 text-green-800 rounded-2xl shadow-sm text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-red-100 border border-red-200 text-red-800 rounded-2xl shadow-sm text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(count($cart) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Cart Items List (2 Columns) -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                                <h3 class="font-bold text-slate-800 text-lg">Cart Items ({{ count($cart) }})</h3>
                            </div>

                            <div class="divide-y divide-slate-100">
                                @php $subtotal = 0; @endphp
                                @foreach($cart as $index => $item)
                                    @php 
                                        $itemTotal = $item['unit_price'] * $item['quantity'];
                                        $subtotal += $itemTotal;
                                    @endphp
                                    <div class="p-6 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                        
                                        <!-- Item Details -->
                                        <div class="space-y-1">
                                            <h4 class="font-bold text-slate-800 text-base">{{ $item['item_name'] }}</h4>
                                            <p class="text-xs text-slate-500">Unit Price: LKR {{ number_format($item['unit_price'], 2) }}</p>
                                            
                                            @if(!empty($item['customizations']))
                                                <div class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded-md inline-block">
                                                    Customized
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Quantity Update Form & Subtotal -->
                                        <div class="flex items-center justify-between sm:justify-end gap-6">
                                            
                                            <!-- Update Quantity -->
                                            <form action="{{ route('cart.update', $index) }}" method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10" 
                                                    class="w-16 px-2 py-1 text-center text-sm border border-slate-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                                                <button type="submit" class="text-xs font-semibold px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                                                    Update
                                                </button>
                                            </form>

                                            <!-- Price & Remove -->
                                            <div class="text-right">
                                                <span class="font-bold text-slate-800 block text-base">
                                                    LKR {{ number_format($itemTotal, 2) }}
                                                </span>

                                                <form action="{{ route('cart.remove', $index) }}" method="POST" class="inline-block mt-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 transition">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>

                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar (1 Column) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6 sticky top-6">
                            <h3 class="font-bold text-slate-800 text-lg border-b border-slate-100 pb-4">Order Summary</h3>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between text-slate-600">
                                    <span>Subtotal</span>
                                    <span class="font-semibold text-slate-800">LKR {{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-slate-600">
                                    <span>Estimated Delivery Fee</span>
                                    <span class="text-xs text-slate-400 italic">Calculated at Checkout</span>
                                </div>
                                <div class="border-t border-slate-100 pt-3 flex justify-between font-bold text-lg text-slate-800">
                                    <span>Total</span>
                                    <span class="text-green-600">LKR {{ number_format($subtotal, 2) }}</span>
                                </div>
                            </div>

                            <a href="{{ route('orders.create') }}" class="w-full block text-center py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow transition duration-150">
                                Proceed to Checkout &rarr;
                            </a>
                        </div>
                    </div>

                </div>
            @else
                <!-- Empty Cart State -->
                <div class="bg-white p-12 rounded-2xl border border-slate-100 shadow-sm text-center space-y-4 max-w-lg mx-auto">
                    <div class="p-4 bg-green-50 text-green-600 rounded-full w-20 h-20 mx-auto flex items-center justify-center">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Your Cart is Empty</h3>
                    <p class="text-sm text-slate-500">Looks like you haven't added any food items to your cart yet.</p>
                    <a href="{{ route('menu') }}" class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl shadow transition">
                        Explore Menu Now
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>