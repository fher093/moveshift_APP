<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;

class MicrosoftController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        
        // Desactivar verificación SSL globalmente en desarrollo
        if (app()->environment('local')) {
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
        }
    }

    /**
     * Redirigir a Microsoft para autenticación
     */
    public function redirect()
    {
        try {
            return Socialite::driver('microsoft')
                ->scopes(['mail', 'profile', 'openid'])
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Microsoft Redirect Error: ' . $e->getMessage());
            return Redirect::route('login')
                ->withErrors(['email' => 'Error al redirigir a Microsoft']);
        }
    }

    /**
     * Manejar callback de Microsoft
     */
    public function callback()
    {
        try {
            $microsoftUser = Socialite::driver('microsoft')->user();

            // Obtener el email
            $email = $microsoftUser->email ?? $microsoftUser->getEmail() ?? null;
            
            // Validar que sea correo institucional
            if (!$email || !str_ends_with($email, '@uta.edu.ec')) {
                return Redirect::route('login')
                    ->withErrors(['email' => 'Solo se permiten correos institucionales (@uta.edu.ec)']);
            }

            // Procesar autenticación
            $result = $this->authService->handleMicrosoftAuthentication([
                'id' => $microsoftUser->id ?? $microsoftUser->getId(),
                'name' => $microsoftUser->name ?? $microsoftUser->getName() ?? 'Usuario',
                'email' => $email,
                'avatar' => $microsoftUser->avatar ?? $microsoftUser->getAvatar() ?? null,
            ]);

            if ($result['success']) {
                return Redirect::route('dashboard');
            }

            return Redirect::route('login')
                ->withErrors(['email' => $result['message']]);

        } catch (\Exception $e) {
            \Log::error('Microsoft Auth Error: ' . $e->getMessage());
            return Redirect::route('login')
                ->withErrors(['email' => 'Error en autenticación con Microsoft']);
        }
    }
}