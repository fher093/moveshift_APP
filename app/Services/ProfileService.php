<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Obtener perfil del usuario
     */
    public function getProfile(int $userId): array
    {
        $user = $this->userRepository->find($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado'
            ];
        }

        return [
            'success' => true,
            'user' => $user
        ];
    }

    /**
     * Actualizar perfil del usuario
     */
    public function updateProfile(int $userId, array $data): array
    {
        try {
            // Validar datos
            $validated = $this->validateProfileData($data);

            // Actualizar usuario
            $user = $this->userRepository->update($userId, $validated);

            return [
                'success' => true,
                'message' => 'Perfil actualizado correctamente',
                'user' => $user
            ];
        } catch (ValidationException $e) {
            return [
                'success' => false,
                'message' => 'Error en validación',
                'errors' => $e->errors()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al actualizar perfil',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validar datos del perfil
     */
    private function validateProfileData(array $data): array
    {
        $rules = [
            'name' => 'sometimes|string|min:2|max:255',
            'last_name' => 'sometimes|string|min:2|max:255',
            'phone' => 'sometimes|nullable|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'zone' => 'sometimes|nullable|string|max:255',
            'career' => 'sometimes|nullable|string|max:255',
        ];

        $messages = [
            'phone.regex' => 'El número de teléfono no es válido',
        ];

        // Usar validación manual
        validator($data, $rules, $messages)->validate();

        return array_intersect_key($data, array_flip(['name', 'last_name', 'phone', 'zone', 'career']));
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): array
    {
        $user = $this->userRepository->find($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado'
            ];
        }

        if (!\Hash::check($currentPassword, $user->password)) {
            return [
                'success' => false,
                'message' => 'La contraseña actual es incorrecta'
            ];
        }

        $this->userRepository->update($userId, [
            'password' => bcrypt($newPassword)
        ]);

        return [
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ];
    }
}
