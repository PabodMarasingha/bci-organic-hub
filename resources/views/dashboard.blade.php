<x-app-layout>
    <div class="min-h-screen bg-[#05070c] flex flex-col items-center justify-center text-slate-100">
        <div class="flex flex-col items-center space-y-6">
            
            <!-- BCI Organic Hub SVG Loader with Spinning Rings -->
            <div class="relative flex items-center justify-center w-28 h-28">
                <!-- Outer Spinning Dashed Ring -->
                <div class="absolute inset-0 rounded-full border-2 border-dashed border-emerald-500/30 animate-[spin_3s_linear_infinite]"></div>
                <!-- Inner Fast Reverse Spinning Ring -->
                <div class="absolute inset-2 rounded-full border-t-2 border-emerald-400 border-l-2 border-transparent animate-[spin_1.5s_linear_infinite_reverse] drop-shadow-[0_0_8px_rgba(52,211,153,0.5)]"></div>
                
                <!-- Pure SVG Logo (Pulsing in the center) -->
                <div class="animate-pulse">
                    <svg viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 drop-shadow-[0_0_12px_rgba(16,185,129,0.7)]">
                        <defs>
                            <linearGradient id="bciGradRedirect" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#34d399" />
                                <stop offset="100%" stop-color="#10b981" />
                            </linearGradient>
                        </defs>
                        <path d="M60 20 L20 30 L20 75 C20 105 45 125 60 135 C75 125 100 105 100 75 L100 30 Z" stroke="url(#bciGradRedirect)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M60 20 C45 5 30 15 50 35 C52 28 55 22 60 20 Z" fill="url(#bciGradRedirect)"/>
                        <path d="M60 20 C75 -2 95 10 70 35 C68 28 65 22 60 20 Z" fill="url(#bciGradRedirect)"/>
                        <path d="M60 135 L60 100" stroke="url(#bciGradRedirect)" stroke-width="4" stroke-linecap="round"/>
                        <path d="M60 120 C45 100 40 85 55 80 C55 90 58 110 60 120 Z" fill="url(#bciGradRedirect)"/>
                        <path d="M60 120 C75 100 80 85 65 80 C65 90 62 110 60 120 Z" fill="url(#bciGradRedirect)"/>
                        <text x="60" y="78" font-family="Arial, sans-serif" font-weight="900" font-size="34" fill="url(#bciGradRedirect)" text-anchor="middle" letter-spacing="1">BCI</text>
                    </svg>
                </div>
            </div>

            <!-- Loading Text -->
            <div class="flex flex-col items-center text-center">
                <h2 class="text-xl font-bold tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300 uppercase mb-1 drop-shadow-md">
                    BCI Organic Hub
                </h2>
                <div class="flex items-center space-x-2 mt-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <p class="text-[10px] font-semibold text-emerald-500/80 tracking-[0.2em] uppercase animate-pulse">
                        Redirecting to your portal...
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Fallback for Instant Smart Redirect -->
    <script>
        window.location.reload();
    </script>
</x-app-layout>