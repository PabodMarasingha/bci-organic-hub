<x-app-layout>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Style & Alpine Cloak -->
    <style>
        [x-cloak] { display: none !important; }
        @keyframes flowBeam {
            0% { stroke-dashoffset: 1000; }
            100% { stroke-dashoffset: 0; }
        }
        .animated-path {
            stroke-dasharray: 200 800;
            animation: flowBeam 4s linear infinite;
        }
    </style>

    <div class="py-8 bg-[#05070c] dark:bg-[#05070c] min-h-screen text-slate-100 selection:bg-emerald-500 selection:text-white transition-colors duration-300" x-data="{ activeTab: 'overview' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('message') || session('success'))
                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold">
                    {{ session('message') ?? session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Top Header & Admin Tools Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#0b0f19]/90 backdrop-blur-xl p-6 rounded-2xl border border-slate-800/80 shadow-xl">
                <div>
                    <h2 class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-green-500">
                        Admin Executive Control Center
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Welcome back, <span class="text-emerald-400 font-semibold">{{ Auth::user()->name ?? 'Administrator' }}</span>! Real-time financial & kitchen operations panel.
                    </p>
                </div>

                <!-- Navigation Tabs & Actions -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex bg-[#141a26] p-1 rounded-xl border border-slate-800">
                        <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'text-slate-400 hover:text-white'" class="px-3 py-1.5 rounded-lg text-xs font-bold border border-transparent transition">
                            Overview
                        </button>
                        <button @click="activeTab = 'finance'" :class="activeTab === 'finance' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'text-slate-400 hover:text-white'" class="px-3 py-1.5 rounded-lg text-xs font-bold border border-transparent transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Money Management
                        </button>
                    </div>

                    <button id="theme-toggle" class="p-2.5 rounded-xl bg-[#141a26] border border-slate-700/70 text-slate-300 hover:text-emerald-400 transition flex items-center gap-2 text-xs font-semibold">
                        <svg id="theme-toggle-dark-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Financial & General KPI Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Gross Revenue -->
                <div class="p-5 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Total Revenue</p>
                        <h3 class="text-2xl font-extrabold text-slate-100 mt-1">Rs. {{ number_format($totalRevenue ?? 0, 2) }}</h3>
                        <p class="text-[10px] text-emerald-400 mt-1 flex items-center gap-1">
                            <span>↑ 14.2%</span> <span class="text-slate-500">vs last month</span>
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Estimated Profit -->
                <div class="p-5 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Net Profit (Est.)</p>
                        <h3 class="text-2xl font-extrabold text-teal-400 mt-1">Rs. {{ number_format($netProfit ?? 0, 2) }}</h3>
                        <p class="text-[10px] text-teal-400 mt-1">33% Margin Rate</p>
                    </div>
                    <div class="p-3 rounded-xl bg-teal-500/10 text-teal-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>

                <!-- Active Orders -->
                <div class="p-5 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Pending Orders</p>
                        <h3 class="text-2xl font-extrabold text-amber-400 mt-1">{{ $pendingOrders ?? 0 }}</h3>
                        <p class="text-[10px] text-amber-400 mt-1">Requires Kitchen Action</p>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-500/10 text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div class="p-5 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Total Ingredients</p>
                        <h3 class="text-2xl font-extrabold text-rose-400 mt-1">{{ $lowStockCount ?? 0 }} Items</h3>
                        <p class="text-[10px] text-rose-400 mt-1">Inventory Overview</p>
                    </div>
                    <div class="p-3 rounded-xl bg-rose-500/10 text-rose-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- TAB 1: Money Management & Analytics View -->
            <div x-show="activeTab === 'finance'" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Income vs Expense Line Chart -->
                    <div class="lg:col-span-2 p-6 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-base text-slate-100">Revenue & Operations Expense Trend</h3>
                                <p class="text-xs text-slate-400">Weekly revenue cashflow tracking</p>
                            </div>
                            <span class="text-xs text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-lg font-semibold">Live Data</span>
                        </div>
                        <div class="h-64">
                            <canvas id="moneyFlowChart"></canvas>
                        </div>
                    </div>

                    <!-- Payment Method Split Doughnut Chart -->
                    <div class="p-6 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-base text-slate-100">Payment Gateways Split</h3>
                            <p class="text-xs text-slate-400 mb-4">Card vs Cash on Delivery</p>
                        </div>
                        <div class="h-48 flex items-center justify-center">
                            <canvas id="paymentSplitChart"></canvas>
                        </div>
                        <div class="flex justify-around text-xs text-slate-400 mt-4 pt-3 border-t border-slate-800">
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Card</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-teal-500 inline-block"></span> COD</span>
                        </div>
                    </div>
                </div>

                <!-- Financial Ledger Table -->
                <div class="p-6 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-base text-slate-100">Recent Financial Transactions</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs text-slate-300">
                            <thead>
                                <tr class="border-b border-slate-800 uppercase text-[10px] text-slate-400">
                                    <th class="py-3 px-4">Transaction ID</th>
                                    <th class="py-3 px-4">Type</th>
                                    <th class="py-3 px-4">Description</th>
                                    <th class="py-3 px-4">Amount</th>
                                    <th class="py-3 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @forelse($recentOrders ?? [] as $order)
                                    <tr>
                                        <td class="py-3.5 px-4 font-mono text-emerald-400">#TRX-{{ $order->id }}</td>
                                        <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 font-semibold">Income</span></td>
                                        <td class="py-3.5 px-4">Order Payment #ORD-{{ $order->id }} ({{ ucfirst($order->payment->payment_method ?? 'Card') }})</td>
                                        <td class="py-3.5 px-4 font-bold text-slate-100">+ Rs. {{ number_format($order->total_amount, 2) }}</td>
                                        <td class="py-3.5 px-4 text-emerald-400">{{ ucfirst($order->payment->payment_status ?? 'Settled') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-slate-500">No recent transactions.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Overview / Kitchen Live Orders View -->
            <div x-show="activeTab === 'overview'" x-cloak class="space-y-6">
                <!-- Action Banner -->
                <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-950/40 via-[#0b0f19] to-[#0b0f19] border border-emerald-500/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider mb-2">Live Store Operations</span>
                        <h3 class="text-lg font-bold text-white">Kitchen & Live Orders Management</h3>
                        <p class="text-xs text-slate-400 mt-1">Manage live order pipeline, update preparation stages, and dispatch delivery drivers.</p>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="p-6 rounded-2xl bg-[#0b0f19]/90 border border-slate-800/80 shadow-lg">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <h3 class="font-bold text-base text-slate-100">Live Customer Orders</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-[11px] uppercase text-slate-400 font-semibold">
                                    <th class="py-3 px-4">Order ID</th>
                                    <th class="py-3 px-4">Customer</th>
                                    <th class="py-3 px-4">Items</th>
                                    <th class="py-3 px-4">Total Amount</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Assign Driver</th>
                                    <th class="py-3 px-4">Update Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-xs text-slate-300">
                                @forelse($recentOrders ?? [] as $order)
                                    <tr class="hover:bg-slate-800/30 transition">
                                        <td class="py-4 px-4 font-semibold text-emerald-400">#ORD-{{ $order->id }}</td>
                                        <td class="py-4 px-4 font-medium text-slate-200">
                                            <div class="font-bold text-slate-100">{{ $order->user->name ?? 'Guest User' }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $order->deliveryZone->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="py-4 px-4 text-slate-300">
                                            @foreach($order->items ?? [] as $item)
                                                <div>{{ $item->quantity }}x {{ $item->item_name }}</div>
                                            @endforeach
                                        </td>
                                        <td class="py-4 px-4 font-bold text-slate-100">
                                            Rs. {{ number_format($order->total_amount, 2) }}
                                        </td>
                                        <td class="py-4 px-4">
                                            @php
                                                $status = strtolower($order->status ?? 'pending');
                                                $badgeClasses = match($status) {
                                                    'delivered', 'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                    'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                                    'preparing' => 'bg-teal-500/10 text-teal-400 border-teal-500/20',
                                                    default => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $badgeClasses }}">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        </td>

                                        <!-- Driver Assignment Form -->
                                        <td class="py-4 px-4">
                                            <form action="{{ route('admin.orders.assign-driver', $order->id) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="driver_id" class="bg-slate-900 border border-slate-700 text-xs rounded-lg px-2 py-1 text-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                                                    <option value="">Select Driver</option>
                                                    @foreach($drivers ?? [] as $driver)
                                                        <option value="{{ $driver->id }}" {{ optional($order->delivery)->driver_id == $driver->id ? 'selected' : '' }}>
                                                            {{ $driver->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 rounded-lg text-[10px] font-bold transition">
                                                    Save
                                                </button>
                                            </form>
                                        </td>

                                        <!-- Order Status Update Form -->
                                        <td class="py-4 px-4">
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-xs rounded-lg px-2 py-1 text-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                    <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-slate-500">
                                            No active orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart JS & Theme Switch Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');

            if (localStorage.getItem('color-theme') === 'light') {
                lightIcon?.classList.remove('hidden');
                document.documentElement.classList.remove('dark');
            } else {
                darkIcon?.classList.remove('hidden');
                document.documentElement.classList.add('dark');
            }

            themeToggleBtn?.addEventListener('click', () => {
                darkIcon?.classList.toggle('hidden');
                lightIcon?.classList.toggle('hidden');

                if (localStorage.getItem('color-theme') === 'light') {
                    localStorage.setItem('color-theme', 'dark');
                    document.documentElement.classList.add('dark');
                } else {
                    localStorage.setItem('color-theme', 'light');
                    document.documentElement.classList.remove('dark');
                }
            });

            // Financial Flow Line Chart
            const ctxMoney = document.getElementById('moneyFlowChart')?.getContext('2d');
            if (ctxMoney) {
                new Chart(ctxMoney, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [
                            {
                                label: 'Revenue (LKR)',
                                data: {{ Js::from($weeklyRevenue ?? [0,0,0,0,0,0,0]) }},
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { labels: { color: '#94a3b8', font: { size: 11 } } }
                        },
                        scales: {
                            x: { grid: { color: 'rgba(51, 65, 85, 0.3)' }, ticks: { color: '#94a3b8' } },
                            y: { grid: { color: 'rgba(51, 65, 85, 0.3)' }, ticks: { color: '#94a3b8' } }
                        }
                    }
                });
            }

            // Payment Split Doughnut Chart
            const ctxPayment = document.getElementById('paymentSplitChart')?.getContext('2d');
            if (ctxPayment) {
                new Chart(ctxPayment, {
                    type: 'doughnut',
                    data: {
                        labels: ['Card Online', 'Cash on Delivery'],
                        datasets: [{
                            data: [{{ Js::from($cardPaymentCount ?? 0) }}, {{ Js::from($codPaymentCount ?? 0) }}],
                            backgroundColor: ['#10b981', '#14b8a6'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        cutout: '72%'
                    }
                });
            }
        });
    </script>
</x-app-layout>