<?php

namespace App\Enums;

enum RideStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case DRIVER_ARRIVING = 'driver_arriving';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
