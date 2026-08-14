<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Customer Portal - BCI Organic Hub' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark/Light Mode Switch Script -->
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-100 dark:bg-[#07090e] text-slate-800 dark:text-slate-100 min-h-screen transition-colors duration-300">

    <div class="flex min-h-screen">

        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white dark:bg-[#0b0f19] border-r border-slate-200 dark:border-slate-800/80 flex flex-col justify-between hidden md:flex transition-colors duration-300">
            <div>
                <!-- Brand Header -->
                <div class="p-6 border-b border-slate-200 dark:border-slate-800/80 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white font-bold shadow-lg shadow-emerald-500/20">
                        O
                    </div>
                    <div>
                        <h2 class="font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-400 text-lg">BCI Hub</h2>
                        <span class="text-[10px] uppercase font-semibold text-slate-400 tracking-wider">Customer Portal</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 text-sm">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-semibold transition">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        Dashboard
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-emerald-500 transition">
                        <i data-lucide="utensils" class="w-5 h-5"></i>
                        Organic Menu / Order
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-emerald-500 transition">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        My Orders History
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-emerald-500 transition">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        Delivery Tracking
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-emerald-500 transition">
                        <i data-lucide="heart" class="w-5 h-5"></i>
                        Favorite Items
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-emerald-500 transition">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        Profile Settings
                    </a>
                </nav>
            </div>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-slate-200 dark:border-slate-800/80">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-500/10 font-semibold transition">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">

            <!-- Top Navbar -->
            <header class="h-20 bg-white/80 dark:bg-[#0b0f19]/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800/80 px-6 flex items-center justify-between sticky top-0 z-30 transition-colors duration-300">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Welcome Back, {{ Auth::user()->name ?? 'Customer' }}!</h1>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Dark / Light Mode Switch Toggle Button -->
                    <button id="theme-toggle" class="p-2.5 rounded-xl bg-slate-100 dark:bg-[#141a26] border border-slate-200 dark:border-slate-700/60 text-slate-600 dark:text-slate-300 hover:text-emerald-500 dark:hover:text-emerald-400 transition">
                        <i id="theme-toggle-dark-icon" data-lucide="moon" class="w-5 h-5 hidden"></i>
                        <i id="theme-toggle-light-icon" data-lucide="sun" class="w-5 h-5 hidden"></i>
                    </button>

                    <!-- Cart Icon Button -->
                    <a href="#" class="relative p-2.5 rounded-xl bg-slate-100 dark:bg-[#141a26] border border-slate-200 dark:border-slate-700/60 text-slate-600 dark:text-slate-300 hover:text-emerald-500 dark:hover:text-emerald-400 transition">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 text-white text-[10px] font-bold flex items-center justify-center">0</span>
                    </a>
                </div>
            </header>

            <!-- Page Body Content -->
            <main class="p-6 flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Script for Lucide Icons & Theme Switch Toggle -->
    <script>
        lucide.createIcons();

        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        if (document.documentElement.classList.contains('dark')) {
            lightIcon.classList.remove('hidden');
        } else {
            darkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', () => {
            darkIcon.classList.toggle('hidden');
            lightIcon.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>
</body>
</html>