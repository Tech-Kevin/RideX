<?php

namespace App\Http\Controllers\Driver;

use App\Enums\RideStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\UpdateRideStatusRequest;
use App\Models\Ride;
use App\Services\RideService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DriverRideController extends Controller
{
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

        $rides = Ride::with('customer')
            ->where('status', RideStatus::PENDING)
            ->where('vehicle_type', Auth::user()->vehicle_type)
            ->latest()
            ->get();

        return view('driver.rides.available', compact('rides'));
    }

    public function pollAvailable(): JsonResponse
    {
        $this->ensureDriver();

        $rides = Ride::with('customer')
            ->where('status', RideStatus::PENDING)
            ->where('vehicle_type', Auth::user()->vehicle_type)
            ->latest()
            ->get();

        return response()->json([
            'rides' => $rides
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
}