<x-guest-layout>
    <style>
        /* Modern Soft Glow & Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 15px rgba(34, 197, 94, 0.2); }
            50% { box-shadow: 0 0 25px rgba(34, 197, 94, 0.45); }
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        
        .role-card {
            border: 2px solid #e2e8f0;
            background-color: #ffffff;
            color: #475569;
            transition: all 0.3s ease;
        }
        
        /* Active Selected State */
        .role-card-active {
            border-color: #22c55e !important;
            background-color: rgba(34, 197, 94, 0.08) !important;
            color: #15803d !important;
            animation: pulseGlow 2.5s infinite;
        }
    </style>

    <div class="min-h-screen flex flex-col justify-center items-center bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Header / Logo -->
        <div class="text-center mb-6 animate-fade-in">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">
                BCI Organic Hub
            </h2>
            <p class="text-sm text-slate-500 mt-1">Select a role and click Sign In to proceed</p>
        </div>

        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100 animate-fade-in">
            
            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Role Selection Grid -->
            <div class="mb-6">
                <label class="block text-xs font-semibold uppercase text-slate-400 mb-3 tracking-wider text-center">
                    Select Your Role
                </label>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Customer Card -->
                    <button type="button" onclick="setRole('customer')" id="card-customer"
                            class="role-card flex flex-col items-center p-4 rounded-xl cursor-pointer">
                        <svg class="w-7 h-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="text-sm font-bold">Customer</span>
                    </button>

                    <!-- Kitchen Card -->
                    <button type="button" onclick="setRole('kitchen')" id="card-kitchen"
                            class="role-card flex flex-col items-center p-4 rounded-xl cursor-pointer">
                        <svg class="w-7 h-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="text-sm font-bold">Kitchen</span>
                    </button>

                    <!-- Delivery Card -->
                    <button type="button" onclick="setRole('delivery')" id="card-delivery"
                            class="role-card flex flex-col items-center p-4 rounded-xl cursor-pointer">
                        <svg class="w-7 h-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="text-sm font-bold">Delivery</span>
                    </button>

                    <!-- Admin Card -->
                    <button type="button" onclick="setRole('admin')" id="card-admin"
                            class="role-card flex flex-col items-center p-4 rounded-xl cursor-pointer">
                        <svg class="w-7 h-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span class="text-sm font-bold">Admin</span>
                    </button>
                </div>
            </div>

            <!-- Sign In Action Button -->
            <button type="button" onclick="loginWithSelectedRole()" 
                    class="w-full py-3.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-green-500/30 transition duration-300 transform active:scale-95 text-base">
                Sign In
            </button>

            <!-- Register Link -->
            @if (Route::has('register'))
                <p class="text-center text-sm text-slate-500 mt-6">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-700 transition">
                        Register here
                    </a>
                </p>
            @endif

        </div>
    </div>

    <!-- JavaScript Handling -->
    <script>
        // Default select customer
        let selectedRole = 'customer';

        function setRole(role) {
            selectedRole = role;

            // Remove active class from all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('role-card-active');
            });

            // Add active class to clicked card
            const activeCard = document.getElementById('card-' + role);
            if (activeCard) {
                activeCard.classList.add('role-card-active');
            }
        }

        function loginWithSelectedRole() {
            // Redirect directly to quick login route
            window.location.href = "{{ url('/quick-login') }}/" + selectedRole;
        }

        // Initialize default selection on page load
        document.addEventListener('DOMContentLoaded', function () {
            setRole('customer');
        });
    </script>
</x-guest-layout>