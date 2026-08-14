<nav x-data="{ open: false }" class="bg-[#0b0f19]/90 backdrop-blur-xl border-b border-slate-800/80 sticky top-0 z-50 transition-all duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- System Logo & Brand Name -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <!-- Custom Organic Hub Icon -->
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <!-- System Name -->
                        <div class="flex flex-col">
                            <span class="text-base font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-green-400">
                                BCI ORGANIC HUB
                            </span>
                            <span class="text-[9px] uppercase tracking-widest text-slate-400 font-bold -mt-1">
                                Healthy Food & Meals
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links with Underline Glow Animations -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    
                    <!-- Dashboard Link -->
                    <a href="{{ route('dashboard') }}" 
                       class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('dashboard') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        Dashboard
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                    </a>

                    <!-- Menu Link -->
                    <a href="{{ Route::has('menu') ? route('menu') : '#' }}" 
                       class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('menu*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        Menu
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('menu*') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                    </a>

                    <!-- Cart Link -->
                    <a href="{{ Route::has('cart') ? route('cart') : '#' }}" 
                       class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('cart*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        Cart
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('cart*') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                    </a>

                    <!-- My Orders Link -->
                    <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" 
                       class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('orders*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        My Orders
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('orders*') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                    </a>

                </div>
            </div>

            <!-- Settings / User Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-xl text-slate-300 bg-[#141a26] border border-slate-800 hover:border-emerald-500/50 hover:text-emerald-400 transition duration-300 focus:outline-none">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="ms-2 -me-0.5 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-[#0b0f19] border border-slate-800 rounded-xl shadow-2xl py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="text-xs text-slate-300 hover:bg-emerald-500/10 hover:text-emerald-400 font-semibold">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-xs text-rose-400 hover:bg-rose-500/10 font-semibold">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button for Mobile View -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-emerald-400 hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#0b0f19] border-b border-slate-800">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('dashboard') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Dashboard</a>
            <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('menu*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Menu</a>
            <a href="{{ Route::has('cart') ? route('cart') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('cart*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Cart</a>
            <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('orders*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">My Orders</a>
        </div>

        <div class="pt-4 pb-2 border-t border-slate-800 px-4">
            <div class="text-xs font-bold text-emerald-400">{{ Auth::user()->name }}</div>
            <div class="text-[10px] text-slate-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:bg-slate-800">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-3 py-2 rounded-lg text-xs font-semibold text-rose-400 hover:bg-rose-500/10">Log Out</a>
                </form>
            </div>
        </div>
    </div>
</nav>