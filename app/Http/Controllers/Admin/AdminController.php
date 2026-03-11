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
}
