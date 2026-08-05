<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Manage Staff Accounts</h1>

        @if (session('message'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('message') }}</div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.staff.store') }}" class="mb-6 border p-4 rounded">
            @csrf
            <label class="block text-sm font-medium mb-1">Name</label>
            <input name="name" class="border p-2 rounded w-full mb-2" required>

            <label class="block text-sm font-medium mb-1">Email</label>
            <input name="email" type="email" class="border p-2 rounded w-full mb-2" required>

            <label class="block text-sm font-medium mb-1">Password</label>
            <input name="password" type="password" class="border p-2 rounded w-full mb-2" required>

            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" id="roleSelect" class="border p-2 rounded w-full mb-2" required onchange="toggleZone()">
                <option value="kitchen">Kitchen</option>
                <option value="delivery">Delivery</option>
                <option value="admin">Admin</option>
            </select>

            <div id="zoneField">
                <label class="block text-sm font-medium mb-1">Delivery Zone (delivery role only)</label>
                <select name="delivery_zone_id" class="border p-2 rounded w-full mb-2">
                    <option value="">— Select Zone —</option>
                    @foreach ($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </select>
            </div>

            <button class="bg-green-600 text-white px-4 py-2 rounded">Create Account</button>
        </form>

        <h2 class="font-semibold mb-2">Existing Staff</h2>
        @foreach ($staff as $person)
            <div class="flex justify-between items-center border-b py-2">
                <span>
                    {{ $person->name }} ({{ $person->email }}) —
                    {{ $person->roles->pluck('name')->join(', ') }}
                    @if ($person->deliveryZone)
                        — Zone: {{ $person->deliveryZone->name }}
                    @endif
                </span>
                <form method="POST" action="{{ route('admin.staff.destroy', $person->id) }}">
                    @csrf @method('DELETE')
                    <button class="text-red-600">Remove</button>
                </form>
            </div>
        @endforeach
    </div>

    <script>
        function toggleZone() {
            const role = document.getElementById('roleSelect').value;
            document.getElementById('zoneField').style.display = role === 'delivery' ? 'block' : 'none';
        }
        toggleZone();
    </script>
</x-app-layout>