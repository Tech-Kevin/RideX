<?php

namespace App\Models;

use App\Enums\VehicleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_type',
        'base_fare',
        'rate_per_km',
    ];

    protected $casts = [
        'vehicle_type' => VehicleType::class,
        'base_fare' => 'decimal:2',
        'rate_per_km' => 'decimal:2',
    ];
}
