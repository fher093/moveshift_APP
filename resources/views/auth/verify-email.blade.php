<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <!-- Logo -->
            <div class="flex justify-center">
                <img src="{{ asset('images/moveshift-logo.png') }}" alt="MoveShift" class="h-32 w-32">
            </div>

            <!-- Form Container -->
            <div class="bg-white space-y-6 p-6 rounded-lg">
                <!-- Title -->
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900">Verifica tu correo</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Hemos enviado un código a:<br>
                        <strong>{{ $email }}</strong>
                    </p>
                </div>

                <!-- Status Messages -->
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Verification Form -->
                <form method="POST" action="{{ route('auth.verify-email.store') }}" class="space-y-5">
                    @csrf

                    <!-- Code Input -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Ingresa el código de 6 dígitos
                        </label>
                        <input 
                            id="code" 
                            type="text" 
                            name="code" 
                            inputmode="numeric"
                            maxlength="6"
                            placeholder="000000"
                            required
                            autofocus
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-black text-center text-2xl tracking-widest font-bold text-gray-700"
                            pattern="[0-9]{6}">
                        <x-input-error :messages="$errors->get('code')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <!-- Timer -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <strong>⏱️ Tiempo restante:</strong> El código expira en 5 minutos
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
                        Verificar
                    </button>
                </form>

                <!-- Resend Code -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-3">¿No recibiste el código?</p>
                    <form method="POST" action="{{ route('auth.resend-code') }}" class="inline">
                        @csrf
                        <button 
                            type="submit"
                            class="text-black font-semibold hover:underline">
                            Reenviar código
                        </button>
                    </form>
                </div>

                <!-- Back Link -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                        Volver al login
                    </a>
                </div>

                <!-- Taxi Icon -->
                <div class="flex justify-center pt-4">
                    <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.22.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm11 0c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM5 11l1.5-4.5h11L19 11H5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>