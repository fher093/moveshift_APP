<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <!-- Logo -->
            <div class="flex justify-center">
                <img src="{{ asset('images/moveshift-logo.png') }}" alt="MoveShift" class="h-32 w-32">
            </div>

            <!-- Tabs -->
            <div class="flex border-b border-gray-300">
                <button class="flex-1 py-2 px-4 text-center text-black font-medium border-b-2 border-black">
                    Registrarse
                </button>
                <button class="flex-1 py-2 px-4 text-center text-gray-400 hover:text-gray-600 font-medium">
                    Iniciar Sesión
                </button>
            </div>

            <!-- Form Container -->
            <div class="bg-white space-y-6">
                <!-- Welcome Text -->
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900">¡Bienvenido!</h2>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus
                            placeholder="Nombre"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-black text-gray-700 placeholder-gray-400">
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <!-- Email -->
                    <div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required
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
                            placeholder="Contraseña (mínimo 8 caracteres)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-black text-gray-700 placeholder-gray-400">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required
                            placeholder="Confirmar contraseña"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-black text-gray-700 placeholder-gray-400">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
                        Registrarse
                    </button>
                </form>

                <!-- Links -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                        ¿Ya tienes cuenta? Inicia sesión
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