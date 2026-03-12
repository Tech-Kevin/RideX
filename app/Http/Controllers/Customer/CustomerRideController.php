<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreRideRequest;
use App\Models\Ride;
use App\Services\RideService;
use App\Models\User;
use App\Enums\RideStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerRideController extends Controller
{
    public function index(): View
    {
        $this->ensureCustomer();

        $rides = Ride::with(['driver'])
            ->where('customer_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.rides.index', compact('rides'));
    }

    public function create(): View
    {
        $this->ensureCustomer();

        $rates = \App\Models\VehicleRate::all()->keyBy('vehicle_type');

        return view('customer.rides.create', compact('rates'));
    }

    public function store(StoreRideRequest $request, RideService $rideService): RedirectResponse
    {
        $this->ensureCustomer();

        $ride = $rideService->createRide($request->validated(), Auth::user());

        return redirect()
            ->route('customer.rides.show', $ride)
            ->with('success', 'Ride booked successfully. Waiting for a driver to accept.');
    }

    public function show(Ride $ride): View
    {
        $this->ensureCustomer();

        abort_unless($ride->customer_id === Auth::id(), 403);

        $ride->load(['customer', 'driver', 'statusLogs.changedByUser']);

        return view('customer.rides.show', compact('ride'));
    }

    private function ensureCustomer(): void
    {
        abort_unless(Auth::check() && Auth::user()->isCustomer(), 403);
    }
    
    public function nearbyDrivers(): JsonResponse
    {
        $this->ensureCustomer();

        // simple proximity mock: all valid drivers with locations.
        // In a real app we'd filter by bounds or distance using Haversine formula
        $drivers = User::where('role', 'driver')
            ->whereNotNull('current_lat')
            ->whereNotNull('current_lng')
            // Optionally: ->where('updated_at', '>=', now()->subMinutes(5)) // only recently active
            ->get(['id', 'current_lat', 'current_lng']);

        return response()->json([
            'drivers' => $drivers
        ]);
    }

    public function driverLocation(Ride $ride): JsonResponse
    {
        $this->ensureCustomer();
        abort_unless($ride->customer_id === Auth::id(), 403);

        $driver = $ride->driver;
        if (!$driver) {
            return response()->json(['error' => 'No driver assigned'], 404);
        }

        return response()->json([
            'lat' => $driver->current_lat,
            'lng' => $driver->current_lng,
        ]);
    }

    public function cancel(Ride $ride, RideService $rideService): RedirectResponse
    {
        $this->ensureCustomer();
        abort_unless($ride->customer_id === Auth::id(), 403);

        if (!in_array($ride->status->value, ['pending', 'accepted'])) {
            return redirect()->back()->with('error', 'This ride cannot be cancelled at its current stage.');
        }

        $rideService->updateRideStatus($ride, RideStatus::CANCELLED, Auth::user());

        return redirect()->route('dashboard')
            ->with('success', 'Ride has been cancelled successfully.');
    }
}