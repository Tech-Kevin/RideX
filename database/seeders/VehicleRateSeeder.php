<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = [
            [
                'vehicle_type' => \App\Enums\VehicleType::BIKE->value,
                'base_fare' => 20.00,
                'rate_per_km' => 7.00,
            ],
            [
                'vehicle_type' => \App\Enums\VehicleType::AUTO->value,
                'base_fare' => 30.00,
                'rate_per_km' => 12.00,
            ],
            [
                'vehicle_type' => \App\Enums\VehicleType::CAR->value,
                'base_fare' => 50.00,
                'rate_per_km' => 18.00,
            ],
        ];

        foreach ($rates as $rate) {
            \App\Models\VehicleRate::updateOrCreate(
                ['vehicle_type' => $rate['vehicle_type']],
                $rate
            );
        }
    }
}
