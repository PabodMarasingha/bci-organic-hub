<x-guest-layout>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 15px rgba(34, 197, 94, 0.2); }
            50% { box-shadow: 0 0 25px rgba(34, 197, 94, 0.45); }
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        .role-card-active {
            border-color: #22c55e !important;
            background-color: rgba(34, 197, 94, 0.08) !important;
            color: #15803d !important;
            animation: pulseGlow 3s infinite;
        }
    </style>

    <div class="min-h-screen flex flex-col justify-center items-center bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Header / Logo -->
        <div class="text-center mb-6 animate-fade-in">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">
                Create an Account
            </h2>
            <p class="text-sm text-slate-500 mt-1">Select your account type and register below</p>
        </div>

        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100 animate-fade-in">
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- 1. Role Selection Grid -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold uppercase text-slate-400 mb-3 tracking-wider">
                        Register As
                    </label>
                    <input type="hidden" name="role" id="selected_role" value="customer">

                    <div class="grid grid-cols-2 gap-3">
                        <!-- Customer -->
                        <button type="button" onclick="selectRole('customer')" id="role-btn-customer" 
                                class="role-btn role-card-active flex flex-col items-center p-3 rounded-xl border-2 border-slate-200 transition-all duration-300 hover:border-green-400">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="text-xs font-bold">Customer</span>
                        </button>

                        <!-- Kitchen Staff -->
                        <button type="button" onclick="selectRole('kitchen')" id="role-btn-kitchen" 
                                class="role-btn flex flex-col items-center p-3 rounded-xl border-2 border-slate-200 text-slate-600 transition-all duration-300 hover:border-green-400">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span class="text-xs font-bold">Kitchen Staff</span>
                        </button>

                        <!-- Delivery Driver -->
                        <button type="button" onclick="selectRole('delivery')" id="role-btn-delivery" 
                                class="role-btn flex flex-col items-center p-3 rounded-xl border-2 border-slate-200 text-slate-600 transition-all duration-300 hover:border-green-400">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span class="text-xs font-bold">Delivery Driver</span>
                        </button>

                        <!-- Admin -->
                        <button type="button" onclick="selectRole('admin')" id="role-btn-admin" 
                                class="role-btn flex flex-col items-center p-3 rounded-xl border-2 border-slate-200 text-slate-600 transition-all duration-300 hover:border-green-400">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span class="text-xs font-bold">Admin</span>
                        </button>
                    </div>
                </div>

                <!-- Name Input -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition duration-200">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition duration-200">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition duration-200">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password Input -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition duration-200">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <!-- Register Submit Button -->
                <button type="submit" 
                        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-green-500/30 transition duration-300 transform active:scale-95">
                    Create Account
                </button>
            </form>

            <!-- Login Link -->
            <p class="text-center text-sm text-slate-500 mt-6">
                Already registered? 
                <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:text-green-700 transition">
                    Sign in here
                </a>
            </p>

        </div>
    </div>

    <!-- Role Selection JS -->
    <script>
        function selectRole(role) {
            document.getElementById('selected_role').value = role;

            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.classList.remove('role-card-active');
                btn.classList.add('text-slate-600');
            });

            const activeBtn = document.getElementById('role-btn-' + role);
            activeBtn.classList.add('role-card-active');
            activeBtn.classList.remove('text-slate-600');
        }
    </script>
</x-guest-layout>