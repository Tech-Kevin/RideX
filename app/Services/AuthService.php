<?php

namespace App\Services;

use App\Jobs\SendSmsJob;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function sendOtp(string $phoneInput): string
    {
        $phone = formatPhone($phoneInput);
        $otp = null;

        DB::transaction(function () use ($phone, &$otp) {
            User::firstOrCreate(
                ['phone' => $phone],
                [
                    'name' => 'Customer',
                    'email' => null,
                    'password' => null,
                    'role' => 'customer',
                    'is_phone_verified' => false,
                ]
            );

            OtpCode::where('phone', $phone)
                ->where('is_used', false)
                ->update(['is_used' => true]);

            $otp = generateOtp();

            OtpCode::create([
                'phone' => $phone,
                'otp' => $otp,
                'expires_at' => otpExpiryTime(),
                'is_used' => false,
            ]);
        });

        SendSmsJob::dispatch(
            $phone,
            buildOtpMessage($otp),
            'otp'
        );

        return $phone;
    }

    /**
     * @return User
     * @throws \Exception
     */
    public function verifyOtp(string $phone, string $otp): User
    {
        $otpCode = OtpCode::where('phone', $phone)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$otpCode) {
            throw new \Exception('Invalid OTP.');
        }

        if (isOtpExpired($otpCode->expires_at)) {
            throw new \Exception('OTP has expired.');
        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            throw new \Exception('User not found.');
        }

        DB::transaction(function () use ($otpCode, $user) {
            $otpCode->update(['is_used' => true]);
            $user->update(['is_phone_verified' => true]);
        });

        return $user;
    }
}
