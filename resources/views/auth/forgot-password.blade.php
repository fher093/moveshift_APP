<x-guest-layout> 
                <!-- Logo Section -->
    <div class="flex justify-center mb-12">
                <img src="{{ asset('images/logo-moveshift.svg') }}" alt="MoveShift" class="h-32 w-32 rounded-full shadow-md">
            </div>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Ingrese su dirección de correo electrónico y le enviaremos un enlace para restablecer su contraseña.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Enviar Enlace para Restablecer Contraseña') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
