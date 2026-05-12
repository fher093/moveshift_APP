<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\EmailVerificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected EmailVerificationService $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'regex:/^[a-z0-9._%+-]+@uta\.edu\.ec$/i'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.regex' => 'Solo se permiten correos institucionales (@uta.edu.ec)',
            'email.unique' => 'Este correo ya está registrado',
        ]);

        // Enviar código de verificación
        $result = $this->emailVerificationService->sendVerificationCode($request->email);

        if (!$result['success']) {
            return back()->withErrors(['email' => $result['message']]);
        }

        // Guardar datos temporales en sesión
        session([
            'registration_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        ]);

        return redirect()->route('auth.verify-email');
    }

    /**
     * Mostrar vista de verificación de email
     */
    public function showVerifyEmail(): View
    {
        if (!session('registration_data')) {
            return redirect()->route('register');
        }

        return view('auth.verify-email', [
            'email' => session('registration_data.email')
        ]);
    }

    /**
     * Verificar código y crear usuario
     */
    public function verifyEmail(Request $request): RedirectResponse
    {
        $registrationData = session('registration_data');

        if (!$registrationData) {
            return redirect()->route('register');
        }

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'El código es requerido',
            'code.size' => 'El código debe tener 6 dígitos',
        ]);

        // Verificar código
        $result = $this->emailVerificationService->verifyCode(
            $registrationData['email'],
            $request->code
        );

        if (!$result['success']) {
            return back()->withErrors(['code' => $result['message']]);
        }

        // Crear usuario
        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => $registrationData['password'],
        ]);

        event(new Registered($user));

        // Limpiar sesión
        session()->forget('registration_data');

        // Login automático
        Auth::login($user);

        return redirect(route('dashboard', absolute: false))->with('status', 'Cuenta creada exitosamente');
    }

    /**
     * Reenviar código
     */
    public function resendCode(Request $request): RedirectResponse
    {
        $registrationData = session('registration_data');

        if (!$registrationData) {
            return redirect()->route('register');
        }

        $result = $this->emailVerificationService->sendVerificationCode($registrationData['email']);

        if (!$result['success']) {
            return back()->withErrors(['email' => $result['message']]);
        }

        return back()->with('status', 'Código reenviado a tu correo');
    }
}