<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class MicrosoftController extends Controller
{
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
            return redirect()->route('login')
                ->withErrors(['email' => 'Error al conectar con Microsoft']);
        }
    }

    /**
     * Manejar callback de Microsoft
     */
    public function callback(): RedirectResponse
    {
        try {
            $microsoftUser = Socialite::driver('microsoft')->user();

            // Obtener el email
            $email = $microsoftUser->email ?? $microsoftUser->getEmail() ?? null;
            
            // Validar que sea correo institucional
            if (!$email || !str_ends_with($email, '@uta.edu.ec')) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Solo se permiten correos institucionales (@uta.edu.ec)']);
            }

            // Buscar o crear usuario
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Crear nuevo usuario
                $user = User::create([
                    'name' => explode(' ', $microsoftUser->name ?? 'Usuario')[0],
                    'last_name' => implode(' ', array_slice(explode(' ', $microsoftUser->name ?? ''), 1)) ?: 'Usuario',
                    'email' => $email,
                    'microsoft_id' => $microsoftUser->id,
                    'password' => Hash::make(Str::random(32)),
                    'last_login' => now(),
                ]);
            } else {
                // Actualizar usuario existente
                $user->update([
                    'microsoft_id' => $microsoftUser->id,
                    'last_login' => now(),
                ]);
            }

            // Login del usuario
            Auth::login($user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            \Log::error('Microsoft Auth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['email' => 'Error en autenticación con Microsoft']);
        }
    }
}