<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = [
        'driver_id',
        'origin_zone',
        'destination_zone',
        'departure_time',
        'available_seats',
        'total_seats',
        'notes',
        'status',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(TripRequest::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function confirmedPassengers()
    {
        return $this->requests()
            ->where('status', 'accepted')
            ->with('passenger');
    }
}