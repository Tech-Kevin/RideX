<?php

namespace App\Http\Controllers\Driver;

use App\Enums\RideStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\UpdateRideStatusRequest;
use App\Models\Ride;
use App\Models\User;
use App\Services\RideService;
use App\Services\SurgePricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DriverRideController extends Controller
{
    public function __construct(private SurgePricingService $surgeService) {}


    private function ensureDriver(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->isDriver(), 403);
        
        if ($user->verification_status !== 'approved') {
            $msg = $user->verification_status === 'rejected' 
                ? 'Your account has been rejected by admin. Please check your profile.' 
                : 'Your account is pending verification. You will be able to accept rides once approved.';
            
            abort(403, $msg);
        }
    }

    public function available(): View
    {
        $this->ensureDriver();

        $driver = Auth::user();

        $rides = Ride::with('customer')
            ->where('status', RideStatus::PENDING)
            ->where('vehicle_type', $driver->vehicle_type)
            ->latest()
            ->get()
            ->map(function ($ride) use ($driver) {
                $ride->pickup_distance_km = ($driver->current_lat && $driver->current_lng)
                    ? calculateDistance(
                        (float) $driver->current_lat, (float) $driver->current_lng,
                        (float) $ride->pickup_lat,    (float) $ride->pickup_lng
                      )
                    : null;
                return $ride;
            });

        return view('driver.rides.available', compact('rides'));
    }

    public function pollAvailable(): JsonResponse
    {
        $this->ensureDriver();

        $driver = Auth::user();

        $rides = Ride::with('customer')
            ->where('status', RideStatus::PENDING)
            ->where('vehicle_type', $driver->vehicle_type)
            ->latest()
            ->get()
            ->map(function ($ride) use ($driver) {
                $ride->pickup_distance_km = ($driver->current_lat && $driver->current_lng)
                    ? calculateDistance(
                        (float) $driver->current_lat, (float) $driver->current_lng,
                        (float) $ride->pickup_lat,    (float) $ride->pickup_lng
                      )
                    : null;
                return $ride;
            });

        $availableCount = (int) User::where('role', 'driver')
            ->where('driver_status', \App\Enums\DriverStatus::ONLINE_AVAILABLE->value)
            ->count();
        $pendingCount = (int) Ride::where('status', RideStatus::PENDING->value)->count();
        $surge = $this->surgeService->getActiveMultiplier($availableCount, $pendingCount);

        return response()->json([
            'rides'            => $rides,
            'surge_multiplier' => $surge['multiplier'] ?? 1.00,
            'surge_label'      => $surge['multiplier'] > 1 ? "🔥 Surge Active ({$surge['multiplier']}x)" : null,
        ]);
    }

    public function myRides(): View
    {
        $this->ensureDriver();

        $rides = Ride::with('customer')
            ->where('driver_id', Auth::id())
            ->latest()
            ->get();

        return view('driver.rides.my', compact('rides'));
    }

    public function show(Ride $ride): View
    {
        $this->ensureDriver();

        abort_unless(
            $ride->driver_id === Auth::id() || $ride->status === RideStatus::PENDING,
            403
        );

        $ride->load(['customer', 'statusLogs.changedByUser']);

        return view('driver.rides.show', compact('ride'));
    }

    public function accept(Ride $ride, RideService $rideService): RedirectResponse
    {
        $this->ensureDriver();

        try {
            $rideService->acceptRide($ride, Auth::user());
        } catch (\Exception $e) {
            return redirect()
                ->route('driver.rides.available')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('driver.rides.my')
            ->with('success', 'Ride accepted successfully.');
    }

    public function updateStatus(UpdateRideStatusRequest $request, Ride $ride, RideService $rideService): RedirectResponse
    {
        $this->ensureDriver();

        abort_unless($ride->driver_id === Auth::id(), 403);

        try {
            $newStatus = RideStatus::from($request->validated('status'));
            $rideService->updateRideStatus($ride, $newStatus, Auth::user());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Ride status updated successfully.');
    }

    public function acceptAjax(Ride $ride, RideService $rideService): JsonResponse
    {
        $this->ensureDriver();

        try {
            $rideService->acceptRide($ride, Auth::user());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $ride->refresh();
        $statusValue = $ride->status->value;

        return response()->json([
            'status'       => $statusValue,
            'status_label' => rideStatusLabel($ride->status),
            'transitions'  => allowedRideStatusTransitions()[$statusValue] ?? [],
            'is_active'    => in_array($statusValue, ['accepted', 'driver_arriving', 'in_progress']),
        ]);
    }

    public function updateStatusAjax(Ride $ride, RideService $rideService): JsonResponse
    {
        $this->ensureDriver();

        abort_unless($ride->driver_id === Auth::id(), 403);

        $newStatusValue = request()->input('status');

        try {
            $newStatus = RideStatus::from($newStatusValue);
            $rideService->updateRideStatus($ride, $newStatus, Auth::user());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $ride->refresh();
        $statusValue = $ride->status->value;

        return response()->json([
            'status'       => $statusValue,
            'status_label' => rideStatusLabel($ride->status),
            'transitions'  => allowedRideStatusTransitions()[$statusValue] ?? [],
            'is_active'    => in_array($statusValue, ['accepted', 'driver_arriving', 'in_progress']),
        ]);
    }
}