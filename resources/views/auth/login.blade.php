<x-guest-layout>
    <!-- Custom CSS Animations -->
    <style>
        /* Top-to-Bottom Symmetrical Border Glow Animation */
        @keyframes flowBeam {
            0% { stroke-dashoffset: 1000; }
            100% { stroke-dashoffset: 0; }
        }

        .animated-path {
            stroke-dasharray: 220 780;
            animation: flowBeam 3s linear infinite;
        }

        /* Tech Cyber Rings Rotation */
        @keyframes spinClockwise {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes spinCounterClockwise {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-360deg); }
        }

        .animate-spin-slow {
            animation: spinClockwise 8s linear infinite;
        }

        .animate-spin-reverse {
            animation: spinCounterClockwise 5s linear infinite;
        }
    </style>

    <!-- =========================================================================
         ADVANCED TECH RADAR INTRO SPLASH SCREEN
         ========================================================================= -->
    <div id="intro-screen" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-[#030508] text-white transition-all duration-700 ease-in-out">
        <!-- Background Radial Pulse -->
        <div class="absolute w-[450px] h-[450px] bg-emerald-500/15 rounded-full blur-[140px] pointer-events-none animate-pulse"></div>

        <div class="relative z-10 flex flex-col items-center text-center space-y-8">
            
            <!-- Tech Cyber Ring Loader with Logo in Center -->
            <div class="relative w-36 h-36 flex items-center justify-center">
                
                <!-- Outer Rotating Ring -->
                <div class="absolute inset-0 rounded-full border-2 border-dashed border-emerald-500/40 animate-spin-slow"></div>
                
                <!-- Middle Counter-Rotating Neon Ring -->
                <div class="absolute inset-2 rounded-full border-2 border-t-emerald-400 border-r-transparent border-b-teal-500 border-l-transparent animate-spin-reverse shadow-[0_0_20px_rgba(16,185,129,0.3)]"></div>
                
                <!-- Inner Glowing Circle -->
                <div class="w-20 h-20 bg-emerald-950/60 rounded-2xl border border-emerald-400/50 flex items-center justify-center shadow-[0_0_30px_rgba(16,185,129,0.4)] backdrop-blur-md">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>

            <!-- Title & Dynamic Subtitle -->
            <div class="space-y-2">
                <h1 class="text-3xl sm:text-4xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-green-500 drop-shadow-[0_0_25px_rgba(16,185,129,0.5)]">
                    BCI ORGANIC HUB
                </h1>
                
                <!-- Dynamic Status Message (Changes via JS) -->
                <div class="flex items-center justify-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <p id="loading-text" class="text-xs text-slate-400 uppercase tracking-[0.25em] font-mono">
                        Connecting System...
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         MAIN LOGIN CONTAINER
         ========================================================================= -->
    <div class="relative min-h-screen flex flex-col justify-center items-center bg-[#05070c] px-4 sm:px-6 py-12 overflow-hidden selection:bg-emerald-500 selection:text-white">
        
        <!-- Interactive Floating Dust Particles Canvas Layer -->
        <canvas id="particles-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>

        <!-- Ambient Deep Glows (Soft Background Lighting) -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-emerald-500/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[400px] h-[400px] bg-teal-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <!-- System Title -->
        <div class="relative z-10 mb-8 text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-green-500 drop-shadow-[0_0_25px_rgba(16,185,129,0.35)]">
                BCI Organic Hub
            </h1>
            <p class="text-[11px] text-slate-400 mt-2 uppercase tracking-[0.2em] font-semibold">Management & Ordering System</p>
        </div>

        <!-- Card Wrapper with SVG Dynamic Border Overlay -->
        <div class="relative z-10 w-full max-w-md">
            
            <!-- Animated SVG Dual Border Light Overlay -->
            <svg class="absolute -inset-[2px] w-[calc(100%+4px)] h-[calc(100%+4px)] pointer-events-none z-20" viewBox="0 0 448 556" preserveAspectRatio="none">
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

                <!-- Static Base Frame -->
                <rect x="1" y="1" width="446" height="554" rx="16" ry="16" fill="none" stroke="#1e293b" stroke-width="1.5" />

                <!-- Left Path: Top Center -> Bottom Center -->
                <path d="M 224 1 L 16 1 A 15 15 0 0 0 1 16 L 1 540 A 15 15 0 0 0 16 555 L 224 555" 
                      fill="none" 
                      stroke="url(#glowGrad)" 
                      stroke-width="3" 
                      stroke-linecap="round"
                      filter="url(#neonGlow)"
                      pathLength="1000"
                      class="animated-path" />

                <!-- Right Path: Top Center -> Bottom Center -->
                <path d="M 224 1 L 432 1 A 15 15 0 0 1 447 16 L 447 540 A 15 15 0 0 1 432 555 L 224 555" 
                      fill="none" 
                      stroke="url(#glowGrad)" 
                      stroke-width="3" 
                      stroke-linecap="round"
                      filter="url(#neonGlow)"
                      pathLength="1000"
                      class="animated-path" />
            </svg>

            <!-- Inner Dark Glassmorphism Card -->
            <div class="relative z-10 w-full bg-[#0b0f19]/90 backdrop-blur-2xl p-8 rounded-2xl border border-slate-800/80 shadow-[0_10px_50px_rgba(0,0,0,0.85)]">
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-emerald-400 text-sm" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Select Role Dropdown -->
                    <div class="mb-5">
                        <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Select Role</label>
                        <select id="role" name="role" required class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none">
                            <option value="" disabled selected class="bg-[#0b0f19] text-slate-500">-- Choose Role --</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }} class="bg-[#0b0f19]">Customer</option>
                            <option value="kitchen" {{ old('role') == 'kitchen' ? 'selected' : '' }} class="bg-[#0b0f19]">Kitchen Staff</option>
                            <option value="delivery" {{ old('role') == 'delivery' ? 'selected' : '' }} class="bg-[#0b0f19]">Delivery</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }} class="bg-[#0b0f19]">Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-1.5 text-red-400 text-xs" />
                    </div>

                    <!-- Email Address -->
                    <div class="mb-5">
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com" 
                            class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-red-400 text-xs" />
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" 
                            class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-red-400 text-xs" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6 text-sm">
                        <label class="flex items-center text-slate-400 cursor-pointer select-none">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded bg-[#141a26] border-slate-700 text-emerald-500 focus:ring-emerald-500/50 focus:ring-offset-[#0b0f19]">
                            <span class="ml-2 text-xs text-slate-300 font-medium">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-emerald-400 hover:text-emerald-300 transition duration-150 font-medium text-xs">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold tracking-wider rounded-xl shadow-lg shadow-emerald-950/80 transition duration-200 transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        LOG IN
                    </button>
                </form>

                <!-- Register Link -->
                <p class="text-xs text-slate-400 mt-6 text-center">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-emerald-400 font-bold hover:text-emerald-300 hover:underline transition ml-1">Register</a>
                </p>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         JAVASCRIPT CONTROLLERS
         ========================================================================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // 1. INTRO RADAR LOGIC
            const introScreen = document.getElementById('intro-screen');
            const loadingText = document.getElementById('loading-text');
            
            if (!sessionStorage.getItem('bci_intro_shown')) {
                // Changing text dynamically to feel like a real tech boot
                setTimeout(() => { if(loadingText) loadingText.innerText = 'Loading Modules...'; }, 800);
                setTimeout(() => { if(loadingText) loadingText.innerText = 'System Ready...'; }, 1700);

                // Fade out intro after 2.5 seconds
                setTimeout(() => {
                    introScreen.classList.add('opacity-0', 'pointer-events-none');
                    
                    setTimeout(() => {
                        introScreen.remove();
                    }, 700);

                    sessionStorage.setItem('bci_intro_shown', 'true');
                }, 2500);
            } else {
                introScreen.remove();
            }

            // 2. FLOATING GLOW DUST PARTICLES LOGIC
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