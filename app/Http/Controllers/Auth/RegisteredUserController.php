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
        // 1. Role එකත් එක්ක Form Validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:customer,kitchen,delivery,admin'], // Role validation
        ]);

        // 2. Database එකේ Role එකත් එක්ක User හදාගැනීම
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Selected Role එක save කිරීම
        ]);

        event(new Registered($user));

        Auth::login($user);

        // 3. Select කරපු Role එක අනුව අදාළ Page එකට Redirect කිරීම
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.ingredients.index');
            case 'kitchen':
                return redirect()->route('kitchen.index');
            case 'delivery':
                return redirect()->route('delivery.index');
            case 'customer':
            default:
                return redirect()->route('dashboard');
        }
    }
}