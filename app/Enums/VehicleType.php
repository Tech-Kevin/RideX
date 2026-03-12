<?php

namespace App\Enums;

enum VehicleType: string
{
    case BIKE = 'bike';
    case AUTO = 'auto';
    case CAR = 'car';

    public function maxPassengers(): int
    {
        return match ($this) {
            self::BIKE => 1,
            self::AUTO => 3,
            self::CAR => 4,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::BIKE => 'Bike',
            self::AUTO => 'Auto',
            self::CAR => 'Car',
        };
    }
}
