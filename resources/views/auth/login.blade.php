<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-12">
        <div class="w-full max-w-sm">
            <!-- Logo Section -->
            <div class="flex justify-center mb-12">
                <img src="{{ asset('images/logo-moveshift.svg') }}" alt="MoveShift" class="h-32 w-32 rounded-full shadow-md">
            </div>

            <!-- Card Container -->
            <div class="bg-white space-y-8">
                
                <!-- Tabs -->
                <div class="flex gap-8 border-b-2 border-gray-200 pb-2">
                    <a href="{{ route('register') }}" class="text-lg font-medium text-gray-300 hover:text-gray-400 transition-colors pb-3">
                        Registrarse
                    </a>
                    <button type="button" class="text-lg font-bold text-gray-900 border-b-4 border-black pb-2 -mb-5">
                        Iniciar Sesión
                    </button>
                </div>

                <!-- Welcome Header -->
                <div class="text-center pt-6">
                    <h1 class="text-4xl font-bold text-gray-900">¡Bienvenido!</h1>
                </div>

                <!-- Status Messages -->
                @if (session('status'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            placeholder="name@uta.edu.ec"
                            class="w-full px-5 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-all text-base"
                        >
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password Input -->
                    <div>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required
                            placeholder="Contraseña"
                            class="w-full px-5 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-all text-base"
                        >
                        @if ($errors->has('password'))
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit"
                        class="w-full bg-black text-white font-bold py-4 rounded-lg hover:bg-gray-800 transition-colors duration-200 text-lg mt-8">
                        Ingresar
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                </div>

                

                <!-- Footer Links -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        ¿No tienes cuenta?
                    </a>
                </div>

                <!-- Footer Icon -->
                <div class="flex justify-center pt-8">
                    <svg class="w-12 h-12 text-gray-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.22.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm11 0c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM5 11l1.5-4.5h11L19 11H5z"/>
                    </svg>
                </div>

                <!-- Bottom Line -->
                <div class="flex justify-center pt-6">
                    <div class="w-16 h-1 bg-black rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>