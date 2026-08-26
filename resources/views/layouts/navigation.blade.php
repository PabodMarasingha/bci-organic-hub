<nav x-data="{ open: false }" class="bg-[#0b0f19]/90 backdrop-blur-xl border-b border-slate-800/80 sticky top-0 z-50 transition-all duration-300">
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

                        // Dynamic Ingredients Route Identification
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
                        
                        <!-- New Pure SVG Logo -->
                        <div class="relative group-hover:scale-105 transition-transform duration-300">
                            <svg viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 drop-shadow-[0_0_8px_rgba(16,185,129,0.4)] group-hover:drop-shadow-[0_0_12px_rgba(16,185,129,0.9)] transition-all duration-300">
                                <defs>
                                    <linearGradient id="navGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#34d399" />
                                        <stop offset="100%" stop-color="#10b981" />
                                    </linearGradient>
                                </defs>
                                <path d="M60 20 L20 30 L20 75 C20 105 45 125 60 135 C75 125 100 105 100 75 L100 30 Z" stroke="url(#navGrad)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M60 20 C45 5 30 15 50 35 C52 28 55 22 60 20 Z" fill="url(#navGrad)"/>
                                <path d="M60 20 C75 -2 95 10 70 35 C68 28 65 22 60 20 Z" fill="url(#navGrad)"/>
                                <path d="M60 135 L60 100" stroke="url(#navGrad)" stroke-width="4" stroke-linecap="round"/>
                                <path d="M60 120 C45 100 40 85 55 80 C55 90 58 110 60 120 Z" fill="url(#navGrad)"/>
                                <path d="M60 120 C75 100 80 85 65 80 C65 90 62 110 60 120 Z" fill="url(#navGrad)"/>
                                <text x="60" y="78" font-family="Arial, sans-serif" font-weight="900" font-size="34" fill="url(#navGrad)" text-anchor="middle" letter-spacing="1">BCI</text>
                            </svg>
                        </div>

                        <!-- System Name -->
                        <div class="flex flex-col">
                            <span class="text-base font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-green-400 group-hover:brightness-110 transition-all duration-300">
                                BCI ORGANIC HUB
                            </span>
                            <span class="text-[9px] uppercase tracking-widest text-slate-400 font-bold -mt-1 group-hover:text-emerald-400/80 transition-colors duration-300">
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
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('admin.dashboard') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            Admin Overview
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ $ingredientsRoute }}" 
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('*ingredient*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            Ingredients
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('*ingredient*') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('admin.staff.index') ? route('admin.staff.index') : (Route::has('admin.staff') ? route('admin.staff') : '#') }}" 
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('*staff*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            Staff Management
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('*staff*') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>

                    {{-- KITCHEN / STAFF TABS --}}
                    @elseif(Auth::check() && (Auth::user()->hasAnyRole(['kitchen', 'Kitchen Staff', 'staff']) || in_array(Auth::user()->role, ['kitchen', 'staff'])))
                        <a href="{{ Route::has('staff.dashboard') ? route('staff.dashboard') : route('dashboard') }}" 
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('staff.dashboard') || request()->routeIs('kitchen*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            Kitchen Orders
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('staff.dashboard') || request()->routeIs('kitchen*') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>

                    {{-- DELIVERY STAFF TABS --}}
                    @elseif(Auth::check() && (Auth::user()->hasAnyRole(['delivery', 'Delivery Staff']) || in_array(Auth::user()->role, ['delivery', 'delivery staff'])))
                        <a href="{{ Route::has('delivery.dashboard') ? route('delivery.dashboard') : (Route::has('delivery.index') ? route('delivery.index') : '#') }}" 
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('delivery.dashboard') || request()->routeIs('delivery.index') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            Active Dispatches
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('delivery.dashboard') || request()->routeIs('delivery.index') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('delivery.dailyLog') ? route('delivery.dailyLog') : '#' }}" 
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('delivery.dailyLog') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            Daily Log
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('delivery.dailyLog') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>

                    {{-- CUSTOMER / DEFAULT TABS --}}
                    @else
                        <a href="{{ route('dashboard') }}" 
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('dashboard') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            Dashboard
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('menu') ? route('menu') : '#' }}" 
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('menu*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            Menu
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('menu*') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('cart') ? route('cart') : '#' }}" 
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('cart*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            Cart
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('cart*') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                        <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" 
                           class="relative px-4 py-2 text-xs font-bold transition-all duration-300 rounded-xl group {{ request()->routeIs('orders*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            My Orders
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-emerald-400 to-teal-400 transition-all duration-300 {{ request()->routeIs('orders*') ? 'w-3/4 shadow-[0_0_10px_#10b981]' : 'w-0 group-hover:w-1/2' }}"></span>
                        </a>
                    @endif

                </div>
            </div>

            <!-- Settings / User Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-xl text-slate-300 bg-[#141a26] border border-slate-800 hover:border-emerald-500/50 hover:text-emerald-400 transition duration-300 focus:outline-none">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ Auth::user()->name ?? 'User' }}
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

                            <!-- Authentication Logout Form -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-xs text-rose-400 hover:bg-rose-500/10 font-semibold transition duration-150">
                                    {{ __('Log Out') }}
                                </button>
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

    <!-- Responsive Mobile Menu Dynamic by Role -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#0b0f19] border-b border-slate-800">
        <div class="pt-2 pb-3 space-y-1 px-4">
            
            @if(Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->role === 'admin'))
                <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : route('dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Admin Overview</a>
                <a href="{{ $ingredientsRoute }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('*ingredient*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Ingredients</a>
                <a href="{{ Route::has('admin.staff.index') ? route('admin.staff.index') : (Route::has('admin.staff') ? route('admin.staff') : '#') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('*staff*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Staff Management</a>
            
            @elseif(Auth::check() && (Auth::user()->hasAnyRole(['kitchen', 'Kitchen Staff', 'staff']) || in_array(Auth::user()->role, ['kitchen', 'staff'])))
                <a href="{{ Route::has('staff.dashboard') ? route('staff.dashboard') : route('dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('staff.dashboard') || request()->routeIs('kitchen*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Kitchen Orders</a>
            
            @elseif(Auth::check() && (Auth::user()->hasAnyRole(['delivery', 'Delivery Staff']) || in_array(Auth::user()->role, ['delivery', 'delivery staff'])))
                <a href="{{ Route::has('delivery.dashboard') ? route('delivery.dashboard') : (Route::has('delivery.index') ? route('delivery.index') : '#') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('delivery.dashboard') || request()->routeIs('delivery.index') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Active Dispatches</a>
                <a href="{{ Route::has('delivery.dailyLog') ? route('delivery.dailyLog') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('delivery.dailyLog') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Daily Log</a>
            
            @else
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('dashboard') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Dashboard</a>
                <a href="{{ Route::has('menu') ? route('menu') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('menu*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Menu</a>
                <a href="{{ Route::has('cart') ? route('cart') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('cart*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">Cart</a>
                <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('orders*') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-300 hover:text-white' }}">My Orders</a>
            @endif

        </div>

        <div class="pt-4 pb-2 border-t border-slate-800 px-4">
            <div class="text-xs font-bold text-emerald-400">{{ Auth::user()->name ?? 'User' }}</div>
            <div class="text-[10px] text-slate-500">{{ Auth::user()->email ?? '' }}</div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:bg-slate-800">Profile</a>
                
                <!-- Mobile Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full text-left block px-3 py-2 rounded-lg text-xs font-semibold text-rose-400 hover:bg-rose-500/10 transition">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>