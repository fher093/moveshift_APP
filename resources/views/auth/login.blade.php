<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <!-- Logo -->
            <div class="max-w-md w-full space-y-8">
            <!-- Logo / Title -->
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-black">
                    MoveShift Taxi
                </h2>
                <p class="mt-2 text-sm text-black-100">
                    Servicio de taxi estudiantil
                </p>
            </div>

            <!-- Tabs -->
            <div class="flex border-b border-gray-300">
                
                <button class="flex-1 py-2 px-4 text-center text-black font-medium border-b-2">
                    Iniciar Sesión
                </button>
            </div>

            <!-- Form Container -->
            <div class="bg-white space-y-6">
                <!-- Welcome Text -->
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900">¡Bienvenido!</h2>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            placeholder="name@uta.edu.ec"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-black text-gray-700 placeholder-gray-400">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <!-- Password -->
                    <div>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required
                            placeholder="Contraseña"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-black text-gray-700 placeholder-gray-400">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
                        Ingresar
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                </div>

                <!-- Microsoft Button -->
                <div>
                    <a href="{{ route('auth.microsoft') }}"
                        class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-lg bg-white text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.4 24H0V12.6h11.4V24zM24 24H12.6V12.6H24V24zM11.4 11.4H0V0h11.4v11.4zm12.6 0H12.6V0H24v11.4z"/>
                        </svg>
                        <span class="ml-2">Continuar con Microsoft</span>
                    </a>
                </div>

                <!-- Links -->
                <div class="flex justify-between items-center text-sm">
                    <a href="{{ route('password.request') }}" class="text-gray-600 hover:text-gray-800">
                        ¿Olvidaste tu contraseña?
                    </a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-800">
                        ¿No tienes cuenta?
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