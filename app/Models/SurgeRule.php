<?php

namespace App\Models;

use App\Enums\DriverStatus;
use Illuminate\Database\Eloquent\Model;

class SurgeRule extends Model
{
    protected $fillable = [
        'name',
        'type',
        'multiplier',
        'conditions',
        'is_active',
        'priority',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'is_active'  => 'boolean',
            'multiplier' => 'decimal:2',
            'starts_at'  => 'datetime',
            'ends_at'    => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function typeBadge(): string
    {
        return match($this->type) {
            'peak_hour'       => 'bg-blue-100 text-blue-700 border-blue-200',
            'festival'        => 'bg-purple-100 text-purple-700 border-purple-200',
            'demand_based'    => 'bg-amber-100 text-amber-700 border-amber-200',
            'manual_override' => 'bg-rose-100 text-rose-700 border-rose-200',
            default           => 'bg-neutral-100 text-neutral-500 border-neutral-200',
        };
    }

    public function typeLabel(): string
    {
        return match($this->type) {
            'peak_hour'       => 'Peak Hour',
            'festival'        => 'Festival / Event',
            'demand_based'    => 'Demand Based',
            'manual_override' => 'Manual Override',
            default           => 'Unknown',
        };
    }
}
