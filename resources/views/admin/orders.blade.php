<x-app-layout>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .tab-btn-active {
            color: #16a34a !important;
            border-bottom: 2px solid #16a34a !important;
            background-color: rgba(22, 163, 74, 0.05);
        }
    </style>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Top Header & Live Analytics Banner -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-100 animate-fade-in">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Admin Control Center</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage orders, system accounts, and live platform operations.</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center gap-3">
                    <div class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 text-sm font-semibold flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Total Revenue: Rs. {{ number_format($totalSales, 2) }}
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-fade-in" style="animation-delay: 0.1s;">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                    <p class="text-xs font-semibold uppercase text-slate-400">Total Orders</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalOrders }}</h3>
                    <span class="text-xs text-slate-500 mt-1 inline-block">All time system orders</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                    <p class="text-xs font-semibold uppercase text-amber-500">Kitchen Active</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-1">{{ $kitchenOrders }}</h3>
                    <span class="text-xs text-amber-600 mt-1 inline-block">Preparing in kitchen</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                    <p class="text-xs font-semibold uppercase text-blue-500">Out For Delivery</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-1">{{ $deliveryOrders }}</h3>
                    <span class="text-xs text-blue-600 mt-1 inline-block">On the way</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                    <p class="text-xs font-semibold uppercase text-emerald-600">Active System Users</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-1">{{ array_sum($usersCount) }}</h3>
                    <span class="text-xs text-emerald-600 mt-1 inline-block">Customers & Staff</span>
                </div>
            </div>

            <!-- Dynamic Tab Navigation Bar -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden animate-fade-in" style="animation-delay: 0.2s;">
                <div class="flex border-b border-slate-200 bg-slate-50/50 overflow-x-auto">
                    <button onclick="switchTab('tab-orders')" id="btn-orders" class="tab-btn px-6 py-4 font-semibold text-sm text-slate-600 hover:text-slate-900 border-b-2 border-transparent transition-all flex items-center gap-2 tab-btn-active whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        All Orders Management
                    </button>

                    <button onclick="switchTab('tab-users')" id="btn-users" class="tab-btn px-6 py-4 font-semibold text-sm text-slate-600 hover:text-slate-900 border-b-2 border-transparent transition-all flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Account & Staff Roles ({{ array_sum($usersCount) }})
                    </button>

                    <button onclick="switchTab('tab-ingredients')" id="btn-ingredients" class="tab-btn px-6 py-4 font-semibold text-sm text-slate-600 hover:text-slate-900 border-b-2 border-transparent transition-all flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Ingredients Inventory
                    </button>
                </div>

                <!-- TAB 1: ALL ORDERS -->
                <div id="tab-orders" class="tab-content p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-slate-800">System Orders Overview</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs uppercase text-slate-400 border-b border-slate-200 bg-slate-50">
                                    <th class="p-3">Order ID</th>
                                    <th class="p-3">Customer</th>
                                    <th class="p-3">Total Amount</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="p-3 font-semibold text-slate-900">#{{ $order->id }}</td>
                                        <td class="p-3 font-medium">{{ $order->user->name ?? 'Guest/Customer' }}</td>
                                        <td class="p-3 font-bold text-emerald-600">
                                            Rs. {{ number_format($order->total_price ?? $order->total ?? $order->price ?? 0, 2) }}
                                        </td>
                                        <td class="p-3">
                                            @php
                                                $badgeClasses = [
                                                    'pending' => 'bg-amber-100 text-amber-800',
                                                    'preparing' => 'bg-orange-100 text-orange-800',
                                                    'out_for_delivery' => 'bg-blue-100 text-blue-800',
                                                    'completed' => 'bg-emerald-100 text-emerald-800',
                                                    'cancelled' => 'bg-rose-100 text-rose-800',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $badgeClasses[$order->status] ?? 'bg-slate-100 text-slate-800' }}">
                                                {{ str_replace('_', ' ', $order->status) }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-slate-500 text-xs">{{ $order->created_at->format('M d, Y - h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-slate-400">No orders found in system yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: SYSTEM ACCOUNTS & ROLES CONNECTED -->
                <div id="tab-users" class="tab-content p-6 hidden">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="text-xs text-slate-500 block">Customers</span>
                            <span class="text-lg font-bold text-slate-800">{{ $usersCount['customers'] }}</span>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                            <span class="text-xs text-amber-700 block">Kitchen Staff</span>
                            <span class="text-lg font-bold text-amber-800">{{ $usersCount['kitchen'] }}</span>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl border border-blue-200">
                            <span class="text-xs text-blue-700 block">Delivery Staff</span>
                            <span class="text-lg font-bold text-blue-800">{{ $usersCount['delivery'] }}</span>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                            <span class="text-xs text-emerald-700 block">System Admins</span>
                            <span class="text-lg font-bold text-emerald-800">{{ $usersCount['admin'] }}</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs uppercase text-slate-400 border-b border-slate-200 bg-slate-50">
                                    <th class="p-3">User Name</th>
                                    <th class="p-3">Email</th>
                                    <th class="p-3">Assigned Role</th>
                                    <th class="p-3">Joined Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-3 font-semibold text-slate-900">{{ $user->name }}</td>
                                        <td class="p-3 text-slate-600">{{ $user->email }}</td>
                                        <td class="p-3">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase
                                                @if($user->role == 'admin') bg-purple-100 text-purple-800
                                                @elseif($user->role == 'kitchen') bg-amber-100 text-amber-800
                                                @elseif($user->role == 'delivery') bg-blue-100 text-blue-800
                                                @else bg-slate-100 text-slate-800 @endif">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-slate-500 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: INGREDIENTS INVENTORY -->
                <div id="tab-ingredients" class="tab-content p-6 hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Ingredients Management</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs uppercase text-slate-400 border-b border-slate-200 bg-slate-50">
                                    <th class="p-3">Ingredient Name</th>
                                    <th class="p-3">Stock Quantity</th>
                                    <th class="p-3">Unit Price</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                                @forelse($ingredients as $ingredient)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-3 font-semibold text-slate-900">{{ $ingredient->name }}</td>
                                        <td class="p-3 font-medium text-slate-700">{{ $ingredient->quantity ?? $ingredient->stock ?? 0 }}</td>
                                        <td class="p-3 font-bold text-emerald-600">
                                            Rs. {{ number_format($ingredient->price ?? $ingredient->unit_price ?? 0, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-6 text-center text-slate-400">No ingredients registered yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JavaScript for Tab Switching with Animation -->
    <script>
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Deactivate all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('tab-btn-active');
            });

            // Show current active tab
            const targetTab = document.getElementById(tabId);
            if (targetTab) {
                targetTab.classList.remove('hidden');
                targetTab.classList.add('animate-fade-in');
            }

            // Highlight current button
            const activeBtn = document.getElementById('btn-' + tabId.replace('tab-', ''));
            if (activeBtn) {
                activeBtn.classList.add('tab-btn-active');
            }
        }
    </script>
</x-app-layout>