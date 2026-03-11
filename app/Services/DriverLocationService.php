<?php

namespace App\Services;

use App\Events\DriverLocationUpdated;
use App\Models\Ride;
use App\Models\User;
use App\Enums\RideStatus;

class DriverLocationService
{
    public function updateLocation(User $driver, float $lat, float $lng): void
    {
        $driver->update([
            'current_lat' => $lat,
            'current_lng' => $lng,
        ]);

        $activeRide = Ride::where('driver_id', $driver->id)
            ->whereIn('status', [
                RideStatus::ACCEPTED,
                RideStatus::DRIVER_ARRIVING,
                RideStatus::IN_PROGRESS,
            ])
            ->latest()
            ->first();

        if ($activeRide) {
            broadcast(new DriverLocationUpdated($activeRide, $driver));
        }
    }
}
