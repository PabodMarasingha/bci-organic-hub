<nav x-data="{ open: false }" class="bg-white border-b border-slate-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-green-600" />
                        <span class="font-bold text-lg text-slate-800 tracking-tight hidden md:inline">BCI Organic Hub</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Customer Navigation Links -->
                    @if (auth()->user()->role === 'customer')
                        @if(Route::has('menu'))
                            <x-nav-link :href="route('menu')" :active="request()->routeIs('menu') || request()->routeIs('build')">
                                {{ __('Menu') }}
                            </x-nav-link>
                        @endif

                        @if(Route::has('cart'))
                            <x-nav-link :href="route('cart')" :active="request()->routeIs('cart')">
                                {{ __('Cart') }}
                            </x-nav-link>
                        @endif

                        @if(Route::has('orders.my'))
                            <x-nav-link :href="route('orders.my')" :active="request()->routeIs('orders.my') || request()->routeIs('orders.show')">
                                {{ __('My Orders') }}
                            </x-nav-link>
                        @elseif(Route::has('orders.index'))
                            <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index') || request()->routeIs('orders.show')">
                                {{ __('My Orders') }}
                            </x-nav-link>
                        @endif
                    @endif

                    <!-- Kitchen Navigation Links -->
                    @if (auth()->user()->role === 'kitchen' && Route::has('kitchen.index'))
                        <x-nav-link :href="route('kitchen.index')" :active="request()->routeIs('kitchen.index')">
                            {{ __('Kitchen') }}
                        </x-nav-link>
                    @endif

                    <!-- Delivery Navigation Links -->
                    @if (auth()->user()->role === 'delivery' && Route::has('delivery.index'))
                        <x-nav-link :href="route('delivery.index')" :active="request()->routeIs('delivery.index')">
                            {{ __('Delivery') }}
                        </x-nav-link>
                    @endif

                    <!-- Admin Navigation Links -->
                    @if (auth()->user()->role === 'admin')
                        @if(Route::has('admin.orders'))
                            <x-nav-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">
                                {{ __('Orders') }}
                            </x-nav-link>
                        @endif

                        @if(Route::has('admin.ingredients'))
                            <x-nav-link :href="route('admin.ingredients')" :active="request()->routeIs('admin.ingredients')">
                                {{ __('Ingredients') }}
                            </x-nav-link>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-600 bg-white hover:text-slate-800 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <span class="ms-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-600 uppercase">
                                {{ Auth::user()->role }}
                            </span>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile Button) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (auth()->user()->role === 'customer')
                @if(Route::has('menu'))
                    <x-responsive-nav-link :href="route('menu')" :active="request()->routeIs('menu') || request()->routeIs('build')">
                        {{ __('Menu') }}
                    </x-responsive-nav-link>
                @endif

                @if(Route::has('cart'))
                    <x-responsive-nav-link :href="route('cart')" :active="request()->routeIs('cart')">
                        {{ __('Cart') }}
                    </x-responsive-nav-link>
                @endif

                @if(Route::has('orders.my'))
                    <x-responsive-nav-link :href="route('orders.my')" :active="request()->routeIs('orders.my') || request()->routeIs('orders.show')">
                        {{ __('My Orders') }}
                    </x-responsive-nav-link>
                @elseif(Route::has('orders.index'))
                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index') || request()->routeIs('orders.show')">
                        {{ __('My Orders') }}
                    </x-responsive-nav-link>
                @endif
            @endif

            @if (auth()->user()->role === 'kitchen' && Route::has('kitchen.index'))
                <x-responsive-nav-link :href="route('kitchen.index')" :active="request()->routeIs('kitchen.index')">
                    {{ __('Kitchen') }}
                </x-responsive-nav-link>
            @endif

            @if (auth()->user()->role === 'delivery' && Route::has('delivery.index'))
                <x-responsive-nav-link :href="route('delivery.index')" :active="request()->routeIs('delivery.index')">
                    {{ __('Delivery') }}
                </x-responsive-nav-link>
            @endif

            @if (auth()->user()->role === 'admin')
                @if(Route::has('admin.orders'))
                    <x-responsive-nav-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">
                        {{ __('Orders') }}
                    </x-responsive-nav-link>
                @endif

                @if(Route::has('admin.ingredients'))
                    <x-responsive-nav-link :href="route('admin.ingredients')" :active="request()->routeIs('admin.ingredients')">
                        {{ __('Ingredients') }}
                    </x-responsive-nav-link>
                @endif
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-200">
            <div class="px-4">
                <div class="font-medium text-base text-slate-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>