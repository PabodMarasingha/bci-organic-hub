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
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        $selectedRole = strtolower(trim($request->role ?? ''));
        $dbRole = strtolower($user->role ?? '');
        $userSpatieRoles = method_exists($user, 'getRoleNames') 
            ? $user->getRoleNames()->map(fn($r) => strtolower($r))->toArray() 
            : [];

        $hasMatchingRole = false;

        if (empty($selectedRole) || in_array($selectedRole, $userSpatieRoles) || $dbRole === $selectedRole) {
            $hasMatchingRole = true;
        } 
        elseif (in_array($selectedRole, ['delivery', 'delivery staff']) && 
               (array_intersect(['delivery', 'delivery staff'], $userSpatieRoles) || in_array($dbRole, ['delivery', 'delivery staff']))) {
            $hasMatchingRole = true;
        } 
        elseif (in_array($selectedRole, ['kitchen', 'staff']) && 
               (array_intersect(['kitchen', 'staff'], $userSpatieRoles) || in_array($dbRole, ['kitchen', 'staff']))) {
            $hasMatchingRole = true;
        }

        if (!$hasMatchingRole) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'role' => 'The selected role does not match this account.',
            ])->onlyInput('email');
        }

        if ($user->hasRole('admin') || $dbRole === 'admin') {
            return redirect()->intended(Route::has('admin.dashboard') ? route('admin.dashboard') : route('dashboard'));
        }

        if ($user->hasRole('kitchen') || $user->hasRole('staff') || in_array($dbRole, ['kitchen', 'staff'])) {
            return redirect()->intended(Route::has('staff.dashboard') ? route('staff.dashboard') : route('dashboard'));
        }

        if ($user->hasRole('delivery') || $user->hasRole('Delivery Staff') || in_array($dbRole, ['delivery', 'delivery staff'])) {
            return redirect()->intended(Route::has('delivery.dashboard') ? route('delivery.dashboard') : route('dashboard'));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}