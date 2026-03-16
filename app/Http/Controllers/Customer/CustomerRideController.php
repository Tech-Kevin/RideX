<?php

namespace App\Http\Controllers\Customer;

use App\Enums\DriverStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreRideRequest;
use App\Models\Ride;
use App\Models\VehicleRate;
use App\Services\RideService;
use App\Models\User;
use App\Enums\RideStatus;
use App\Services\SurgePricingService;
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

    public function create(SurgePricingService $surgeService): View
    {
        $this->ensureCustomer();

        $rates = VehicleRate::all()->keyBy('vehicle_type');

        $availableDrivers = User::where('role', 'driver')
            ->where('driver_status', DriverStatus::ONLINE_AVAILABLE->value)
            ->count();
        $activeRiders = Ride::whereIn('status', [RideStatus::PENDING->value])->count();
        $surge = $surgeService->getActiveMultiplier($availableDrivers, $activeRiders);
        $surgeMultiplier = $surge['multiplier'];

        return view('customer.rides.create', compact('rates', 'surgeMultiplier'));
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

    public function rideStatus(Ride $ride): JsonResponse
    {
        $this->ensureCustomer();
        abort_unless($ride->customer_id === Auth::id(), 403);

        $ride->load(['driver', 'statusLogs.changedByUser']);

        $driver = null;
        if ($ride->driver) {
            $driver = [
                'name'  => $ride->driver->name,
                'phone' => $ride->driver->phone,
                'initial' => strtoupper(substr($ride->driver->name, 0, 1)),
            ];
        }

        $statusLogs = $ride->statusLogs->map(function ($log) {
            return [
                'status'    => rideStatusLabel($log->status),
                'by'        => $log->changedByUser?->name ?? 'System Automated',
                'remarks'   => $log->remarks ?? '-',
                'timestamp' => $log->created_at->format('H:i A'),
            ];
        });

        $statusValue = $ride->status->value;

        return response()->json([
            'status'         => $statusValue,
            'status_label'   => rideStatusLabel($ride->status),
            'driver'         => $driver,
            'status_logs'    => $statusLogs,
            'is_driver_active' => in_array($statusValue, ['accepted', 'driver_arriving', 'in_progress']) && $ride->driver_id,
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