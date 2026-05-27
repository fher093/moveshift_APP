<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'zone',
        'career',
        'last_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación con trips (como conductor)
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    /**
     * Relación con trip requests (como pasajero)
     */
    public function tripRequests(): HasMany
    {
        return $this->hasMany(TripRequest::class, 'passenger_id');
    }

    /**
     * Relación con ratings
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'from_user_id');
    }

    /**
     * Relación con vehículos
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'driver_id');
    }

    /**
     * Obtener vehículo activo
     */
    public function activeVehicle()
    {
        return $this->vehicles()->where('is_active', true)->first();
    }
}