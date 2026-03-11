<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'changed_by',
        'status',
        'remarks',
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
