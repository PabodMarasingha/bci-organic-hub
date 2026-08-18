<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\DeliveryZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form with delivery zones.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user'  => $request->user(),
            'zones' => DeliveryZone::all(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Validate extra fields (including driver and customer details)
        $extraData = $request->validate([
            'phone_number'     => ['nullable', 'string', 'max:20'],
            'phone'            => ['nullable', 'string', 'max:20'], // Fallback compatibility
            'address'          => ['nullable', 'string', 'max:500'],
            'delivery_zone_id' => ['nullable', 'exists:delivery_zones,id'],
            'vehicle_type'     => ['nullable', 'string', 'max:100'],
            'vehicle_number'   => ['nullable', 'string', 'max:100'],
            'avatar'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        // Fill standard validated data (name, email)
        $user->fill($request->validated());

        // Fill extra data
        $user->phone_number     = $extraData['phone_number'] ?? $extraData['phone'] ?? $user->phone_number;
        $user->address          = $extraData['address'] ?? $user->address;
        $user->delivery_zone_id = $extraData['delivery_zone_id'] ?? $user->delivery_zone_id;
        $user->vehicle_type     = $extraData['vehicle_type'] ?? $user->vehicle_type;
        $user->vehicle_number   = $extraData['vehicle_number'] ?? $user->vehicle_number;

        // Handle Profile Image Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar in 'public/avatars'
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete user's avatar file from storage if present
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}