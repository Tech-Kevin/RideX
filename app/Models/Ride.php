<?php

namespace App\Models;

use App\Enums\RideStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'driver_id',
        'pickup_address',
        'pickup_lat',
        'pickup_lng',
        'drop_address',
        'drop_lat',
        'drop_lng',
        'distance_km',
        'estimated_fare',
        'final_fare',
        'status',
        'accepted_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'vehicle_type',
    ];

    protected function casts(): array
    {
        return [
            'status' => RideStatus::class,
            'vehicle_type' => \App\Enums\VehicleType::class,
            'pickup_lat' => 'decimal:7',
            'pickup_lng' => 'decimal:7',
            'drop_lat' => 'decimal:7',
            'drop_lng' => 'decimal:7',
            'distance_km' => 'decimal:2',
            'estimated_fare' => 'decimal:2',
            'final_fare' => 'decimal:2',
            'accepted_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(RideStatusLog::class);
    }
}
