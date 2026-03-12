<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $totalDrivers = User::where('role', 'driver')->count();
        $totalRides = Ride::count();
        $totalRevenue = Ride::where('status', 'completed')->sum('estimated_fare');

        $recentRides = Ride::with(['customer', 'driver'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalDrivers',
            'totalRides',
            'totalRevenue',
            'recentRides'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && in_array($request->role, ['customer', 'driver', 'admin'])) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function rides(Request $request)
    {
        $query = Ride::with(['customer', 'driver'])->orderBy('created_at', 'desc');

        if ($request->has('status') && in_array($request->status, ['pending', 'accepted', 'driver_arriving', 'in_progress', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $rides = $query->paginate(20);
        $currentStatus = $request->status;

        return view('admin.rides', compact('rides', 'currentStatus'));
    }

    public function toggleStatus(User $user)
    {
        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot deactivate your own account.'], 403);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true, 
            'is_active' => $user->is_active,
            'message' => $user->is_active ? 'User activated successfully.' : 'User deactivated successfully.'
        ]);
    }

    public function vehicleRates()
    {
        $rates = \App\Models\VehicleRate::all();
        return view('admin.rates', compact('rates'));
    }

    public function updateVehicleRate(Request $request, \App\Models\VehicleRate $rate)
    {
        $request->validate([
            'base_fare' => 'required|numeric|min:0',
            'rate_per_km' => 'required|numeric|min:0',
        ]);

        $rate->update($request->only('base_fare', 'rate_per_km'));

        return back()->with('success', "Rates for {$rate->vehicle_type->label()} updated successfully.");
    }
    public function verifications()
    {
        $users = User::whereIn('verification_status', ['pending', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.verifications', compact('users'));
    }

    public function verifyUser(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_note' => 'required_if:status,rejected|nullable|string|max:500',
        ]);

        $user->update([
            'verification_status' => $request->status,
            'rejection_note' => $request->status === 'approved' ? null : $request->rejection_note,
        ]);

        return back()->with('success', "User verification {$request->status} successfully.");
    }
}
