<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Mostrar vista de edición de perfil
     */
    public function edit(): View
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Actualizar perfil
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'phone' => 'nullable|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'zone' => 'nullable|string|max:255',
            'career' => 'nullable|string|max:255',
        ], [
            'name.required' => 'El nombre es requerido',
            'last_name.required' => 'El apellido es requerido',
            'phone.regex' => 'El número de teléfono no es válido',
        ]);

        $result = $this->profileService->updateProfile(auth()->id(), $validated);

        if ($result['success']) {
            return back()->with('status', $result['message']);
        }

        return back()->withErrors($result['errors'] ?? ['error' => $result['message']]);
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'La contraseña actual es requerida',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $result = $this->profileService->changePassword(
            auth()->id(),
            $validated['current_password'],
            $validated['password']
        );

        if ($result['success']) {
            return back()->with('status', $result['message']);
        }

        return back()->withErrors(['error' => $result['message']]);
    }
}