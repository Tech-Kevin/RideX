<?php

namespace App\Http\Controllers\Driver;

use App\Enums\RideStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\UpdateRideStatusRequest;
use App\Models\Ride;
use App\Services\RideService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DriverRideController extends Controller
{
    private function ensureDriver(): void
    {
        abort_unless(Auth::check() && Auth::user()->isDriver(), 403);
    }

    public function available(): View
    {
        $this->ensureDriver();

        $rides = Ride::with('customer')
            ->where('status', RideStatus::PENDING)
            ->latest()
            ->get();

        return view('driver.rides.available', compact('rides'));
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