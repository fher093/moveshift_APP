<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Información Personal -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Información Personal') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Actualiza tu información personal y datos de contacto.
                        </p>
                    </header>

                    @if (session('status') === 'profile-updated')
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Perfil actualizado correctamente.') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH') 

                        <!-- Avatar Upload -->
                        <div class="mb-6">
                            <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Foto de Perfil
                            </label>
                            
                            <!-- Preview -->
                            <div class="mb-4">
                                <img id="avatarPreview" 
                                     src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://via.placeholder.com/96' }}"
                                     alt="Avatar Preview" 
                                     class="h-24 w-24 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                            </div>
                            
                            <input 
                                type="file" 
                                id="avatar" 
                                name="avatar" 
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                       file:mr-4 file:py-2 file:px-4
                                       file:rounded-lg file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-blue-50 file:text-blue-700
                                       hover:file:bg-blue-100">
                            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                        </div>

                        <script>
                            document.getElementById('avatar').addEventListener('change', function(e) {
                                const file = e.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(event) {
                                        document.getElementById('avatarPreview').src = event.target.result;
                                    }
                                    reader.readAsDataURL(file);
                                }
                            });
                        </script>

                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre
                            </label>
                            <input type="text" name="name" id="name" value="{{ auth()->user()->name }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Apellido
                            </label>
                            <input type="text" name="last_name" id="last_name" value="{{ auth()->user()->last_name }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (Solo lectura) -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Correo Institucional
                            </label>
                            <input type="email" name="email" id="email" value="{{ auth()->user()->email }}"
                                disabled
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-100 dark:bg-gray-700 dark:text-gray-400 text-gray-600 cursor-not-allowed">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">El correo no puede ser modificado.</p>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Número de Teléfono
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ auth()->user()->phone }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                placeholder="0987654321">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Zona/Barrio -->
                        <div>
                            <label for="zone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Zona / Barrio
                            </label>
                            <input type="text" name="zone" id="zone" value="{{ auth()->user()->zone }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Ej: Centro, La Merced">
                            @error('zone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Carrera -->
                        <div>
                            <label for="career" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Carrera / Programa
                            </label>
                            <input type="text" name="career" id="career" value="{{ auth()->user()->career }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Ej: Ingeniería en Sistemas">
                            @error('career')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botón Submit -->
                        <div class="flex items-center gap-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-900 dark:active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Cambiar Contraseña') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Actualiza tu contraseña para mantener tu cuenta segura.
                        </p>
                    </header>

                    @if (session('status') === 'password-updated')
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Contraseña actualizada correctamente.') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.password') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Contraseña Actual -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contraseña Actual
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                required>
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nueva Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nueva Contraseña
                            </label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                required>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Confirmar Contraseña
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                required>
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botón Submit -->
                        <div class="flex items-center gap-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-900 dark:active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>