<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideRequested implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(public Ride $ride)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('drivers'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ride.requested';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->ride->id,
            'pickup_address' => $this->ride->pickup_address,
            'drop_address' => $this->ride->drop_address,
            'distance_km' => (string) $this->ride->distance_km,
            'estimated_fare' => (string) $this->ride->estimated_fare,
            'customer_name' => $this->ride->customer?->name ?? 'Customer',
            'status' => $this->ride->status,
            'created_at' => $this->ride->created_at?->toDateTimeString(),
        ];
    }
}