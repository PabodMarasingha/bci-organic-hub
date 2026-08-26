<x-guest-layout>
    <!-- Custom CSS Animations -->
    <style>
        @keyframes flowBeam {
            0% { stroke-dashoffset: 1000; }
            100% { stroke-dashoffset: 0; }
        }
        .animated-path {
            stroke-dasharray: 220 780;
            animation: flowBeam 3s linear infinite;
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }
        @keyframes floatReverse {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(4px); }
        }
        .animate-float-slow { animation: floatSlow 4s ease-in-out infinite; }
        .animate-float-reverse { animation: floatReverse 5s ease-in-out infinite; }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(4px); }
            15% { opacity: 1; transform: translateY(0px); }
            85% { opacity: 1; transform: translateY(0px); }
            100% { opacity: 0; transform: translateY(-4px); }
        }
        .animate-ticker { animation: fadeInOut 4s ease-in-out infinite; }
    </style>

    <div class="relative min-h-screen w-full text-slate-100 overflow-hidden flex items-center justify-center selection:bg-amber-400 selection:text-slate-950">
        
        <!-- ================= FULL-PAGE BACKGROUND & CONTROLLED DARKENING ================= -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1600&q=80" 
                 alt="Organic Fresh Food"
                 class="w-full h-full object-cover scale-105 filter blur-[6px] opacity-40">
            <!-- Solid deep dark cinematic gradient -->
            <div class="absolute inset-0 bg-[#090d16]/95"></div>
            <div class="absolute inset-0 bg-gradient-to-tr from-emerald-950/20 via-slate-950/90 to-amber-950/20"></div>
        </div>

        <!-- Interactive Dust Particles -->
        <canvas id="particles-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>

        <!-- Ambient Glow Orbs -->
        <div class="absolute top-1/4 left-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-[140px] pointer-events-none z-0"></div>
        <div class="absolute bottom-1/4 right-10 w-96 h-96 bg-amber-500/10 rounded-full blur-[140px] pointer-events-none z-0"></div>

        <!-- MAIN SPLIT CONTAINER -->
        <div class="relative z-10 w-full max-w-7xl mx-auto min-h-screen grid grid-cols-1 lg:grid-cols-12 items-center p-6 lg:p-12 gap-12">
            
            <!-- ================= LEFT COLUMN: HERO & FEATURES ================= -->
            <div class="lg:col-span-7 flex flex-col justify-center space-y-8 lg:pr-4">
                
                <!-- TOP BRANDING HEADER -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-emerald-500/40 flex items-center justify-center text-emerald-400 shadow-2xl">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="px-3.5 py-1 bg-emerald-500/10 border border-emerald-500/30 text-[11px] font-bold uppercase tracking-widest text-emerald-400 rounded-full inline-block mb-1.5">
                                Campus Dining Redefined
                            </span>
                            <h1 class="text-4xl lg:text-5xl font-black text-white tracking-tight">
                                BCI Organic Hub
                            </h1>
                        </div>
                    </div>

                    <p class="text-slate-300 text-base lg:text-lg max-w-xl leading-relaxed font-normal">
                        Order fresh, healthy meals directly from the campus kitchen. Fast online ordering built for students, faculty, and kitchen staff.
                    </p>
                </div>

                <!-- 3 SOLID FEATURE CARDS -->
                <div class="space-y-3.5 max-w-md w-full">
                    
                    <!-- Card 1 -->
                    <div class="animate-float-slow bg-slate-900/90 border border-slate-800 hover:border-emerald-500/40 rounded-2xl p-4 flex items-center space-x-4 shadow-xl transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white tracking-wide">100% Organic</h3>
                            <p class="text-xs text-slate-400 font-medium">Fresh daily ingredients</p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="animate-float-reverse bg-slate-900/90 border border-slate-800 hover:border-amber-500/40 rounded-2xl p-4 flex items-center space-x-4 shadow-xl transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white tracking-wide">Fast Ordering</h3>
                            <p class="text-xs text-slate-400 font-medium">Skip campus queues</p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="animate-float-slow bg-slate-900/90 border border-slate-800 hover:border-rose-500/40 rounded-2xl p-4 flex items-center space-x-4 shadow-xl transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white tracking-wide">Real-time Status</h3>
                            <p class="text-xs text-slate-400 font-medium">Track order updates</p>
                        </div>
                    </div>

                </div>

                <!-- Footer Text -->
                <div class="text-xs text-slate-500 font-medium tracking-wider pt-2">
                    &copy; {{ date('Y') }} BCI ORGANIC HUB. ALL RIGHTS RESERVED.
                </div>

            </div>

            <!-- ================= RIGHT COLUMN: LOGIN PANEL ================= -->
            <div class="lg:col-span-5 flex justify-center items-center w-full">
                
                <div class="relative w-full max-w-md" x-data="{ 
                    step: 'role', 
                    selectedRole: '',
                    tickerIndex: 0,
                    tickerItems: [
                        '⚡ Order #1048 delivered to Faculty Block',
                        '🥗 Fresh Avocado Salad prepared by Kitchen',
                        '🚀 Fast pickup confirmed for Student Hub'
                    ],
                    init() {
                        setInterval(() => {
                            this.tickerIndex = (this.tickerIndex + 1) % this.tickerItems.length;
                        }, 4000);
                    }
                }">

                    <!-- SVG Animated Border Glow -->
                    <svg class="absolute -inset-[2px] w-[calc(100%+4px)] h-[calc(100%+4px)] pointer-events-none z-20" viewBox="0 0 448 480" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="glowGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#10B981" />
                                <stop offset="50%" stop-color="#D97706" />
                                <stop offset="100%" stop-color="#EF4444" />
                            </linearGradient>
                            <filter id="neonGlow" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur stdDeviation="3.5" result="blur" />
                                <feMerge>
                                    <feMergeNode in="blur" />
                                    <feMergeNode in="SourceGraphic" />
                                </feMerge>
                            </filter>
                        </defs>

                        <rect x="1" y="1" width="446" height="478" rx="20" ry="20" fill="none" stroke="#d97706" stroke-opacity="0.3" stroke-width="1.5" />

                        <path d="M 224 1 L 16 1 A 19 19 0 0 0 1 20 L 1 460 A 19 19 0 0 0 20 479 L 224 479"
                              fill="none" stroke="url(#glowGrad)" stroke-width="3" stroke-linecap="round" filter="url(#neonGlow)" pathLength="1000" class="animated-path" />

                        <path d="M 224 1 L 432 1 A 19 19 0 0 1 447 20 L 447 460 A 19 19 0 0 1 432 479 L 224 479"
                              fill="none" stroke="url(#glowGrad)" stroke-width="3" stroke-linecap="round" filter="url(#neonGlow)" pathLength="1000" class="animated-path" />
                    </svg>

                    <!-- Solid Dark Card Body -->
                    <div class="relative z-10 w-full bg-[#0d1322] p-8 rounded-3xl border border-slate-800 shadow-[0_20px_60px_rgba(0,0,0,0.8)] flex flex-col items-center text-white">

                        <!-- NEW ADDITION: Live Order Activity Ticker -->
                        <div class="mb-5 w-full bg-slate-900/80 border border-slate-800 rounded-xl py-2 px-3 text-center overflow-hidden shadow-inner">
                            <span class="text-[11px] font-medium text-amber-400 tracking-wide animate-ticker block" x-text="tickerItems[tickerIndex]"></span>
                        </div>

                        <x-auth-session-status class="mb-4 text-emerald-400 text-sm w-full font-medium" :status="session('status')" />

                        <!-- Step 1: Select Role -->
                        <div x-show="step === 'role'" class="w-full">
                            <h2 class="text-2xl font-black text-white text-center mb-1 tracking-tight">Welcome Back</h2>
                            <p class="text-xs text-amber-400 mb-6 text-center uppercase tracking-widest font-bold">Select account type</p>
                            
                            <div class="grid grid-cols-2 gap-3.5">
                                <button type="button"
                                    @click="step = 'form'; selectedRole = 'customer'; document.getElementById('email').value = 'customer@bci.test'; document.getElementById('password').value = 'password';"
                                    class="cursor-pointer bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-4 rounded-2xl transition-all duration-150 border border-emerald-500/50 shadow-lg active:scale-95 text-center text-sm">
                                    Customer
                                </button>

                                <button type="button"
                                    @click="step = 'form'; selectedRole = 'kitchen'; document.getElementById('email').value = 'kitchen@bci.test'; document.getElementById('password').value = 'password';"
                                    class="cursor-pointer bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold py-4 rounded-2xl transition-all duration-150 border border-amber-400/50 shadow-lg active:scale-95 text-center text-sm">
                                    Kitchen Staff
                                </button>

                                <button type="button"
                                    @click="step = 'form'; selectedRole = 'delivery'; document.getElementById('email').value = 'delivery@bci.test'; document.getElementById('password').value = 'password';"
                                    class="cursor-pointer bg-rose-600 hover:bg-rose-500 text-white font-bold py-4 rounded-2xl transition-all duration-150 border border-rose-500/50 shadow-lg active:scale-95 text-center text-sm">
                                    Delivery
                                </button>

                                <button type="button"
                                    @click="step = 'form'; selectedRole = 'admin'; document.getElementById('email').value = 'admin@bci.test'; document.getElementById('password').value = 'password';"
                                    class="cursor-pointer bg-slate-800 hover:bg-slate-700 text-white font-bold py-4 rounded-2xl transition-all duration-150 border border-slate-700 shadow-lg active:scale-95 text-center text-sm">
                                    Admin
                                </button>
                            </div>

                            <p class="text-xs text-slate-400 mt-6 text-center font-medium">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="text-amber-400 font-bold underline hover:text-amber-300 transition">Register</a>
                            </p>
                        </div>

                        <!-- Step 2: Form -->
                        <div x-show="step === 'form'" x-cloak class="w-full">
                            <button type="button" @click="step = 'role'" class="cursor-pointer text-xs text-amber-400 hover:text-amber-300 mb-4 transition flex items-center gap-1 font-semibold">
                                &larr; Back to role selection
                            </button>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <input type="hidden" name="role" x-model="selectedRole">

                                <div class="mb-4">
                                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Email Address</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com"
                                        class="w-full bg-slate-900 border border-slate-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-500 shadow-inner">
                                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-rose-400 text-xs" />
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Password</label>
                                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                                        class="w-full bg-slate-900 border border-slate-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-500 shadow-inner">
                                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-rose-400 text-xs" />
                                </div>

                                <div class="flex items-center justify-between mb-5 text-sm">
                                    <label class="flex items-center text-slate-300 cursor-pointer select-none">
                                        <input id="remember_me" type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-amber-500 focus:ring-amber-500">
                                        <span class="ml-2 text-xs text-slate-300 font-medium">Remember me</span>
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-amber-400 hover:underline transition duration-150 font-semibold text-xs">
                                            Forgot password?
                                        </a>
                                    @endif
                                </div>

                                <button type="submit" class="cursor-pointer w-full py-4 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold tracking-wider rounded-xl shadow-xl transition duration-200 transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                                    LOG IN
                                </button>
                            </form>

                            <p class="text-xs text-slate-400 mt-5 text-center font-medium">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="text-amber-400 font-bold hover:underline transition ml-1">Register</a>
                            </p>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Floating Particles Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
                for (let i = 0; i < 40; i++) {
                    particles.push({
                        x: Math.random() * width, y: Math.random() * height,
                        radius: Math.random() * 2 + 0.5,
                        vx: (Math.random() - 0.5) * 0.3, vy: (Math.random() - 0.5) * 0.3,
                        alpha: Math.random() * 0.4 + 0.2
                    });
                }
                function animate() {
                    ctx.clearRect(0, 0, width, height);
                    const color = '217, 119, 6';
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