<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

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
     * Redirects the user based on their ACTUAL assigned role,
     * regardless of which role button they clicked on the login screen.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->intended(Route::has('admin.dashboard') ? route('admin.dashboard') : route('dashboard'));
        }

        if ($user->hasRole('kitchen') || $user->hasRole('staff')) {
            return redirect()->intended(Route::has('staff.dashboard') ? route('staff.dashboard') : route('dashboard'));
        }

        if ($user->hasRole('delivery')) {
            return redirect()->intended(Route::has('delivery.dashboard') ? route('delivery.dashboard') : route('dashboard'));
        }

        return redirect()->intended(route('dashboard', absolute: false));
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