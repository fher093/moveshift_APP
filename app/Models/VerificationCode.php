<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'expires_at',
        'verified',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Verificar si el código es válido
     */
    public function isValid(): bool
    {
        return !$this->verified && $this->expires_at->isFuture();
    }

    /**
     * Obtener código válido por email
     */
    public static function getValidCode(string $email)
    {
        return self::where('email', $email)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Marcar como verificado
     */
    public function markAsVerified(): void
    {
        $this->update(['verified' => true]);
    }
}