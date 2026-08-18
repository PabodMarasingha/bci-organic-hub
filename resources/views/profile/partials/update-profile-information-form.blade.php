<section>
    <header>
        <h2 class="text-lg font-bold text-slate-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            {{ __("Update your account's profile information, avatar, phone number, and delivery address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Avatar Upload -->
        <div class="flex items-center gap-6 p-4 rounded-2xl bg-slate-900/60 border border-slate-800">
            <div class="relative">
                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=10b981&background=020617' }}" 
                     alt="Avatar" 
                     class="w-20 h-20 rounded-full object-cover border-2 border-emerald-500/50 shadow-md">
            </div>
            <div>
                <x-input-label for="avatar" :value="__('Profile Picture')" class="text-slate-300 font-semibold mb-1" />
                <input id="avatar" name="avatar" type="file" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-500/10 file:text-emerald-400 hover:file:bg-emerald-500/20 cursor-pointer" accept="image/*" />
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-slate-300" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-slate-900 border-slate-800 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-slate-900 border-slate-800 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-400">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-slate-400 hover:text-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone Number -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" class="text-slate-300" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full bg-slate-900 border-slate-800 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" :value="old('phone', $user->phone)" placeholder="+94 7X XXX XXXX" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Delivery Address -->
        <div>
            <x-input-label for="address" :value="__('Delivery Address')" class="text-slate-300" />
            <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-xl bg-slate-900 border-slate-800 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm" placeholder="Enter your full home or office delivery address">{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <x-primary-button class="bg-emerald-500 hover:bg-emerald-600 focus:bg-emerald-600 active:bg-emerald-700 border-none font-bold py-2.5 px-6 rounded-xl">{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-emerald-400 font-semibold"
                >{{ __('Saved successfully.') }}</p>
            @endif
        </div>
    </form>
</section>