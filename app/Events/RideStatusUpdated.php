<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(public Ride $ride)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ride.' . $this->ride->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ride.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'ride_id' => $this->ride->id,
            'status' => $this->ride->status,
            'status_label' => rideStatusLabel($this->ride->status),
            'started_at' => optional($this->ride->started_at)?->toDateTimeString(),
            'completed_at' => optional($this->ride->completed_at)?->toDateTimeString(),
            'cancelled_at' => optional($this->ride->cancelled_at)?->toDateTimeString(),
        ];
    }
}