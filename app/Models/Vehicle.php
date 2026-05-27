<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'driver_id',
        'brand',
        'model',
        'plate',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación con User (conductor)
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Obtener vehículo activo del conductor
     */
    public static function getActiveVehicle($driverId)
    {
        return self::where('driver_id', $driverId)
            ->where('is_active', true)
            ->first();
    }
}