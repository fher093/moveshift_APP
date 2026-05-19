<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'zone',
        'career',
        'avatar',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relaciones
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    public function tripRequests(): HasMany
    {
        return $this->hasMany(TripRequest::class, 'passenger_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'from_user_id');
    }

    public function receivedRatings(): HasMany
    {
        return $this->hasMany(Rating::class, 'to_user_id');
    }

    /**
     * Obtener promedio de calificaciones
     */
    public function getAverageRating()
    {
        return $this->receivedRatings()->avg('rating') ?? 0;
    }

    /**
     * Obtener número total de calificaciones
     */
    public function getTotalRatings()
    {
        return $this->receivedRatings()->count();
    }
}