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

    <div class="relative min-h-screen flex flex-col justify-center items-center bg-organic-cream dark:bg-[#05070c] px-4 sm:px-6 py-12 overflow-hidden selection:bg-organic-gold selection:text-organic-charcoal transition-colors duration-300">

        <canvas id="particles-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-organic-green/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[400px] h-[400px] bg-organic-gold/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <div class="relative z-10 mb-8 text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-wider text-organic-green dark:text-organic-gold">
                BCI Organic Hub
            </h1>
            <p class="text-[11px] text-organic-charcoal/60 dark:text-slate-400 mt-2 uppercase tracking-[0.2em] font-semibold">Create Your Customer Account</p>
        </div>

        <div class="relative z-10 w-full max-w-md" x-data="{ selectedRole: 'customer' }">
            <div class="relative z-10 w-full bg-white/90 dark:bg-[#0b0f19]/90 backdrop-blur-2xl p-8 rounded-2xl border border-organic-green/15 dark:border-slate-800/80 shadow-xl">

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe"
                            class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-organic-tomato text-xs" />
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com"
                            class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-organic-tomato text-xs" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                            class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-organic-tomato text-xs" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                            class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-organic-tomato text-xs" />
                    </div>

                    <!-- Dynamic Delivery Specific Fields -->
                    <div x-show="selectedRole === 'delivery'" x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="space-y-4 pt-2 mb-6 border-t border-organic-green/20 dark:border-slate-800/80">

                        <p class="text-[10px] font-bold tracking-widest text-organic-green dark:text-organic-gold uppercase">Delivery Details</p>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Phone Number</label>
                            <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="0715285369" 
                                class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-1.5 text-organic-tomato text-xs" />
                        </div>

                        <!-- Delivery Zone -->
                        <div class="relative" wire:ignore>
                            <label for="delivery_zone_id" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Delivery Zone</label>
                            <select id="delivery_zone_id" name="delivery_zone_id" class="w-full" placeholder="Type to search or add zone...">
                                <option value="">Select or Type a Zone...</option>
                                @foreach($deliveryZones ?? [] as $zone)
                                    <option value="{{ $zone->id }}" {{ old('delivery_zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('delivery_zone_id')" class="mt-1.5 text-organic-tomato text-xs" />
                        </div>

                        <!-- Vehicle Type & Vehicle Number Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="vehicle_type" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Vehicle Type</label>
                                <input id="vehicle_type" type="text" name="vehicle_type" value="{{ old('vehicle_type') }}" placeholder="bike / scooter" 
                                    class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                                <x-input-error :messages="$errors->get('vehicle_type')" class="mt-1.5 text-organic-tomato text-xs" />
                            </div>

                            <div>
                                <label for="vehicle_number" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Vehicle Number</label>
                                <input id="vehicle_number" type="text" name="vehicle_number" value="{{ old('vehicle_number') }}" placeholder="va-3569" 
                                    class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                                <x-input-error :messages="$errors->get('vehicle_number')" class="mt-1.5 text-organic-tomato text-xs" />
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-4 bg-organic-green hover:bg-organic-green-light dark:bg-organic-gold dark:hover:bg-organic-gold/90 text-white dark:text-organic-charcoal font-bold tracking-wider rounded-xl shadow-lg transition duration-200 transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-organic-green dark:focus:ring-organic-gold">
                        CREATE ACCOUNT
                    </button>
                </form>

                <p class="text-xs text-organic-charcoal/60 dark:text-slate-400 mt-6 text-center">
                    Already registered?
                    <a href="{{ route('login') }}" class="text-organic-green dark:text-organic-gold font-bold hover:underline transition ml-1">Log in</a>
                </p>

                <p class="text-[10px] text-organic-charcoal/40 dark:text-slate-500 mt-3 text-center">
                    Staff accounts (Kitchen, Delivery, Admin) are created by an administrator, not through self-registration.
                </p>
            </div>
        </div>
    </div>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // --- 1. Initialize Tom Select ---
            if (document.getElementById('delivery_zone_id')) {
                new TomSelect("#delivery_zone_id", {
                    create: true,
                    createOnBlur: true,
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
            if (canvas) {
                const ctx = canvas.getContext('2d');
                let width = canvas.width = window.innerWidth;
                let height = canvas.height = window.innerHeight;
                window.addEventListener('resize', () => {
                    width = canvas.width = window.innerWidth;
                    height = canvas.height = window.innerHeight;
                });
                const particles = [];
                for (let i = 0; i < 45; i++) {
                    particles.push({
                        x: Math.random() * width, y: Math.random() * height,
                        radius: Math.random() * 2 + 0.5,
                        vx: (Math.random() - 0.5) * 0.4, vy: (Math.random() - 0.5) * 0.4,
                        alpha: Math.random() * 0.6 + 0.2
                    });
                }
                function animate() {
                    ctx.clearRect(0, 0, width, height);
                    const isDark = document.documentElement.classList.contains('dark');
                    const color = isDark ? '242, 169, 59' : '31, 77, 58';
                    particles.forEach(p => {
                        p.x += p.vx; p.y += p.vy;
                        if (p.x < 0) p.x = width; if (p.x > width) p.x = 0;
                        if (p.y < 0) p.y = height; if (p.y > height) p.y = 0;
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                        ctx.fillStyle = `rgba(${color}, ${p.alpha})`;
                        ctx.fill();
                    });
                    requestAnimationFrame(animate);
                }
                animate();
            }
        });
    </script>
</x-guest-layout>