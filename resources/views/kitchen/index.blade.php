<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 tracking-tight flex items-center gap-2">
                <span class="animate-bounce">👨‍🍳</span> Kitchen Management
            </h2>
            <div class="flex items-center gap-3">
                <!-- Sound Toggle Button -->
                <button id="soundToggleBtn" onclick="toggleAudio()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl border transition flex items-center gap-1.5 shadow-sm">
                    🔊 Sound: On
                </button>

                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full border border-green-200 shadow-sm flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-ping"></span> Live Kitchen Status: Active
                </span>
            </div>
        </div>
    </x-slot>

    <!-- UI Custom Animations -->
    <style>
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 10px rgba(239, 68, 68, 0.15); }
            50% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
        }
        @keyframes cardSlideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-card { animation: cardSlideUp 0.4s ease-out forwards; }
        .order-urgent { animation: pulseGlow 1.5s infinite; }
    </style>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8" id="kitchenQueueContainer">

            <!-- Success Message Notification -->
            @if(session('message'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-xl shadow-sm flex justify-between items-center animate-card">
                    <span class="text-sm font-semibold text-green-800">✅ {{ session('message') }}</span>
                </div>
            @endif

            <!-- 1. Quick Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Orders</p>
                        <h3 class="text-3xl font-extrabold text-amber-500 mt-1">{{ $pendingCount }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 font-bold text-xl">⏳</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">In Preparation</p>
                        <h3 class="text-3xl font-extrabold text-blue-500 mt-1">{{ $preparingCount }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 font-bold text-xl">🍳</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ready for Pickup</p>
                        <h3 class="text-3xl font-extrabold text-green-500 mt-1">{{ $readyCount }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 font-bold text-xl">✅</div>
                </div>
            </div>

            <!-- 2. Active Orders Queue -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Active Kitchen Orders</h3>
                        <p class="text-xs text-slate-400">Orders that need cooking or packaging</p>
                    </div>
                    <button onclick="window.location.reload();" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition flex items-center gap-1.5 shadow-sm">
                        🔄 Refresh Queue
                    </button>
                </div>

                @if($orders->isEmpty())
                    <div class="text-center py-12 text-slate-400">
                        <span class="text-4xl block mb-2">🍽️</span>
                        <p class="font-medium text-sm">No active kitchen orders at the moment!</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($orders as $order)
                            <div class="animate-card bg-white border-2 rounded-2xl p-5 relative hover:shadow-lg transition 
                                {{ $order->status == 'pending' ? 'border-red-200 order-urgent' : ($order->status == 'preparing' ? 'border-blue-200' : 'border-green-200') }}">
                                
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                                    <div>
                                        <span class="text-xs font-bold text-slate-400">#ORD-{{ $order->id }}</span>
                                        <h4 class="font-bold text-slate-800 text-sm">{{ $order->user->name ?? 'Customer' }}</h4>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-full 
                                            {{ $order->status == 'pending' ? 'bg-red-100 text-red-600' : ($order->status == 'preparing' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600') }}">
                                            {{ $order->status }}
                                        </span>
                                        <!-- Real-time Elapsed Timer -->
                                        <div class="text-[10px] text-slate-400 font-mono mt-1 live-timer" data-time="{{ $order->created_at->toISOString() }}">
                                            ⏱️ 00m 00s
                                        </div>
                                    </div>
                                </div>

                                <!-- Items List -->
                                <ul class="space-y-2 mb-4 text-xs text-slate-600">
                                    @forelse($order->items as $item)
                                        <li class="flex justify-between items-center bg-slate-50 p-2 rounded-lg">
                                            <span><b class="text-slate-800">{{ $item->quantity }}x</b> {{ $item->productItem->name ?? 'Item' }}</span>
                                            @if(!empty($item->customization))
                                                <span class="text-slate-400 text-[10px]">{{ $item->customization }}</span>
                                            @endif
                                        </li>
                                    @empty
                                        <li class="text-slate-400 text-xs italic">No items listed</li>
                                    @endforelse
                                </ul>

                                <!-- Action Buttons -->
                                <form method="POST" action="{{ route('kitchen.orders.updateStatus', $order->id) }}">
                                    @csrf
                                    @method('PATCH')

                                    @if($order->status == 'pending')
                                        <input type="hidden" name="status" value="preparing">
                                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md hover:shadow-blue-500/20 transition active:scale-95 flex items-center justify-center gap-1">
                                            <span>🔥 Start Preparing</span>
                                        </button>
                                    @elseif($order->status == 'preparing')
                                        <input type="hidden" name="status" value="ready">
                                        <button type="submit" class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded-xl shadow-md hover:shadow-green-500/20 transition active:scale-95 flex items-center justify-center gap-1">
                                            <span>✅ Mark as Ready</span>
                                        </button>
                                    @elseif($order->status == 'ready')
                                        <div class="text-center py-2 bg-green-50 text-green-700 rounded-xl font-bold text-xs border border-green-200">
                                            📦 Ready for Delivery
                                        </div>
                                    @endif
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 3. Ingredient Stock Management Section -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span>📦</span> Ingredient Stock Availability
                </h3>

                @if($ingredients->isEmpty())
                    <p class="text-xs text-slate-400 italic">No ingredients found in stock database.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($ingredients as $ingredient)
                            <div class="p-4 rounded-xl border flex items-center justify-between transition {{ $ingredient->in_stock ? 'border-slate-100 bg-slate-50/50' : 'border-red-200 bg-red-50/50' }}">
                                <div>
                                    <p class="font-bold text-xs text-slate-800">{{ $ingredient->name }}</p>
                                    <p class="text-[11px] font-semibold mt-0.5 {{ $ingredient->in_stock ? 'text-green-600' : 'text-red-600' }}">
                                        Status: {{ $ingredient->in_stock ? 'In Stock' : 'Out of Stock' }}
                                    </p>
                                </div>
                                
                                <form method="POST" action="{{ route('kitchen.ingredients.toggle', $ingredient->id) }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg transition active:scale-95 {{ $ingredient->in_stock ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                        {{ $ingredient->in_stock ? 'Mark Out' : 'Mark In' }}
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Notification Audio element -->
    <audio id="orderAudio" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

    <!-- Clean JavaScript -->
    <script>
        let soundEnabled = true;

        function toggleAudio() {
            soundEnabled = !soundEnabled;
            const btn = document.getElementById('soundToggleBtn');
            if (btn) {
                btn.innerText = soundEnabled ? '🔊 Sound: On' : '🔇 Sound: Off';
            }
        }

        // Live Order Timer Script
        function updateTimers() {
            document.querySelectorAll('.live-timer').forEach(el => {
                const timeAttr = el.getAttribute('data-time');
                if (!timeAttr) return;

                const createdTime = new Date(timeAttr).getTime();
                const now = new Date().getTime();
                const diffSec = Math.floor((now - createdTime) / 1000);

                if (diffSec < 0) return;

                const minutes = Math.floor(diffSec / 60);
                const seconds = diffSec % 60;

                const formattedMin = String(minutes).padStart(2, '0');
                const formattedSec = String(seconds).padStart(2, '0');

                el.innerText = `⏱️ ${formattedMin}m ${formattedSec}s`;
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initial call
            updateTimers();
            
            // 1. Run live timer every 1 second
            setInterval(updateTimers, 1000);

            // 2. Auto Polling (Fetch & Refresh Queue every 15 seconds silently)
            setInterval(function () {
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const currentContainer = document.getElementById('kitchenQueueContainer');
                        const newContainer = doc.getElementById('kitchenQueueContainer');

                        if (currentContainer && newContainer) {
                            currentContainer.innerHTML = newContainer.innerHTML;
                            updateTimers();
                        }
                    })
                    .catch(error => console.error('Error syncing kitchen orders:', error));
            }, 15000);
        });
    </script>
</x-app-layout>