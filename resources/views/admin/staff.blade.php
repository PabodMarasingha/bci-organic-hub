<x-app-layout>
    <div class="py-8 bg-[#060913] min-h-screen text-slate-100 font-sans" 
         x-data="{ 
             editModalOpen: false, 
             editUser: {}, 
             createRole: 'kitchen',
             notificationOpen: true 
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Page Title -->
            <div>
                <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-emerald-400 to-green-400">
                    System User & Staff Management
                </h2>
                <p class="text-xs text-slate-400 mt-1">Assign roles, manage access levels, vehicle details, and assign delivery zones to staff members.</p>
            </div>

            <!-- Notification -->
            @if (session('success') || session('message'))
                <div x-show="notificationOpen" 
                     x-transition
                     class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold shadow-lg flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('success') ?? session('message') }}</span>
                    </div>
                    <button @click="notificationOpen = false" class="text-emerald-400 hover:text-emerald-200 font-bold">&times;</button>
                </div>
            @endif

            <!-- Create Staff Form -->
            <div class="p-6 rounded-2xl bg-[#0c121e] border border-slate-800/80 shadow-xl">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    Register New Staff Account
                </h3>
                <form method="POST" action="{{ route('admin.staff.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Full Name</label>
                        <input name="name" type="text" placeholder="John Doe" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Email Address</label>
                        <input name="email" type="email" placeholder="user@bci.test" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Phone Number</label>
                        <input name="phone_number" type="text" placeholder="0771234567" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">Password</label>
                        <input name="password" type="password" placeholder="••••••••" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-xs text-slate-400 mb-1 font-semibold">System Role</label>
                        <select name="role" x-model="createRole" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 uppercase" required>
@foreach($roles->where('name', '!=', 'customer') as $role)
    <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
@endforeach
                        </select>
                    </div>

                    <!-- Dynamic Delivery Details Fields -->
                    <template x-if="createRole === 'delivery'">
                        <div class="md:col-span-4 grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-slate-800/60" x-transition>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1 font-semibold">Assigned Delivery Zone</label>
                                <select name="delivery_zone_id" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                                    <option value="">-- Select Zone --</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->name }} (Rs. {{ number_format($zone->fee, 2) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1 font-semibold">Vehicle Type</label>
                                <input name="vehicle_type" type="text" placeholder="Motorbike / Three Wheeler" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1 font-semibold">Vehicle Number</label>
                                <input name="vehicle_number" type="text" placeholder="AB-1234" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                            </div>
                        </div>
                    </template>

                    <div class="md:col-span-4 flex justify-end mt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>

            <!-- Staff Accounts Table -->
            <div class="p-6 rounded-2xl bg-[#0c121e] border border-slate-800/80 shadow-xl">
                <h3 class="text-sm font-bold text-slate-200 mb-6">Registered System Accounts</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-[11px] uppercase text-slate-400 font-semibold tracking-wider">
                                <th class="py-3 px-4">User Name</th>
                                <th class="py-3 px-4">Email / Phone</th>
                                <th class="py-3 px-4">Role</th>
                                <th class="py-3 px-4">Assigned Zone</th>
                                <th class="py-3 px-4">Vehicle Info</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-xs text-slate-300">
                            @forelse ($staff as $user)
                                @php
                                    $roleName = $user->roles->first()?->name ?? 'customer';
                                    $badgeStyle = match(strtolower($roleName)) {
                                        'admin' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                        'kitchen', 'staff' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                        'delivery' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
                                        default => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-slate-100">{{ $user->name }}</td>
                                    <td class="py-3.5 px-4 text-slate-400">
                                        <div>{{ $user->email }}</div>
                                        <div class="text-[10px] text-slate-500">{{ $user->phone_number ?? 'No Phone' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badgeStyle }}">
                                            {{ $roleName === 'staff' ? 'KITCHEN STAFF' : $roleName }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-400">
                                        {{ $user->deliveryZone?->name ?? '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-400">
                                        @if($user->vehicle_type || $user->vehicle_number)
                                            <span class="text-xs text-slate-300 font-medium">{{ $user->vehicle_type }}</span>
                                            <span class="text-[10px] text-teal-400 block">{{ $user->vehicle_number }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right space-x-3">
                                        <button 
                                            @click="editUser = { 
                                                id: {{ $user->id }}, 
                                                name: '{{ addslashes($user->name) }}', 
                                                email: '{{ $user->email }}', 
                                                phone_number: '{{ $user->phone_number ?? '' }}', 
                                                role: '{{ $roleName }}', 
                                                delivery_zone_id: '{{ $user->delivery_zone_id ?? '' }}',
                                                vehicle_type: '{{ $user->vehicle_type ?? '' }}',
                                                vehicle_number: '{{ $user->vehicle_number ?? '' }}'
                                            }; editModalOpen = true"
                                            class="text-teal-400 hover:text-teal-300 font-semibold text-xs transition hover:underline">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.staff.destroy', $user->id) }}" class="inline-block" onsubmit="return confirm('Delete this account permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-400 font-semibold text-xs transition hover:underline">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-500">No system accounts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Edit Staff Modal -->
        <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="editModalOpen" @click="editModalOpen = false" class="fixed inset-0 bg-black/80 backdrop-blur-md"></div>
            <div x-show="editModalOpen" class="bg-[#0c121e] border border-slate-800 rounded-2xl w-full max-w-lg p-6 shadow-2xl relative z-10 space-y-4">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-slate-100">Edit Account Details</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-white text-xl">&times;</button>
                </div>
                <form :action="`/admin/staff/${editUser.id}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Full Name</label>
                        <input type="text" name="name" x-model="editUser.name" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Email</label>
                            <input type="email" name="email" x-model="editUser.email" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Phone Number</label>
                            <input type="text" name="phone_number" x-model="editUser.phone_number" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">New Password (Leave blank to keep unchanged)</label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Role</label>
                        <select name="role" x-model="editUser.role" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 uppercase" required>
@foreach($roles->where('name', '!=', 'customer') as $role)
    <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
@endforeach
                        </select>
                    </div>

                    <!-- Dynamic Delivery Zone and Vehicle Edit Fields -->
                    <template x-if="editUser.role === 'delivery'">
                        <div class="space-y-3 pt-2 border-t border-slate-800" x-transition>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Delivery Zone</label>
                                <select name="delivery_zone_id" x-model="editUser.delivery_zone_id" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                                    <option value="">-- Select Zone --</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Vehicle Type</label>
                                    <input type="text" name="vehicle_type" x-model="editUser.vehicle_type" placeholder="Bike/Car" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Vehicle Number</label>
                                    <input type="text" name="vehicle_number" x-model="editUser.vehicle_number" placeholder="WP ABC-1234" class="w-full bg-[#121a29] border border-slate-700/60 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 text-slate-950 font-bold text-xs uppercase">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>