<?php

use App\Models\Ride;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('drivers', function (User $user) {
    return $user->isDriver();
});

Broadcast::channel('ride.{rideId}', function (User $user, int $rideId) {
    return Ride::where('id', $rideId)
        ->where(function ($query) use ($user) {
            $query->where('customer_id', $user->id)
                ->orWhere('driver_id', $user->id);
        })
        ->exists();
});