<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Form එකෙන් එන Request එක (Role, Email, Password) Validate & Authenticate කිරීම
        $request->validate([
            'role' => ['required', 'string'],
        ]);

        $request->authenticate();

        $user = Auth::user();

        // 2. Select කරපු Role එක DB එකේ user ගේ සැබෑ Role එකට සමානදැයි බලයි
        if ($request->filled('role') && $user->role !== $request->role) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'role' => 'The selected role does not match this account.',
            ])->onlyInput('email');
        }

        // 3. Session Regenerate කිරීම
        $request->session()->regenerate();

        // 4. User ගේ Role එක අනුව අදාළ Dashboard එකට Redirect කිරීම
        return match ($user->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'kitchen'  => redirect()->route('kitchen.dashboard'),
            'delivery' => redirect()->route('delivery.dashboard'),
            default    => redirect()->intended(route('dashboard', absolute: false)),
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