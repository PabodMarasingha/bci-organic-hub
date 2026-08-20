<x-guest-layout>
    <style>
        @keyframes flowBeam {
            0% { stroke-dashoffset: 1000; }
            100% { stroke-dashoffset: 0; }
        }
        .animated-path {
            stroke-dasharray: 220 780;
            animation: flowBeam 3s linear infinite;
        }
    </style>

    <div class="relative min-h-screen flex flex-col justify-center items-center bg-organic-cream dark:bg-[#05070c] px-4 sm:px-6 py-12 overflow-hidden selection:bg-organic-gold selection:text-organic-charcoal transition-colors duration-300">

        <canvas id="particles-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-organic-green/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[400px] h-[400px] bg-organic-gold/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <div class="relative z-10 mb-8 text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-wider text-organic-green dark:text-organic-gold">
                BCI Organic Hub
            </h1>
            <p class="text-[11px] text-organic-charcoal/60 dark:text-slate-400 mt-2 uppercase tracking-[0.2em] font-semibold">Management & Ordering System</p>
        </div>

        <div class="relative z-10 w-full max-w-md" x-data="{ step: 'role', selectedRole: '' }">

            <svg class="absolute -inset-[2px] w-[calc(100%+4px)] h-[calc(100%+4px)] pointer-events-none z-20" viewBox="0 0 448 556" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="glowGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#1F4D3A" />
                        <stop offset="50%" stop-color="#2D6A4F" />
                        <stop offset="100%" stop-color="#F2A93B" />
                    </linearGradient>
                    <filter id="neonGlow" x="-20%" y="-20%" width="140%" height="140%">
                        <feGaussianBlur stdDeviation="3.5" result="blur" />
                        <feMerge>
                            <feMergeNode in="blur" />
                            <feMergeNode in="SourceGraphic" />
                        </feMerge>
                    </filter>
                </defs>

                <rect x="1" y="1" width="446" height="554" rx="16" ry="16" fill="none" stroke="#1e293b" stroke-width="1.5" class="dark:opacity-100 opacity-0" />
                <rect x="1" y="1" width="446" height="554" rx="16" ry="16" fill="none" stroke="#E8E4D9" stroke-width="1.5" class="dark:opacity-0 opacity-100" />

                <path d="M 224 1 L 16 1 A 15 15 0 0 0 1 16 L 1 540 A 15 15 0 0 0 16 555 L 224 555"
                      fill="none"
                      stroke="url(#glowGrad)"
                      stroke-width="3"
                      stroke-linecap="round"
                      filter="url(#neonGlow)"
                      pathLength="1000"
                      class="animated-path" />

                <path d="M 224 1 L 432 1 A 15 15 0 0 1 447 16 L 447 540 A 15 15 0 0 1 432 555 L 224 555"
                      fill="none"
                      stroke="url(#glowGrad)"
                      stroke-width="3"
                      stroke-linecap="round"
                      filter="url(#neonGlow)"
                      pathLength="1000"
                      class="animated-path" />
            </svg>

            <div class="relative z-10 w-full bg-white/90 dark:bg-[#0b0f19]/90 backdrop-blur-2xl p-8 rounded-2xl border border-organic-green/15 dark:border-slate-800/80 shadow-xl">

                <x-auth-session-status class="mb-4 text-organic-green dark:text-organic-gold text-sm" :status="session('status')" />

                <!-- Step 1: Select Role -->
                <div x-show="step === 'role'">
                    <p class="text-sm text-organic-charcoal/70 dark:text-slate-300 mb-4 text-center font-medium">Log in as:</p>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button"
                            @click="step = 'form'; selectedRole = 'customer'; document.getElementById('email').value = 'customer@bci.test'; document.getElementById('password').value = 'password';"
                            class="bg-organic-green hover:bg-organic-green-light text-white font-semibold py-3 rounded-lg transition">
                            Customer
                        </button>
                        <button type="button"
                            @click="step = 'form'; selectedRole = 'kitchen'; document.getElementById('email').value = 'kitchen@bci.test'; document.getElementById('password').value = 'password';"
                            class="bg-organic-gold hover:bg-organic-gold/90 text-organic-charcoal font-semibold py-3 rounded-lg transition">
                            Kitchen Staff
                        </button>
                        <button type="button"
                            @click="step = 'form'; selectedRole = 'delivery'; document.getElementById('email').value = 'delivery@bci.test'; document.getElementById('password').value = 'password';"
                            class="bg-organic-tomato hover:bg-organic-tomato/90 text-white font-semibold py-3 rounded-lg transition">
                            Delivery
                        </button>
                        <button type="button"
                            @click="step = 'form'; selectedRole = 'admin'; document.getElementById('email').value = 'admin@bci.test'; document.getElementById('password').value = 'password';"
                            class="bg-organic-charcoal hover:bg-organic-charcoal/90 text-white font-semibold py-3 rounded-lg transition">
                            Admin
                        </button>
                    </div>

                    <p class="text-sm text-organic-charcoal/60 dark:text-slate-400 mt-6 text-center">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-organic-green dark:text-organic-gold font-semibold underline">Register</a>
                    </p>
                </div>

                <!-- Step 2: Email/Password Form -->
                <div x-show="step === 'form'" x-cloak>
                    <button type="button" @click="step = 'role'" class="text-sm text-organic-charcoal/50 dark:text-slate-500 hover:text-organic-green dark:hover:text-organic-gold mb-4 transition">
                        &larr; Back to role selection
                    </button>

                    <form method="POST" action="{{ route('login') }}">
    @csrf
    <input type="hidden" name="role" x-model="selectedRole">

                        <div class="mb-5">
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com"
                                class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-organic-tomato text-xs" />
                        </div>

                        <div class="mb-5">
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                                class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-organic-tomato text-xs" />
                        </div>

                        <div class="flex items-center justify-between mb-6 text-sm">
                            <label class="flex items-center text-organic-charcoal/60 dark:text-slate-400 cursor-pointer select-none">
                                <input id="remember_me" type="checkbox" name="remember" class="rounded bg-organic-cream dark:bg-[#141a26] border-organic-green/30 dark:border-slate-700 text-organic-green dark:text-organic-gold focus:ring-organic-green/40 dark:focus:ring-organic-gold/40">
                                <span class="ml-2 text-xs text-organic-charcoal/70 dark:text-slate-300 font-medium">Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-organic-green dark:text-organic-gold hover:underline transition duration-150 font-medium text-xs">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-organic-green hover:bg-organic-green-light dark:bg-organic-gold dark:hover:bg-organic-gold/90 text-white dark:text-organic-charcoal font-bold tracking-wider rounded-xl shadow-lg transition duration-200 transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-organic-green dark:focus:ring-organic-gold">
                            LOG IN
                        </button>
                    </form>

                    <p class="text-xs text-organic-charcoal/60 dark:text-slate-400 mt-6 text-center">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-organic-green dark:text-organic-gold font-bold hover:underline transition ml-1">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('particles-canvas');
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
        });
    </script>
</x-guest-layout>