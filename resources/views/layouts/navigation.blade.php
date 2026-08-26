<nav x-data="{ open: false }" class="bg-white/90 dark:bg-[#0b0f19]/90 backdrop-blur-xl border-b border-organic-green/10 dark:border-slate-800/80 sticky top-0 z-50 transition-all duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">

                <!-- System Logo & Brand Name (Smart Dynamic Role Redirection) -->
                <div class="shrink-0 flex items-center">
                    @php
                        $user = Auth::user();
                        $homeRoute = route('dashboard');

                        if ($user) {
                            if ($user->hasRole('admin') || $user->role === 'admin') {
                                $homeRoute = Route::has('admin.dashboard') ? route('admin.dashboard') : route('dashboard');
                            } elseif ($user->hasAnyRole(['kitchen', 'Kitchen Staff', 'staff']) || in_array($user->role, ['kitchen', 'staff'])) {
                                $homeRoute = Route::has('staff.dashboard') ? route('staff.dashboard') : route('dashboard');
                            } elseif ($user->hasAnyRole(['delivery', 'Delivery Staff']) || in_array($user->role, ['delivery', 'delivery staff'])) {
                                $homeRoute = Route::has('delivery.dashboard') ? route('delivery.dashboard') : (Route::has('delivery.index') ? route('delivery.index') : route('dashboard'));
                            }
                        }

                        $ingredientsRoute = '#';
                        if (Route::has('admin.ingredients')) {
                            $ingredientsRoute = route('admin.ingredients');
                        } elseif (Route::has('admin.ingredients.index')) {
                            $ingredientsRoute = route('admin.ingredients.index');
                        } elseif (Route::has('ingredients.index')) {
                            $ingredientsRoute = route('ingredients.index');
                        } elseif (Route::has('kitchen.index')) {
                            $ingredientsRoute = route('kitchen.index');
                        }
                    @endphp

                    <a href="{{ $homeRoute }}" class="flex items-center gap-3 group" title="Go to Dashboard">
                        <div class="w-10 h-10 rounded-xl bg-organic-green dark:bg-gradient-to-br dark:from-organic-green dark:to-organic-green-light flex items-center justify-center shadow-lg shadow-organic-green/20 group-hover:scale-105 transition duration-300">
                            <svg class="w-5 h-5 text-organic-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-black tracking-wider text-organic-green dark:text-organic-gold">
                                BCI ORGANIC HUB
                            </span>
                            <span class="text-[9px] uppercase tracking-widest text-organic-charcoal/50 dark:text-slate-400 font-bold -mt-1">
                                Healthy Food & Meals
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links Dynamic by User Role -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">

                    {{-- ADMIN TABS --}}
                    @if(Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->role === 'admin'))
                        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : route('dashboard') }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('admin.dashboard') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            Admin Overview
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ $ingredientsRoute }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('*ingredient*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            Ingredients
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('*ingredient*') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('admin.staff.index') ? route('admin.staff.index') : (Route::has('admin.staff') ? route('admin.staff') : '#') }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('*staff*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            Staff Management
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('*staff*') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>

                    {{-- KITCHEN / STAFF TABS --}}
                    @elseif(Auth::check() && (Auth::user()->hasAnyRole(['kitchen', 'Kitchen Staff', 'staff']) || in_array(Auth::user()->role, ['kitchen', 'staff'])))
                        <a href="{{ Route::has('staff.dashboard') ? route('staff.dashboard') : route('dashboard') }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('staff.dashboard') || request()->routeIs('kitchen*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            Kitchen Orders
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('staff.dashboard') || request()->routeIs('kitchen*') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>

                    {{-- DELIVERY STAFF TABS --}}
                    @elseif(Auth::check() && (Auth::user()->hasAnyRole(['delivery', 'Delivery Staff']) || in_array(Auth::user()->role, ['delivery', 'delivery staff'])))
                        <a href="{{ Route::has('delivery.dashboard') ? route('delivery.dashboard') : (Route::has('delivery.index') ? route('delivery.index') : '#') }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('delivery.dashboard') || request()->routeIs('delivery.index') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            Active Dispatches
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('delivery.dashboard') || request()->routeIs('delivery.index') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('delivery.dailyLog') ? route('delivery.dailyLog') : '#' }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('delivery.dailyLog') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            Daily Log
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('delivery.dailyLog') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>

                    {{-- CUSTOMER / DEFAULT TABS --}}
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('dashboard') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            Dashboard
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('dashboard') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('menu') ? route('menu') : '#' }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('menu*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            Menu
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('menu*') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('cart') ? route('cart') : '#' }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('cart*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            Cart
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('cart*') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}"
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('orders*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white hover:bg-organic-green/5 dark:hover:bg-slate-800/50' }}">
                            My Orders
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-organic-gold transition-all duration-300 {{ request()->routeIs('orders*') ? 'w-3/4' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                    @endif

                </div>
            </div>

            <!-- Theme Toggle + Settings / User Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                <!-- Theme Toggle Button -->
                <button id="theme-toggle" type="button" class="p-2.5 rounded-xl bg-organic-cream dark:bg-[#141a26] border border-organic-green/15 dark:border-slate-700/60 text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-organic-gold transition">
                    <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-xl text-organic-charcoal/80 dark:text-slate-300 bg-organic-cream dark:bg-[#141a26] border border-organic-green/15 dark:border-slate-800 hover:border-organic-green/40 dark:hover:border-organic-gold/50 hover:text-organic-green dark:hover:text-organic-gold transition duration-300 focus:outline-none">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-organic-green dark:bg-organic-gold animate-pulse"></span>
                                {{ Auth::user()->name ?? 'User' }}
                            </span>
                            <svg class="ms-2 -me-0.5 h-4 w-4 text-organic-charcoal/40 dark:text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-white dark:bg-[#0b0f19] border border-organic-green/10 dark:border-slate-800 rounded-xl shadow-2xl py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="text-xs text-organic-charcoal/80 dark:text-slate-300 hover:bg-organic-green/5 dark:hover:bg-organic-gold/10 hover:text-organic-green dark:hover:text-organic-gold font-semibold">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-xs text-organic-tomato hover:bg-organic-tomato/10 font-semibold transition duration-150">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button for Mobile View -->
            <div class="-me-2 flex items-center sm:hidden gap-2">
                <button id="theme-toggle-mobile" type="button" class="p-2 rounded-xl text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-organic-gold transition">
                    <svg class="theme-toggle-dark-icon-mobile w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg class="theme-toggle-light-icon-mobile w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                </button>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-organic-charcoal/60 dark:text-slate-400 hover:text-organic-green dark:hover:text-organic-gold hover:bg-organic-green/5 dark:hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu Dynamic by Role -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-[#0b0f19] border-b border-organic-green/10 dark:border-slate-800">
        <div class="pt-2 pb-3 space-y-1 px-4">

            @if(Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->role === 'admin'))
                <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : route('dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">Admin Overview</a>
                <a href="{{ $ingredientsRoute }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('*ingredient*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">Ingredients</a>
                <a href="{{ Route::has('admin.staff.index') ? route('admin.staff.index') : (Route::has('admin.staff') ? route('admin.staff') : '#') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('*staff*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">Staff Management</a>

            @elseif(Auth::check() && (Auth::user()->hasAnyRole(['kitchen', 'Kitchen Staff', 'staff']) || in_array(Auth::user()->role, ['kitchen', 'staff'])))
                <a href="{{ Route::has('staff.dashboard') ? route('staff.dashboard') : route('dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('staff.dashboard') || request()->routeIs('kitchen*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">Kitchen Orders</a>

            @elseif(Auth::check() && (Auth::user()->hasAnyRole(['delivery', 'Delivery Staff']) || in_array(Auth::user()->role, ['delivery', 'delivery staff'])))
                <a href="{{ Route::has('delivery.dashboard') ? route('delivery.dashboard') : (Route::has('delivery.index') ? route('delivery.index') : '#') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('delivery.dashboard') || request()->routeIs('delivery.index') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">Active Dispatches</a>
                <a href="{{ Route::has('delivery.dailyLog') ? route('delivery.dailyLog') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('delivery.dailyLog') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">Daily Log</a>

            @else
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('dashboard') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">Dashboard</a>
                <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('menu*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">Menu</a>
                <a href="{{ Route::has('cart') ? route('cart') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('cart*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">Cart</a>
                <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('orders*') ? 'text-organic-green dark:text-organic-gold bg-organic-green/10 dark:bg-organic-gold/10' : 'text-organic-charcoal/70 dark:text-slate-300 hover:text-organic-green dark:hover:text-white' }}">My Orders</a>
            @endif

        </div>

        <div class="pt-4 pb-2 border-t border-organic-green/10 dark:border-slate-800 px-4">
            <div class="text-xs font-bold text-organic-green dark:text-organic-gold">{{ Auth::user()->name ?? 'User' }}</div>
            <div class="text-[10px] text-organic-charcoal/50 dark:text-slate-500">{{ Auth::user()->email ?? '' }}</div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-organic-charcoal/80 dark:text-slate-300 hover:bg-organic-green/5 dark:hover:bg-slate-800">Profile</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left block px-3 py-2 rounded-lg text-xs font-semibold text-organic-tomato hover:bg-organic-tomato/10 transition">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    function applyThemeIcons() {
        const isDark = document.documentElement.classList.contains('dark');
        document.querySelectorAll('#theme-toggle-dark-icon, .theme-toggle-dark-icon-mobile').forEach(el => el.classList.toggle('hidden', !isDark));
        document.querySelectorAll('#theme-toggle-light-icon, .theme-toggle-light-icon-mobile').forEach(el => el.classList.toggle('hidden', isDark));
    }

    function toggleTheme() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('color-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        applyThemeIcons();
    }

    document.addEventListener('DOMContentLoaded', () => {
        applyThemeIcons();
        document.getElementById('theme-toggle')?.addEventListener('click', toggleTheme);
        document.getElementById('theme-toggle-mobile')?.addEventListener('click', toggleTheme);
    });
</script>