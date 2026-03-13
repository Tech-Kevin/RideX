<?php

namespace App\Enums;

enum DriverStatus: string
{
    case ONLINE_AVAILABLE = 'online_available';
    case ON_TRIP          = 'on_trip';
    case ON_BREAK         = 'on_break';
    case OFFLINE          = 'offline';
    case SUSPENDED        = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::ONLINE_AVAILABLE => 'Online & Available',
            self::ON_TRIP          => 'On Trip',
            self::ON_BREAK         => 'On Break',
            self::OFFLINE          => 'Offline',
            self::SUSPENDED        => 'Suspended',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::ONLINE_AVAILABLE => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            self::ON_TRIP          => 'bg-blue-100 text-blue-700 border-blue-200',
            self::ON_BREAK         => 'bg-amber-100 text-amber-700 border-amber-200',
            self::OFFLINE          => 'bg-neutral-100 text-neutral-500 border-neutral-200',
            self::SUSPENDED        => 'bg-rose-100 text-rose-700 border-rose-200',
        };
    }
}
