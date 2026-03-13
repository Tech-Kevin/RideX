<?php

namespace App\Services;

use App\Enums\DriverStatus;
use App\Enums\RideStatus;
use App\Events\RideAccepted;
use App\Events\RideRequested;
use App\Events\RideStatusUpdated;
use App\Jobs\SendSmsJob;
use App\Models\Ride;
use App\Models\RideStatusLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RideService
{
    public function __construct(private SurgePricingService $surgeService) {}

    public function createRide(array $data, User $customer): Ride
    {
        $distance = calculateDistance(
            (float) $data['pickup_lat'],
            (float) $data['pickup_lng'],
            (float) $data['drop_lat'],
            (float) $data['drop_lng']
        );

        $baseFare = calculateFare($distance, $data['vehicle_type']);

        // Compute live supply/demand for demand-based surge evaluation
        $availableDrivers = User::where('role', 'driver')
            ->where('driver_status', DriverStatus::ONLINE_AVAILABLE->value)
            ->count();
        $activeRiders = Ride::whereIn('status', [RideStatus::PENDING->value])->count();

        $surge      = $this->surgeService->getActiveMultiplier($availableDrivers, $activeRiders);
        $finalFare  = round($baseFare * $surge['multiplier'], 2);

        $ride = DB::transaction(function () use ($data, $distance, $finalFare, $surge, $customer) {
            $ride = Ride::create([
                'customer_id'     => $customer->id,
                'vehicle_type'    => $data['vehicle_type'],
                'pickup_address'  => $data['pickup_address'],
                'pickup_lat'      => $data['pickup_lat'],
                'pickup_lng'      => $data['pickup_lng'],
                'drop_address'    => $data['drop_address'],
                'drop_lat'        => $data['drop_lat'],
                'drop_lng'        => $data['drop_lng'],
                'distance_km'     => $distance,
                'estimated_fare'  => $finalFare,
                'surge_multiplier'=> $surge['multiplier'],
                'surge_rule_name' => $surge['rule_name'],
                'status'          => RideStatus::PENDING,
            ]);

            RideStatusLog::create([
                'ride_id'    => $ride->id,
                'changed_by' => $customer->id,
                'status'     => RideStatus::PENDING->value,
                'remarks'    => 'Ride requested by customer.' . ($surge['multiplier'] > 1 ? " Surge: {$surge['multiplier']}x ({$surge['rule_name']})." : ''),
            ]);

            return $ride;
        });

        $ride->load('customer');

        SendSmsJob::dispatch(
            $customer->phone,
            buildRideBookedMessage($ride->id, (float) $ride->estimated_fare),
            'ride_booked'
        );

        $drivers = User::where('role', 'driver')
            ->where('is_phone_verified', true)
            ->where('vehicle_type', $ride->vehicle_type)
            ->get();

        foreach ($drivers as $driver) {
            SendSmsJob::dispatch(
                $driver->phone,
                "New ride available. Pickup: {$ride->pickup_address}. Ride ID: {$ride->id}.",
                'ride_request'
            );
        }

        broadcast(new RideRequested($ride));

        return $ride;
    }

    public function acceptRide(Ride $ride, User $driver): Ride
    {
        DB::transaction(function () use ($ride, $driver) {
            $lockedRide = Ride::where('id', $ride->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRide->status !== RideStatus::PENDING || $lockedRide->driver_id !== null) {
                throw new \Exception('This ride was already accepted by another driver.');
            }

            if ($lockedRide->vehicle_type !== $driver->vehicle_type) {
                throw new \Exception("Your vehicle type ({$driver->vehicle_type->label()}) does not match this ride's requirement ({$lockedRide->vehicle_type->label()}).");
            }

            $lockedRide->update([
                'driver_id'   => $driver->id,
                'status'      => RideStatus::ACCEPTED,
                'accepted_at' => now(),
            ]);

            // Update driver operational status
            $driver->update(['driver_status' => DriverStatus::ON_TRIP]);

            RideStatusLog::create([
                'ride_id'    => $lockedRide->id,
                'changed_by' => $driver->id,
                'status'     => RideStatus::ACCEPTED->value,
                'remarks'    => 'Ride accepted by driver.',
            ]);
        });

        $ride->refresh();
        $ride->load(['customer', 'driver']);

        SendSmsJob::dispatch(
            $ride->customer->phone,
            buildRideStatusMessage($ride->id, RideStatus::ACCEPTED->value),
            'ride_status'
        );

        broadcast(new RideAccepted($ride));

        return $ride;
    }

    public function updateRideStatus(Ride $ride, RideStatus $newStatus, User $driver): Ride
    {
        if (!canTransitionRideStatus($ride->status->value ?? $ride->status, $newStatus->value)) {
            throw new \Exception('Invalid status transition.');
        }

        DB::transaction(function () use ($ride, $newStatus, $driver) {
            $updateData = ['status' => $newStatus];

            if ($newStatus === RideStatus::IN_PROGRESS) {
                $updateData['started_at'] = now();
            }

            if ($newStatus === RideStatus::COMPLETED) {
                $updateData['completed_at'] = now();
                $updateData['final_fare']   = $ride->estimated_fare;
            }

            if ($newStatus === RideStatus::CANCELLED) {
                $updateData['cancelled_at'] = now();
            }

            $ride->update($updateData);

            // Sync driver_status with ride lifecycle
            $driverStatus = match($newStatus) {
                RideStatus::COMPLETED, RideStatus::CANCELLED => DriverStatus::ONLINE_AVAILABLE,
                default                                       => null,
            };
            if ($driverStatus) {
                $driver->update(['driver_status' => $driverStatus]);
            }

            RideStatusLog::create([
                'ride_id'    => $ride->id,
                'changed_by' => $driver->id,
                'status'     => $newStatus->value,
                'remarks'    => 'Ride status updated by driver.',
            ]);
        });

        $ride->refresh();
        $ride->load('customer');

        SendSmsJob::dispatch(
            $ride->customer->phone,
            buildRideStatusMessage($ride->id, $newStatus->value),
            'ride_status'
        );

        broadcast(new RideStatusUpdated($ride));

        return $ride;
    }
}
