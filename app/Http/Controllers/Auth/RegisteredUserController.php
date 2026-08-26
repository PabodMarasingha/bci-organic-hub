<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DeliveryZone; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $deliveryZones = class_exists(DeliveryZone::class) ? DeliveryZone::all() : collect();

        return view('auth.register', compact('deliveryZones'));
    }

    /**
     * Handle an incoming registration request.
     * Public registration ALWAYS creates a customer account.
     * Staff (kitchen, delivery, admin) accounts can only be created
     * by an existing admin via the Staff Management panel.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $isDelivery = in_array($request->role, ['delivery', 'Delivery Staff']);

        $deliveryZoneId = $request->delivery_zone_id;

        if ($isDelivery && !empty($deliveryZoneId) && !is_numeric($deliveryZoneId) && class_exists(DeliveryZone::class)) {
            $newZone = DeliveryZone::firstOrCreate([
                'name' => $deliveryZoneId
            ]);
            $deliveryZoneId = $newZone->id;
        }

        $user = User::query()->create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'phone_number'     => $isDelivery ? $request->phone_number : null,
            'delivery_zone_id' => $isDelivery ? $deliveryZoneId : null,
            'vehicle_type'     => $isDelivery ? $request->vehicle_type : null,
            'vehicle_number'   => $isDelivery ? $request->vehicle_number : null,
        ]);

        // Hardcoded to 'customer' — never trust a role value from the request.
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('customer');
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}