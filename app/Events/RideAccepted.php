<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideAccepted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(public Ride $ride)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('drivers'),
            new PrivateChannel('ride.' . $this->ride->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ride.accepted';
    }

    public function broadcastWith(): array
    {
        return [
            'ride_id' => $this->ride->id,
            'driver_id' => $this->ride->driver?->id,
            'driver_name' => $this->ride->driver?->name,
            'driver_phone' => $this->ride->driver?->phone,
            'status' => $this->ride->status,
            'accepted_at' => optional($this->ride->accepted_at)?->toDateTimeString(),
        ];
    }
}