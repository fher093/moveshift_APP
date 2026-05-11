<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function findByMicrosoftId(string $microsoftId)
    {
        return $this->model->where('microsoft_id', $microsoftId)->first();
    }

    public function getByInstitutionalEmail(string $email)
    {
        if (!str_ends_with($email, '@uta.edu.ec')) {
            return null;
        }
        return $this->findByEmail($email);
    }

    public function updateLastLogin(int $userId)
    {
        return $this->update($userId, [
            'last_login' => now()
        ]);
    }

    public function createOrUpdateFromMicrosoft(array $microsoftData)
    {
        $email = $microsoftData['email'];

        // Validar que sea correo institucional
        if (!str_ends_with($email, '@uta.edu.ec')) {
            throw new \Exception('Solo se permiten correos institucionales (@uta.edu.ec)');
        }

        $user = $this->findByMicrosoftId($microsoftData['id']);

        if ($user) {
            $this->update($user->id, [
                'avatar' => $microsoftData['avatar'] ?? $user->avatar,
                'last_login' => now(),
            ]);
            return $user->refresh();
        }

        // Crear nuevo usuario
        return $this->create([
            'name' => $microsoftData['name'] ?? '',
            'email' => $email,
            'microsoft_id' => $microsoftData['id'],
            'avatar' => $microsoftData['avatar'] ?? null,
            'last_login' => now(),
            'password' => bcrypt(str_random(32)),
        ]);
    }
}
