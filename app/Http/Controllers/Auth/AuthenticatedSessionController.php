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
    
    public function create(): View
    {
        return view('auth.login');
    }

    
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Request Validation
        $request->validate([
            'role' => ['required', 'string'],
        ]);

        $request->authenticate();

        /** @var User $user */
        $user = Auth::user();

       
        $selectedRole = strtolower(trim($request->role));
        
        
        $dbRole = strtolower($user->role ?? '');
        $userSpatieRoles = method_exists($user, 'getRoleNames') 
            ? $user->getRoleNames()->map(fn($r) => strtolower($r))->toArray() 
            : [];

        
        $hasMatchingRole = false;

       
        if (in_array($selectedRole, $userSpatieRoles) || $dbRole === $selectedRole) {
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

        
        $request->session()->regenerate();

       
        if ($user->hasRole('admin') || $dbRole === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('kitchen') || $user->hasRole('staff') || in_array($dbRole, ['kitchen', 'staff'])) {
            return redirect()->route('staff.dashboard');
        }

        if ($user->hasRole('delivery') || $user->hasRole('Delivery Staff') || in_array($dbRole, ['delivery', 'delivery staff'])) {
            return redirect()->route('delivery.dashboard');
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