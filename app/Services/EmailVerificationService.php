<?php

namespace App\Services;

use App\Mail\SendVerificationCode;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;

class EmailVerificationService
{
    /**
     * Generar y enviar código de verificación
     */
    public function sendVerificationCode(string $email): array
    {
        try {
            // Generar código de 6 dígitos
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Eliminar TODOS los códigos anteriores (verificados o no)
            VerificationCode::where('email', $email)->delete();

            // Crear nuevo código con expiración de 5 minutos
            VerificationCode::create([
                'email'      => $email,
                'code'       => $code,
                'expires_at' => now()->addMinutes(5),
            ]);

            // Enviar email
            Mail::to($email)->send(new SendVerificationCode($code, $email));

            return [
                'success' => true,
                'message' => 'Código enviado a tu correo',
            ];

        } catch (\Exception $e) {
            \Log::error('Email Verification Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al enviar el código',
            ];
        }
    }

    /**
     * Verificar código
     */
    public function verifyCode(string $email, string $code): array
    {
        $verification = VerificationCode::getValidCode($email);

        if (!$verification) {
            return [
                'success' => false,
                'message' => 'Código expirado o no válido',
            ];
        }

        if ($verification->code !== $code) {
            return [
                'success' => false,
                'message' => 'Código incorrecto',
            ];
        }

        $verification->markAsVerified();

        return [
            'success' => true,
            'message' => 'Código verificado correctamente',
        ];
    }
}