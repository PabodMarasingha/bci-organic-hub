<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delivery Portal - BCI Organic Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js (Tabs switch කිරීමට සහ UI state පාලනයට) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col" x-data="{ activeTab: 'active' }">

    <!-- Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🌱</span>
                    <span class="font-bold text-xl text-emerald-800 tracking-tight">BCI Organic Hub</span>
                </div>
                <nav class="hidden md:flex space-x-4">
                    <a href="/dashboard" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-emerald-700">Dashboard</a>
                    <a href="{{ route('delivery.index') }}" class="px-3 py-2 text-sm font-semibold text-emerald-700 border-b-2 border-emerald-600">Delivery Portal</a>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Driver Portal: Active
                </span>
                <div class="text-sm text-slate-600 font-medium">
                    {{ Auth::user()->name ?? 'Driver' }} <span class="text-xs uppercase bg-slate-100 px-2 py-0.5 rounded border border-slate-200">DELIVERY</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
        
        <!-- Notification Message -->
        @if(session('message'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>{{ session('message') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">&times;</button>
            </div>
        @endif

        <!-- Title & Quick Stats -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                🚚 Delivery Management
            </h1>
            <p class="text-sm text-slate-500 mt-1">Manage orders, track routes, and view earnings in real-time.</p>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ready for Pickup</p>
                    <h3 class="text-3xl font-extrabold text-amber-500 mt-1">{{ $readyCount }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 text-xl">
                    📦
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">On The Way</p>
                    <h3 class="text-3xl font-extrabold text-blue-600 mt-1">{{ $outForDeliveryCount }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 text-xl">
                    🛵
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Delivered Today</p>
                    <h3 class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $deliveredTodayCount }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 text-xl">
                    🎉
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Today's Earnings</p>
                    <h3 class="text-3xl font-extrabold text-purple-600 mt-1">LKR {{ number_format($todayEarnings ?? 0, 2) }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 text-xl">
                    💵
                </div>
            </div>
        </div>

        <!-- Interactive Tabs Section -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Navigation Header -->
            <div class="border-b border-slate-200 px-6 pt-4 flex flex-wrap items-center justify-between gap-4">
                <div class="flex gap-6">
                    <button @click="activeTab = 'active'" :class="activeTab === 'active' ? 'border-emerald-600 text-emerald-700 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-4 border-b-2 text-sm transition-all flex items-center gap-2">
                        <i class="fa-solid fa-list-check"></i> Active Orders 
                        <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full">{{ $orders->count() }}</span>
                    </button>
                    <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'border-emerald-600 text-emerald-700 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-4 border-b-2 text-sm transition-all flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left"></i> Delivery History
                    </button>
                    <button @click="activeTab = 'earnings'" :class="activeTab === 'earnings' ? 'border-emerald-600 text-emerald-700 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-4 border-b-2 text-sm transition-all flex items-center gap-2">
                        <i class="fa-solid fa-wallet"></i> Earnings & Payouts
                    </button>
                </div>
                <a href="{{ route('delivery.index') }}" class="mb-3 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg flex items-center gap-1.5 transition">
                    🔄 Refresh Queue
                </a>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                
                <!-- Tab 1: Active Deliveries -->
                <div x-show="activeTab === 'active'" class="space-y-4">
                    <h2 class="text-lg font-bold text-slate-800 mb-2">Orders Ready & In-Transit</h2>

                    @forelse($orders as $order)
                        <div class="border rounded-xl p-5 transition-all shadow-sm flex flex-col md:flex-row justify-between gap-4 {{ $order->status === 'out_for_delivery' ? 'border-blue-200 bg-blue-50/30' : 'border-slate-200 hover:border-emerald-500 bg-white' }}">
                            <div class="space-y-2 flex-1">
                                <div class="flex items-center gap-3">
                                    @if($order->status === 'ready')
                                        <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-md">READY FOR PICKUP</span>
                                    @else
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-md">ON THE WAY</span>
                                    @endif
                                    <span class="text-sm font-bold text-slate-800">Order #ORD-{{ $order->id }}</span>
                                    <span class="text-xs text-slate-400">{{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm pt-1">
                                    <div>
                                        <p class="text-xs text-slate-400">Customer Name</p>
                                        <p class="font-semibold text-slate-700">{{ $order->user->name ?? $order->customer_name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-400">Delivery Address</p>
                                        <p class="font-semibold text-slate-700">{{ $order->delivery_address ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-400">Payment Status</p>
                                        <p class="font-semibold {{ ($order->payment_method ?? 'COD') == 'COD' ? 'text-amber-600' : 'text-emerald-600' }}">
                                            {{ $order->payment_method ?? 'COD' }} - LKR {{ number_format($order->total_amount ?? 0, 2) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-400">Items</p>
                                        <p class="font-semibold text-slate-700">
                                            @if($order->items && $order->items->count())
                                                {{ $order->items->pluck('productItem.name')->implode(', ') }}
                                            @else
                                                Standard Package
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row md:flex-col justify-center gap-2 min-w-[160px]">
                                @if($order->customer_phone || ($order->user && $order->user->phone))
                                    <a href="tel:{{ $order->customer_phone ?? $order->user->phone }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg text-center transition">
                                        📞 Call Customer
                                    </a>
                                @endif

                                @if($order->delivery_address)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->delivery_address) }}" target="_blank" class="px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg text-center transition">
                                        🗺️ View Map Route
                                    </a>
                                @endif

                                @if($order->status === 'ready')
                                    <form action="{{ route('delivery.update-status', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="out_for_delivery">
                                        <button type="submit" class="w-full px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg text-center transition shadow-sm">
                                            🚀 Accept & Start Delivery
                                        </button>
                                    </form>
                                @elseif($order->status === 'out_for_delivery')
                                    <form action="{{ route('delivery.update-status', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="delivered">
                                        <button type="submit" class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg text-center transition shadow-sm">
                                            ✅ Mark as Delivered
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <span class="text-4xl">🛵</span>
                            <p class="text-slate-500 mt-2 font-medium">No active deliveries right now!</p>
                        </div>
                    @endforelse

                </div>

                <!-- Tab 2: Delivery History -->
                <div x-show="activeTab === 'history'" class="space-y-4">
                    <h2 class="text-lg font-bold text-slate-800 mb-2">Completed Deliveries</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-slate-700 uppercase text-xs">
                                <tr>
                                    <th class="p-3">Order ID</th>
                                    <th class="p-3">Customer</th>
                                    <th class="p-3">Address</th>
                                    <th class="p-3">Delivered Time</th>
                                    <th class="p-3">Payment</th>
                                    <th class="p-3 text-right">Fee Earned</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($historyOrders as $hOrder)
                                    <tr class="hover:bg-slate-50">
                                        <td class="p-3 font-semibold text-slate-800">#ORD-{{ $hOrder->id }}</td>
                                        <td class="p-3">{{ $hOrder->user->name ?? $hOrder->customer_name ?? 'N/A' }}</td>
                                        <td class="p-3">{{ $hOrder->delivery_address ?? 'N/A' }}</td>
                                        <td class="p-3">{{ $hOrder->updated_at->format('h:i A') }}</td>
                                        <td class="p-3">
                                            <span class="text-xs {{ ($hOrder->payment_method ?? 'COD') == 'COD' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }} font-bold px-2 py-0.5 rounded">
                                                {{ $hOrder->payment_method ?? 'COD' }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-right font-bold text-slate-800">LKR {{ number_format($hOrder->delivery_fee ?? 350, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-slate-400">No completed deliveries yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 3: Earnings & Payouts -->
                <div x-show="activeTab === 'earnings'" class="space-y-6">
                    <h2 class="text-lg font-bold text-slate-800">Earnings Overview</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 border rounded-xl bg-slate-50">
                            <p class="text-xs text-slate-400 font-bold uppercase">Today's Total</p>
                            <p class="text-2xl font-black text-slate-800 mt-1">LKR {{ number_format($todayEarnings ?? 0, 2) }}</p>
                        </div>
                        <div class="p-4 border rounded-xl bg-slate-50">
                            <p class="text-xs text-slate-400 font-bold uppercase">Pending Deliveries</p>
                            <p class="text-2xl font-black text-amber-600 mt-1">{{ $orders->count() }} Orders</p>
                        </div>
                        <div class="p-4 border rounded-xl bg-slate-50">
                            <p class="text-xs text-slate-400 font-bold uppercase">Completed Today</p>
                            <p class="text-2xl font-black text-emerald-600 mt-1">{{ $deliveredTodayCount }} Orders</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} BCI Organic Hub - Delivery Management Portal. All rights reserved.
    </footer>

</body>
</html>