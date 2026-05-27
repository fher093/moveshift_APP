<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'origin_zone',
        'destination_zone',
        'departure_time',
        'available_seats',
        'total_seats',
        'notes',
        'price',
        'status',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
    ];

    /**
     * Relación con User (conductor)
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Relación con Vehicle
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Relación con TripRequest
     */
    public function requests(): HasMany
    {
        return $this->hasMany(TripRequest::class);
    }

    /**
     * Relación con Rating
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Obtener pasajeros confirmados
     */
    public function confirmedPassengers()
    {
        return $this->requests()->where('status', 'accepted');
    }
}