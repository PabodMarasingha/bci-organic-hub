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
use Spatie\Permission\Models\Role;

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
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
            'role'             => ['required', 'string', 'in:customer,staff,kitchen,delivery,Delivery Staff,admin'],
            
            // Dynamic Delivery Validation Rules
            'phone_number'     => ['nullable', 'required_if:role,delivery', 'string', 'max:20'],
            'delivery_zone_id' => ['nullable', 'required_if:role,delivery'],
            'vehicle_type'     => ['nullable', 'required_if:role,delivery', 'string', 'max:100'],
            'vehicle_number'   => ['nullable', 'required_if:role,delivery', 'string', 'max:50'],
        ]);

        $isDelivery = in_array($request->role, ['delivery', 'Delivery Staff']);

        // === අලුතින් Type කළ Zone එකක් නම් එය Database එකට Save කර ID එක ලබාගැනීම ===
        $deliveryZoneId = $request->delivery_zone_id;

        if ($isDelivery && !empty($deliveryZoneId) && !is_numeric($deliveryZoneId)) {
            $newZone = DeliveryZone::firstOrCreate([
                'name' => $deliveryZoneId
            ]);
            $deliveryZoneId = $newZone->id; // හැදුණු අලුත් Zone එකේ ID එක මෙතැනින් ගනී
        }
        // ==============================================================================

        // 2. User Creation
        $user = User::query()->create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'phone_number'     => $isDelivery ? $request->phone_number : null,
            'delivery_zone_id' => $isDelivery ? $deliveryZoneId : null, // <-- නිවැරදි ID එක මෙතැනට ලබාදේ
            'vehicle_type'     => $isDelivery ? $request->vehicle_type : null,
            'vehicle_number'   => $isDelivery ? $request->vehicle_number : null,
        ]);

        if ($user instanceof User) {
            // 3. Assign Role via Spatie
            $role = Role::firstOrCreate([
                'name'       => $request->role,
                'guard_name' => 'web'
            ]);
            
            $user->assignRole($role);

            event(new Registered($user));

            Auth::login($user);

            // 4. Role-based Redirects
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->hasRole('staff') || $user->hasRole('kitchen')) {
                return redirect()->route('staff.dashboard');
            }

            if ($user->hasRole('delivery') || $user->hasRole('Delivery Staff')) {
                return redirect()->route('delivery.dashboard');
            }
        }

        // Default: Customer Dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }
}