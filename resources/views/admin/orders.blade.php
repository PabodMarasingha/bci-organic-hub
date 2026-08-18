<x-app-layout>
    <div class="py-8 bg-[#060913] min-h-screen text-slate-100 font-sans" x-data="{ notificationOpen: true }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Title -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-emerald-400 to-green-400">
                        Kitchen & Live Orders Management
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Manage live order pipeline, update kitchen preparation stages, and dispatch drivers.</p>
                </div>
            </div>

            <!-- Notification -->
            @if (session('success'))
                <div x-show="notificationOpen" class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button @click="notificationOpen = false" class="text-emerald-400 font-bold">&times;</button>
                </div>
            @endif

            <!-- Filter Status Badges -->
            <div class="flex gap-2 border-b border-slate-800 pb-4 overflow-x-auto">
                <a href="{{ route('admin.orders') }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ !request('status') ? 'bg-teal-500 text-slate-950' : 'bg-[#0c121e] text-slate-400' }}">All Orders</a>
                <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ request('status') === 'pending' ? 'bg-amber-500 text-slate-950' : 'bg-[#0c121e] text-slate-400' }}">Pending</a>
                <a href="{{ route('admin.orders', ['status' => 'processing']) }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ request('status') === 'processing' ? 'bg-blue-500 text-slate-950' : 'bg-[#0c121e] text-slate-400' }}">Kitchen Processing</a>
                <a href="{{ route('admin.orders', ['status' => 'ready']) }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ request('status') === 'ready' ? 'bg-purple-500 text-slate-950' : 'bg-[#0c121e] text-slate-400' }}">Ready for Pickup</a>
                <a href="{{ route('admin.orders', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ request('status') === 'completed' ? 'bg-emerald-500 text-slate-950' : 'bg-[#0c121e] text-slate-400' }}">Completed</a>
            </div>

            <!-- Orders Table -->
            <div class="p-6 rounded-2xl bg-[#0c121e] border border-slate-800/80 shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-[11px] uppercase text-slate-400 font-semibold tracking-wider">
                                <th class="py-3 px-4">Order ID</th>
                                <th class="py-3 px-4">Customer</th>
                                <th class="py-3 px-4">Items</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Assign Driver</th>
                                <th class="py-3 px-4 text-right">Update Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-xs text-slate-300">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-4 font-mono font-bold text-teal-400">#ORD-{{ $order->id }}</td>
                                    <td class="py-4 px-4">
                                        <div class="font-semibold text-slate-100">{{ $order->user?->name ?? 'Guest' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $order->deliveryZone?->name }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <ul class="space-y-1">
                                            @foreach($order->items as $item)
                                                <li class="text-[11px]">
                                                    <span class="font-bold text-slate-200">{{ $item->quantity }}x</span> {{ $item->item_name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-slate-100">Rs. {{ number_format($order->total_amount, 2) }}</td>
                                    <td class="py-4 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border 
                                            {{ match($order->status) {
                                                'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                                'processing' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
                                                'ready' => 'bg-purple-500/10 text-purple-400 border-purple-500/30',
                                                'out_for_delivery' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
                                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                                default => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                                            } }}">
                                            {{ str_replace('_', ' ', $order->status) }}
                                        </span>
                                    </td>

                                    <!-- Driver Assignment Form -->
                                    <td class="py-4 px-4">
                                        <form method="POST" action="{{ route('admin.orders.assignDriver', $order->id) }}" class="flex items-center gap-2">
                                            @csrf
                                            <select name="driver_id" class="bg-[#121a29] border border-slate-700 rounded-lg px-2 py-1 text-[11px] text-slate-300 focus:outline-none">
                                                <option value="">-- Assign Driver --</option>
                                                @foreach($deliveryDrivers as $driver)
                                                    <option value="{{ $driver->id }}" {{ ($order->delivery?->driver_id ?? $order->delivery?->user_id) == $driver->id ? 'selected' : '' }}>
                                                        {{ $driver->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-teal-400 text-[10px] font-bold rounded">Save</button>
                                        </form>
                                    </td>

                                    <!-- Status Change Form -->
                                    <td class="py-4 px-4 text-right">
                                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="bg-[#121a29] border border-slate-700 rounded-lg px-2 py-1 text-[11px] text-slate-200 focus:outline-none focus:border-teal-500">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Kitchen)</option>
                                                <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready for Delivery</option>
                                                <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-slate-500">No active orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>