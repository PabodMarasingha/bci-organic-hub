<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
    </style>

    <x-slot name="header">
        <div class="animate-fade-in-up" style="animation-delay: 0ms;">
            <h2 class="font-black text-3xl text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500 tracking-tight">
                Account Settings
            </h2>
            <p class="text-sm text-slate-500 mt-1 font-medium">
                Manage your profile details, security preferences, and account controls
            </p>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/80 min-h-screen relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- 1. Profile Hero & Overview Card --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-950 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden animate-fade-in-up border border-slate-700/50" style="animation-delay: 100ms;">
                {{-- Decorative background glows --}}
                <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute right-1/3 -top-10 w-40 h-40 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
                        {{-- Avatar Circle --}}
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-emerald-400 to-teal-300 p-1 shadow-lg shadow-emerald-500/20">
                                <div class="w-full h-full bg-slate-900 rounded-[22px] flex items-center justify-center font-black text-3xl text-emerald-400 uppercase tracking-widest border border-emerald-500/30">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                            </div>
                            <span class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 border-4 border-slate-900 rounded-full shadow-md" title="Active Account"></span>
                        </div>

                        <div>
                            <div class="flex items-center justify-center sm:justify-start gap-3">
                                <h3 class="text-2xl font-black tracking-wide">{{ Auth::user()->name }}</h3>
                                <span class="text-[10px] font-extrabold uppercase px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full tracking-wider">
                                    {{ Auth::user()->role ?? 'Customer' }}
                                </span>
                            </div>
                            <p class="text-slate-400 text-sm mt-1 font-medium flex items-center justify-center sm:justify-start gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ Auth::user()->email }}
                            </p>
                            <p class="text-xs text-slate-500 mt-2 font-medium">
                                Member since {{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <a href="{{ route('orders.index') }}" class="flex-1 md:flex-none text-center px-6 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-2xl transition-all duration-300 shadow-lg shadow-emerald-500/20 transform hover:-translate-y-0.5">
                            My Orders
                        </a>
                        <a href="{{ route('menu') }}" class="flex-1 md:flex-none text-center px-6 py-3.5 bg-slate-800/80 hover:bg-slate-800 border border-slate-700 text-slate-200 font-extrabold text-xs uppercase tracking-wider rounded-2xl transition-all duration-300">
                            Browse Menu
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. Profile Information Form Card --}}
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] border border-white/60 shadow-xl shadow-slate-200/50 p-6 sm:p-10 animate-fade-in-up" style="animation-delay: 200ms;">
                <div class="max-w-2xl">
                    <section>
                        <header class="mb-6">
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                                <span class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">👤</span>
                                Personal Details
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Update your account's profile details and email address.
                            </p>
                        </header>

                        @include('profile.partials.update-profile-information-form')
                    </section>
                </div>
            </div>

            {{-- 3. Update Password Form Card --}}
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] border border-white/60 shadow-xl shadow-slate-200/50 p-6 sm:p-10 animate-fade-in-up" style="animation-delay: 300ms;">
                <div class="max-w-2xl">
                    <section>
                        <header class="mb-6">
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                                <span class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">🔒</span>
                                Security & Password
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Ensure your account is using a long, random password to stay safe.
                            </p>
                        </header>

                        @include('profile.partials.update-password-form')
                    </section>
                </div>
            </div>

            {{-- 4. Delete Account Danger Zone Card --}}
            <div class="bg-gradient-to-br from-rose-50/50 to-white backdrop-blur-xl rounded-[2rem] border border-rose-100 shadow-xl shadow-rose-100/30 p-6 sm:p-10 animate-fade-in-up" style="animation-delay: 400ms;">
                <div class="max-w-2xl">
                    <section>
                        <header class="mb-6">
                            <h3 class="text-xl font-black text-rose-600 flex items-center gap-3">
                                <span class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-lg">⚠️</span>
                                Danger Zone
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Once your account is deleted, all of its resources and data will be permanently deleted.
                            </p>
                        </header>

                        @include('profile.partials.delete-user-form')
                    </section>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>