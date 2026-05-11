<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Procesar autenticación con Microsoft
     */
    public function handleMicrosoftAuthentication(array $microsoftData): array
    {
        try {
            $user = $this->userRepository->createOrUpdateFromMicrosoft($microsoftData);
            Auth::login($user);
            
            return [
                'success' => true,
                'message' => 'Autenticación exitosa',
                'user' => $user
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error' => 'Error en autenticación'
            ];
        }
    }

    /**
     * Login con correo y contraseña
     */
    public function login(string $email, string $password): array
    {
        // Validar correo institucional
        if (!str_ends_with($email, '@uta.edu.ec')) {
            return [
                'success' => false,
                'message' => 'Solo se permiten correos institucionales (@uta.edu.ec)'
            ];
        }

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $this->userRepository->updateLastLogin($user->id);
            
            return [
                'success' => true,
                'message' => 'Login exitoso',
                'user' => $user
            ];
        }

        return [
            'success' => false,
            'message' => 'Credenciales inválidas'
        ];
    }

    /**
     * Logout
     */
    public function logout(): bool
    {
        Auth::logout();
        return true;
    }
}
