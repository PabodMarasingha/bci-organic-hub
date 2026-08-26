<x-guest-layout>
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    
    <!-- Custom CSS Animations & Tom Select Dark Theme Overrides -->
    <style>
        @keyframes flowBeam {
            0% { stroke-dashoffset: 1200; }
            100% { stroke-dashoffset: 0; }
        }

        .animated-path {
            stroke-dasharray: 220 980;
            animation: flowBeam 3s linear infinite;
        }

        /* --- BCI Organic Hub - Dark Theme Overrides for Tom Select --- */
        .ts-control, .ts-wrapper.single.input-active .ts-control {
            background-color: #141a26 !important;
            border: 1px solid rgba(51, 65, 85, 0.7) !important;
            color: #f1f5f9 !important;
            border-radius: 0.75rem !important;
            padding: 0.875rem !important;
            box-shadow: none !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease;
        }
        .ts-wrapper.single.focus .ts-control {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.5) !important;
        }
        .ts-dropdown {
            background-color: #141a26 !important;
            border: 1px solid rgba(51, 65, 85, 0.7) !important;
            color: #f1f5f9 !important;
            border-radius: 0.75rem !important;
            margin-top: 6px;
            overflow: hidden;
            font-size: 0.875rem !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }
        .ts-dropdown .option, .ts-dropdown .create {
            padding: 0.75rem 1rem !important;
            transition: background-color 0.2s ease;
        }
        .ts-dropdown .option.active, .ts-dropdown .option:hover, .ts-dropdown .create:hover {
            background-color: #1e293b !important;
            color: #34d399 !important;
        }
        .ts-control > input {
            color: #f1f5f9 !important;
            font-size: 0.875rem !important;
        }
        .ts-wrapper.single .ts-control:after {
            border-color: #94a3b8 transparent transparent transparent !important;
        }
        .ts-wrapper.single.dropdown-active .ts-control:after {
            border-color: transparent transparent #94a3b8 transparent !important;
        }
        /* Custom highlight for newly created typed items */
        .ts-dropdown .create {
            color: #10b981 !important;
            font-weight: bold;
        }
    </style>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Main Container -->
    <div class="relative min-h-screen flex flex-col justify-center items-center bg-[#05070c] px-4 sm:px-6 py-12 overflow-hidden selection:bg-emerald-500 selection:text-white">
        
        <!-- Interactive Floating Dust Particles Canvas Layer -->
        <canvas id="particles-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>

        <!-- Ambient Deep Glows -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-emerald-500/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[400px] h-[400px] bg-teal-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <!-- System Title -->
        <div class="relative z-10 mb-8 text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-green-500 drop-shadow-[0_0_25px_rgba(16,185,129,0.35)]">
                BCI Organic Hub
            </h1>
            <p class="text-[11px] text-slate-400 mt-2 uppercase tracking-[0.2em] font-semibold">Create Your Account</p>
        </div>

        <!-- Card Wrapper with SVG Dynamic Border Overlay -->
        <div class="relative z-10 w-full max-w-md" x-data="{ selectedRole: '{{ old('role', '') }}' }">
            
            <svg class="absolute -inset-[2px] w-[calc(100%+4px)] h-[calc(100%+4px)] pointer-events-none z-20" viewBox="0 0 448 740" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="glowGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#34d399" />
                        <stop offset="50%" stop-color="#2dd4bf" />
                        <stop offset="100%" stop-color="#10b981" />
                    </linearGradient>
                    <filter id="neonGlow" x="-20%" y="-20%" width="140%" height="140%">
                        <feGaussianBlur stdDeviation="3.5" result="blur" />
                        <feMerge>
                            <feMergeNode in="blur" />
                            <feMergeNode in="SourceGraphic" />
                        </feMerge>
                    </filter>
                </defs>

                <rect x="1" y="1" width="446" height="738" rx="16" ry="16" fill="none" stroke="#1e293b" stroke-width="1.5" />

                <path d="M 224 1 L 16 1 A 15 15 0 0 0 1 16 L 1 724 A 15 15 0 0 0 16 739 L 224 739" 
                      fill="none" 
                      stroke="url(#glowGrad)" 
                      stroke-width="3" 
                      stroke-linecap="round"
                      filter="url(#neonGlow)"
                      pathLength="1200"
                      class="animated-path" />

                <path d="M 224 1 L 432 1 A 15 15 0 0 1 447 16 L 447 724 A 15 15 0 0 1 432 739 L 224 739" 
                      fill="none" 
                      stroke="url(#glowGrad)" 
                      stroke-width="3" 
                      stroke-linecap="round"
                      filter="url(#neonGlow)"
                      pathLength="1200"
                      class="animated-path" />
            </svg>

            <!-- Inner Dark Glassmorphism Card -->
            <div class="relative z-10 w-full bg-[#0b0f19]/90 backdrop-blur-2xl p-8 rounded-2xl border border-slate-800/80 shadow-[0_10px_50px_rgba(0,0,0,0.85)]">
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Select Role Field -->
                    <div class="mb-4">
                        <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Select Role</label>
                        <select id="role" name="role" x-model="selectedRole" required 
                            class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none cursor-pointer">
                            <option value="" disabled selected class="bg-[#0b0f19] text-slate-500">Select Your Role</option>
                            <option value="customer" class="bg-[#0b0f19] text-slate-100">Customer / Student</option>
                            <option value="staff" class="bg-[#0b0f19] text-slate-100">Kitchen Staff</option>
                            <option value="delivery" class="bg-[#0b0f19] text-slate-100">Delivery Staff</option>
                            <option value="admin" class="bg-[#0b0f19] text-slate-100">Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-1.5 text-red-400 text-xs" />
                    </div>

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" 
                            class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-red-400 text-xs" />
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com" 
                            class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-red-400 text-xs" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" 
                            class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-red-400 text-xs" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" 
                            class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-red-400 text-xs" />
                    </div>

                    <!-- Dynamic Delivery Specific Fields -->
                    <div x-show="selectedRole === 'delivery'" x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="space-y-4 pt-2 mb-6 border-t border-slate-800/80">

                        <p class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase">Delivery Details</p>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Phone Number</label>
                            <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="0715285369" 
                                class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-1.5 text-red-400 text-xs" />
                        </div>

                        <!-- Delivery Zone (Professional Search & Add) -->
                        <div class="relative" wire:ignore>
                            <label for="delivery_zone_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Delivery Zone</label>
                            <select id="delivery_zone_id" name="delivery_zone_id" class="w-full" placeholder="Type to search or add zone...">
                                <option value="">Select or Type a Zone...</option>
                                @foreach($deliveryZones ?? [] as $zone)
                                    <option value="{{ $zone->id }}" {{ old('delivery_zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('delivery_zone_id')" class="mt-1.5 text-red-400 text-xs" />
                        </div>

                        <!-- Vehicle Type & Vehicle Number Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="vehicle_type" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Vehicle Type</label>
                                <input id="vehicle_type" type="text" name="vehicle_type" value="{{ old('vehicle_type') }}" placeholder="bike / scooter" 
                                    class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                                <x-input-error :messages="$errors->get('vehicle_type')" class="mt-1.5 text-red-400 text-xs" />
                            </div>

                            <div>
                                <label for="vehicle_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Vehicle Number</label>
                                <input id="vehicle_number" type="text" name="vehicle_number" value="{{ old('vehicle_number') }}" placeholder="va-3569" 
                                    class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                                <x-input-error :messages="$errors->get('vehicle_number')" class="mt-1.5 text-red-400 text-xs" />
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold tracking-wider rounded-xl shadow-lg shadow-emerald-950/80 transition duration-200 transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        CREATE ACCOUNT
                    </button>
                </form>

                <p class="text-xs text-slate-400 mt-6 text-center">
                    Already registered? 
                    <a href="{{ route('login') }}" class="text-emerald-400 font-bold hover:text-emerald-300 hover:underline transition ml-1">Log in</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // --- 1. Initialize Tom Select (Allowing creation of new typed options) ---
            if (document.getElementById('delivery_zone_id')) {
                new TomSelect("#delivery_zone_id", {
                    create: true, // මෙතැන 'true' කළ නිසා දැන් Type කරන ඕනෑම එකක් Save වේවි
                    createOnBlur: true, // Click නොකර එළියට ගියත් Type කරපු එක Save වෙනවා
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: "Type to search or add zone...",
                    render: {
                        option_create: function(data, escape) {
                            return '<div class="create">Add new zone: <strong>' + escape(data.input) + '</strong></div>';
                        }
                    }
                });
            }

            // --- 2. Particles Animation ---
            const canvas = document.getElementById('particles-canvas');
            const ctx = canvas.getContext('2d');

            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;

            window.addEventListener('resize', () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            });

            const particleCount = 45;
            const particles = [];

            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    radius: Math.random() * 2 + 0.5,
                    vx: (Math.random() - 0.5) * 0.4,
                    vy: (Math.random() - 0.5) * 0.4,
                    alpha: Math.random() * 0.6 + 0.2
                });
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);

                particles.forEach(p => {
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0) p.x = width;
                    if (p.x > width) p.x = 0;
                    if (p.y < 0) p.y = height;
                    if (p.y > height) p.y = 0;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(52, 211, 153, ${p.alpha})`;
                    ctx.shadowBlur = 10;
                    ctx.shadowColor = '#10b981';
                    ctx.fill();
                });

                requestAnimationFrame(animate);
            }

            animate();
        });
    </script>
</x-guest-layout>