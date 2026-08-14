<x-app-layout>
    <div class="min-h-screen bg-[#0b1329] text-slate-100 py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">

            <!-- Top Header Card with Profile Photo Upload -->
            <div class="relative bg-slate-900/90 border border-slate-800 rounded-3xl p-8 text-center shadow-2xl backdrop-blur-md overflow-hidden">
                <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <form id="profile-photo-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="relative z-10 flex flex-col items-center">
                    @csrf
                    @method('PATCH')

                    <!-- Hidden Inputs for required validation -->
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">

                    <!-- Avatar with Hover Camera Icon -->
                    <div class="relative group cursor-pointer mb-4">
                        <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full blur opacity-40 group-hover:opacity-100 transition duration-300"></div>
                        
                        <img id="avatar-preview" 
                             src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=10b981&background=020617&size=128' }}" 
                             alt="Profile Photo" 
                             class="relative w-32 h-32 rounded-full object-cover border-4 border-slate-900 shadow-2xl transition duration-300 group-hover:scale-105">

                        <label for="avatar-input" class="absolute inset-0 bg-slate-950/70 rounded-full flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 cursor-pointer text-white">
                            <svg class="w-8 h-8 text-emerald-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l0.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l0.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-[11px] font-semibold text-emerald-300">Change Photo</span>
                        </label>

                        <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="previewAndSubmitAvatar(this)">
                    </div>

                    <span class="inline-block px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold rounded-full uppercase tracking-wider mb-2">
                        Customer Profile
                    </span>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ auth()->user()->name }}</h1>
                    <p class="text-slate-400 text-sm font-medium mt-0.5">{{ auth()->user()->email }}</p>
                </form>
            </div>

            <!-- Personal Details Form -->
            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Update Password Form -->
            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Delete Account Card -->
            <div class="bg-slate-900/90 border border-red-500/20 rounded-3xl p-6 sm:p-8 shadow-xl">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>

    <script>
        function previewAndSubmitAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
                document.getElementById('profile-photo-form').submit();
            }
        }
    </script>
</x-app-layout>