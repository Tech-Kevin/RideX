<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_phone_verified',
        'current_lat',
        'current_lng',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_phone_verified' => 'boolean',
            'current_lat' => 'decimal:7',
            'current_lng' => 'decimal:7',
        ];
    }
    public function customerRides()
    {
        return $this->hasMany(Ride::class, 'customer_id');
    }

    public function driverRides()
    {
        return $this->hasMany(Ride::class, 'driver_id');
    }

    public function rideStatusLogs()
    {
        return $this->hasMany(RideStatusLog::class, 'changed_by');
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isDriver()
    {
        return $this->role === 'driver';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

}
