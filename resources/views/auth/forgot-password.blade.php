<x-guest-layout>
    <!-- Custom CSS Animations for Top-to-Bottom Border Glow Beam -->
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
    </style>

    <!-- Main Container with Clean Deep Dark Background -->
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
            <p class="text-[11px] text-slate-400 mt-2 uppercase tracking-[0.2em] font-semibold">Reset Password</p>
        </div>

        <!-- Card Wrapper with SVG Dynamic Border Overlay -->
        <div class="relative z-10 w-full max-w-md">
            
            <!-- Animated SVG Dual Border Light Overlay -->
            <svg class="absolute -inset-[2px] w-[calc(100%+4px)] h-[calc(100%+4px)] pointer-events-none z-20" viewBox="0 0 448 480" preserveAspectRatio="none">
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
                <rect x="1" y="1" width="446" height="478" rx="16" ry="16" fill="none" stroke="#1e293b" stroke-width="1.5" />

                <!-- Left Path: Top Center -> Bottom Center -->
                <path d="M 224 1 L 16 1 A 15 15 0 0 0 1 16 L 1 464 A 15 15 0 0 0 16 479 L 224 479" 
                      fill="none" 
                      stroke="url(#glowGrad)" 
                      stroke-width="3" 
                      stroke-linecap="round"
                      filter="url(#neonGlow)"
                      pathLength="1000"
                      class="animated-path" />

                <!-- Right Path: Top Center -> Bottom Center -->
                <path d="M 224 1 L 432 1 A 15 15 0 0 1 447 16 L 447 464 A 15 15 0 0 1 432 479 L 224 479" 
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
                
                <div class="mb-6 text-xs text-slate-400 leading-relaxed">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </div>

                <!-- Session Status Message (Email Sent Successful Alert) -->
                <x-auth-session-status class="mb-5 text-emerald-400 text-xs font-semibold bg-emerald-950/40 border border-emerald-500/30 p-3 rounded-xl" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-6">
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com" 
                            class="w-full bg-[#141a26] border border-slate-700/70 text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 block p-3.5 transition duration-200 outline-none placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-red-400 text-xs" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold tracking-wider text-xs uppercase rounded-xl shadow-lg shadow-emerald-950/80 transition duration-200 transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        Email Password Reset Link
                    </button>
                </form>

                <!-- Back to Login Link -->
                <p class="text-xs text-slate-400 mt-6 text-center">
                    Remembered password? 
                    <a href="{{ route('login') }}" class="text-emerald-400 font-bold hover:text-emerald-300 hover:underline transition ml-1">Log in</a>
                </p>
            </div>
        </div>
    </div>

    <!-- JavaScript for Floating Glow Dust Particles -->
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