<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['kitchen', 'delivery', 'admin']);
        })->with(['roles', 'deliveryZone'])->get();

        $zones = DeliveryZone::all();

        return view('admin.staff', compact('staff', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:kitchen,delivery,admin',
            'delivery_zone_id' => 'nullable|exists:delivery_zones,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'delivery_zone_id' => $request->role === 'delivery' ? $request->delivery_zone_id : null,
        ]);

        $user->assignRole($request->role);

        return back()->with('message', 'Staff account created.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('message', 'Staff account removed.');
    }
}