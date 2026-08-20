<x-guest-layout>
    <style>
        @keyframes flowBeam {
            0% { stroke-dashoffset: 1200; }
            100% { stroke-dashoffset: 0; }
        }
        .animated-path {
            stroke-dasharray: 220 980;
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
            <p class="text-[11px] text-organic-charcoal/60 dark:text-slate-400 mt-2 uppercase tracking-[0.2em] font-semibold">Create Your Customer Account</p>
        </div>

        <div class="relative z-10 w-full max-w-md">
            <div class="relative z-10 w-full bg-white/90 dark:bg-[#0b0f19]/90 backdrop-blur-2xl p-8 rounded-2xl border border-organic-green/15 dark:border-slate-800/80 shadow-xl">

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe"
                            class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-organic-tomato text-xs" />
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com"
                            class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-organic-tomato text-xs" />
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                            class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-organic-tomato text-xs" />
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-organic-charcoal/60 dark:text-slate-400 mb-2">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                            class="w-full bg-organic-cream dark:bg-[#141a26] border border-organic-green/20 dark:border-slate-700/70 text-organic-charcoal dark:text-slate-100 text-sm rounded-xl focus:ring-2 focus:ring-organic-green/40 dark:focus:ring-organic-gold/40 focus:border-organic-green dark:focus:border-organic-gold block p-3.5 transition duration-200 outline-none placeholder:text-organic-charcoal/30 dark:placeholder:text-slate-600">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-organic-tomato text-xs" />
                    </div>

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
                particles.forEach(p => {
                    p.x += p.vx; p.y += p.vy;
                    if (p.x < 0) p.x = width; if (p.x > width) p.x = 0;
                    if (p.y < 0) p.y = height; if (p.y > height) p.y = 0;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(31, 77, 58, ${p.alpha})`;
                    ctx.fill();
                });
                requestAnimationFrame(animate);
            }
            animate();
        });
    </script>
</x-guest-layout>