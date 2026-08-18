<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    /**
     * Display a listing of staff and system users.
     */
    public function index()
    {
        // Spatie Roles හෝ DB role column එක පදනම් කරගෙන සියලුම Users ලා ලබා ගැනීම
        $staff = User::with(['roles', 'deliveryZone'])
            ->where('role', '!=', 'customer')
            ->orWhereDoesntHave('roles', function ($q) {
                $q->where('name', 'customer');
            })
            ->latest()
            ->get();

        $roles = Role::whereIn('name', ['kitchen', 'delivery', 'admin', 'customer'])->get();
        $zones = DeliveryZone::where('is_active', true)->get();

        return view('admin.staff', compact('staff', 'roles', 'zones'));
    }

    /**
     * Store a newly created staff account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'phone_number'     => 'nullable|string|max:20',
            'password'         => 'required|string|min:6',
            'role'             => 'required|exists:roles,name',
            'delivery_zone_id' => 'nullable|exists:delivery_zones,id',
            'vehicle_type'     => 'nullable|string|max:100',
            'vehicle_number'   => 'nullable|string|max:100',
        ]);

        $userData = [
            'name'             => $validated['name'],
            'email'            => $validated['email'],
            'phone_number'     => $validated['phone_number'] ?? null,
            'password'         => Hash::make($validated['password']),
            'delivery_zone_id' => $validated['role'] === 'delivery' ? ($validated['delivery_zone_id'] ?? null) : null,
            'vehicle_type'     => $validated['role'] === 'delivery' ? ($validated['vehicle_type'] ?? null) : null,
            'vehicle_number'   => $validated['role'] === 'delivery' ? ($validated['vehicle_number'] ?? null) : null,
        ];

        // DB Table එකේ 'role' column එකක් තිබේ නම් එයටද Save කිරීම
        if (Schema::hasColumn('users', 'role')) {
            $userData['role'] = strtolower($validated['role']);
        }

        $user = User::create($userData);

        // Spatie Permission Role Assign කිරීම
        $user->assignRole($validated['role']);

        return back()->with('success', 'User account created successfully.');
    }

    /**
     * Update the specified staff account.
     */
    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $staff->id,
            'phone_number'     => 'nullable|string|max:20',
            'password'         => 'nullable|string|min:6',
            'role'             => 'required|exists:roles,name',
            'delivery_zone_id' => 'nullable|exists:delivery_zones,id',
            'vehicle_type'     => 'nullable|string|max:100',
            'vehicle_number'   => 'nullable|string|max:100',
        ]);

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        $staff->phone_number = $validated['phone_number'] ?? $staff->phone_number;

        if (!empty($validated['password'])) {
            $staff->password = Hash::make($validated['password']);
        }

        // DB Table එකේ 'role' column එක Update කිරීම
        if (Schema::hasColumn('users', 'role')) {
            $staff->role = strtolower($validated['role']);
        }

        // Delivery details set කිරීම (Role එක 'delivery' නම් පමණි)
        if ($validated['role'] === 'delivery') {
            $staff->delivery_zone_id = $validated['delivery_zone_id'] ?? null;
            $staff->vehicle_type     = $validated['vehicle_type'] ?? null;
            $staff->vehicle_number   = $validated['vehicle_number'] ?? null;
        } else {
            $staff->delivery_zone_id = null;
            $staff->vehicle_type     = null;
            $staff->vehicle_number   = null;
        }

        $staff->save();

        // Spatie Permission Role Sync කිරීම
        $staff->syncRoles([$validated['role']]);

        return back()->with('success', 'User account updated successfully.');
    }

    /**
     * Remove the specified staff account.
     */
    public function destroy(User $staff)
    {
        $staff->delete();
        return back()->with('success', 'User account removed successfully.');
    }
}