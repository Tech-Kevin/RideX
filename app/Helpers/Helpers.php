<?php

use App\Enums\RideStatus;
use Carbon\Carbon;

if (!function_exists('generateOtp')) {
    function generateOtp(int $length = 6): string
    {
        $min = (int) str_pad('1', $length, '0');
        $max = (int) str_pad('', $length, '9');

        return (string) random_int($min, $max);
    }
}

if (!function_exists('otpExpiryTime')) {
    function otpExpiryTime(int $minutes = 5): Carbon
    {
        return now()->addMinutes($minutes);
    }
}

if (!function_exists('isOtpExpired')) {
    function isOtpExpired($expiresAt): bool
    {
        return Carbon::parse($expiresAt)->isPast();
    }
}

if (!function_exists('formatPhone')) {
    function formatPhone(string $phone, string $countryCode = '+91'): string
    {
        $phone = preg_replace('/\D+/', '', $phone);
        $phone = ltrim($phone, '0');

        if (str_starts_with($phone, '91') && strlen($phone) > 10) {
            return '+' . $phone;
        }

        return $countryCode . $phone;
    }
}

if (!function_exists('calculateDistance')) {
    function calculateDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $earthRadius = 6371;

        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)
        ));

        return round($angle * $earthRadius, 2);
    }
}

if (!function_exists('calculateFare')) {
    function calculateFare(float $distanceKm, float $baseFare = 30, float $ratePerKm = 12): float
    {
        return round($baseFare + ($distanceKm * $ratePerKm), 2);
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency(float $amount, string $symbol = '₹'): string
    {
        return $symbol . number_format($amount, 2);
    }
}

if (!function_exists('rideStatusLabel')) {
    function rideStatusLabel(string|\App\Enums\RideStatus $status): string
    {
        if ($status instanceof \App\Enums\RideStatus) {
            $status = $status->value;
        }
        
        return match ($status) {
            RideStatus::PENDING->value => 'Pending',
            RideStatus::ACCEPTED->value => 'Accepted',
            RideStatus::DRIVER_ARRIVING->value => 'Driver Arriving',
            RideStatus::IN_PROGRESS->value => 'In Progress',
            RideStatus::COMPLETED->value => 'Completed',
            RideStatus::CANCELLED->value => 'Cancelled',
            default => 'Unknown',
        };
    }
}

if (!function_exists('buildOtpMessage')) {
    function buildOtpMessage(string $otp): string
    {
        return "Your Ride Booking OTP is {$otp}. It is valid for 5 minutes.";
    }
}

if (!function_exists('buildRideBookedMessage')) {
    function buildRideBookedMessage(int|string $rideId, float $fare): string
    {
        return "Your ride #{$rideId} has been booked successfully. Estimated fare is " . formatCurrency($fare) . ".";
    }
}

if (!function_exists('buildRideStatusMessage')) {
    function buildRideStatusMessage(int|string $rideId, string $status): string
    {
        $label = rideStatusLabel($status);

        return "Your ride #{$rideId} status has been updated to {$label}.";
    }
}

if (!function_exists('buildRideCancelledMessage')) {
    function buildRideCancelledMessage(int|string $rideId): string
    {
        return "Your ride #{$rideId} has been cancelled.";
    }
}

if (!function_exists('allowedRideStatusTransitions')) {
    function allowedRideStatusTransitions(): array
    {
        return [
            RideStatus::PENDING->value => [
                RideStatus::ACCEPTED->value,
                RideStatus::CANCELLED->value,
            ],
            RideStatus::ACCEPTED->value => [
                RideStatus::DRIVER_ARRIVING->value,
                RideStatus::CANCELLED->value,
            ],
            RideStatus::DRIVER_ARRIVING->value => [
                RideStatus::IN_PROGRESS->value,
                RideStatus::CANCELLED->value,
            ],
            RideStatus::IN_PROGRESS->value => [
                RideStatus::COMPLETED->value,
                RideStatus::CANCELLED->value,
            ],
            RideStatus::COMPLETED->value => [],
            RideStatus::CANCELLED->value => [],
        ];
    }
}

if (!function_exists('canTransitionRideStatus')) {
    function canTransitionRideStatus(string $currentStatus, string $newStatus): bool
    {
        $transitions = allowedRideStatusTransitions();

        return in_array($newStatus, $transitions[$currentStatus] ?? [], true);
    }
}