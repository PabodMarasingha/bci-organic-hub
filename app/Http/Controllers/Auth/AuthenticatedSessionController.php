<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Quick Auto-Login Method (For quick role selection)
     */
    public function quickLogin(Request $request, string $role): RedirectResponse
    {
        // Database එකේ අදාළ Role එක තියෙන පළමු User ව සෙවීම
        $user = User::where('role', $role)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => "Database එකේ {$role} role එක සහිත User කෙනෙක් හමු වූයේ නැත.",
            ]);
        }

        // Direct Login වී Session එක Regenerate කිරීම
        Auth::login($user);
        $request->session()->regenerate();

        // Role එක අනුව Redirect කිරීම
        return match ($user->role) {
            'admin'    => redirect()->intended(route('admin.orders')),
            'kitchen'  => redirect()->intended(route('kitchen.index')),
            'delivery' => redirect()->intended(route('delivery.index')),
            default    => redirect()->intended(route('dashboard')),
        };
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Authenticate credentials (Email & Password)
        $request->authenticate();

        $user = Auth::user();

        // 2. Form එකෙන් එවන Role එක User ගේ DB Role එකට Match වෙනවාදැයි පරීක්ෂා කිරීම
        if ($request->filled('role') && $user->role !== $request->role) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Selected role does not match this user account.',
            ])->onlyInput('email');
        }

        // 3. Regenerate session upon successful login
        $request->session()->regenerate();

        // 4. Role එක අනුව අදාළ Dashboard එකට Redirect කිරීම
        return match ($user->role) {
            'admin'    => redirect()->intended(route('admin.orders')),
            'kitchen'  => redirect()->intended(route('kitchen.index')),
            'delivery' => redirect()->intended(route('delivery.index')),
            default    => redirect()->intended(route('dashboard')),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}