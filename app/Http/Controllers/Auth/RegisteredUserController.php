<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate incoming registration request including role
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'string', 'in:customer,kitchen,delivery,admin'],
        ]);

        // 2. Create new user record with selected role
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // 3. Assign Spatie Role (For Spatie\Permission package consistency)
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($request->role);
        }

        event(new Registered($user));

        Auth::login($user);

        // 4. Redirect user to corresponding dashboard based on assigned role
        return match ($user->role) {
            'admin'    => redirect()->route('admin.orders'),
            'kitchen'  => redirect()->route('kitchen.index'),
            'delivery' => redirect()->route('delivery.index'),
            default    => redirect()->route('dashboard'), // Customer
        };
    }
}