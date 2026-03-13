<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DriverStatus;
use App\Enums\RideStatus;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Services\SurgePricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(private SurgePricingService $surgeService) {}

    /* ─── Dashboard ─────────────────────────────────── */
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

    /* ─── Operations Panel ───────────────────────────── */
    public function operations()
    {
        $metrics = $this->buildMetrics();
        return view('admin.operations', compact('metrics'));
    }

    public function operationsMetrics(): JsonResponse
    {
        return response()->json($this->buildMetrics());
    }

    private function buildMetrics(): array
    {
        $drivers = User::where('role', 'driver');

        $totalDrivers     = (clone $drivers)->count();
        $onlineAvailable  = (clone $drivers)->where('driver_status', DriverStatus::ONLINE_AVAILABLE->value)->count();
        $onTrip           = (clone $drivers)->where('driver_status', DriverStatus::ON_TRIP->value)->count();
        $onBreak          = (clone $drivers)->where('driver_status', DriverStatus::ON_BREAK->value)->count();
        $offline          = (clone $drivers)->where('driver_status', DriverStatus::OFFLINE->value)->count();
        $suspended        = (clone $drivers)->where('driver_status', DriverStatus::SUSPENDED->value)->count();

        $activeRiders     = Ride::where('status', RideStatus::PENDING->value)->count();
        $ongoingTrips     = Ride::whereIn('status', [
            RideStatus::ACCEPTED->value,
            RideStatus::DRIVER_ARRIVING->value,
            RideStatus::IN_PROGRESS->value,
        ])->count();
        $completedToday   = Ride::where('status', RideStatus::COMPLETED->value)
            ->whereDate('completed_at', today())
            ->count();

        $ratio = $onlineAvailable > 0
            ? round($activeRiders / $onlineAvailable, 2)
            : ($activeRiders > 0 ? 999 : 0);

        $surge = $this->surgeService->getActiveMultiplier($onlineAvailable, $activeRiders);

        return compact(
            'totalDrivers', 'onlineAvailable', 'onTrip', 'onBreak',
            'offline', 'suspended', 'activeRiders', 'ongoingTrips',
            'completedToday', 'ratio',
        ) + [
            'surge_multiplier' => $surge['multiplier'],
            'surge_rule_name'  => $surge['rule_name'],
            'surge_active'     => $surge['multiplier'] > 1.00,
        ];
    }
}

