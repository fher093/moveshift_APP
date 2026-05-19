<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Viaje') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <form action="{{ route('trips.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Zona de Origen (RF3) -->
                    <div>
                        <label for="origin_zone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Zona de Origen
                        </label>
                        <input type="text" id="origin_zone" name="origin_zone" 
                            value="{{ old('origin_zone') }}"
                            placeholder="Ej: Centro, La Merced, Barrio"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                        @error('origin_zone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Destino (RF3) -->
                    <div>
                        <label for="destination_zone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Destino
                        </label>
                        <input type="text" id="destination_zone" name="destination_zone" 
                            value="{{ old('destination_zone') }}"
                            placeholder="Ej: Campus UTA, Parque Industrial"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                        @error('destination_zone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha y Hora de Salida (RF3) -->
                    <div>
                        <label for="departure_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Fecha y Hora de Salida
                        </label>
                        <input type="datetime-local" id="departure_time" name="departure_time" 
                            value="{{ old('departure_time') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                        @error('departure_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cupos Disponibles (RF3) -->
                    <div>
                        <label for="total_seats" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cupos Disponibles
                        </label>
                        <div class="flex items-center gap-4">
                            <input type="number" id="total_seats" name="total_seats" 
                                value="{{ old('total_seats', 4) }}"
                                min="1" max="8"
                                class="mt-1 block w-32 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <span class="text-sm text-gray-600 dark:text-gray-400">(Entre 1 y 8 asientos)</span>
                        </div>
                        @error('total_seats')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notas/Reglas (RF3) -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Notas y Reglas
                        </label>
                        <textarea id="notes" name="notes" rows="4"
                            placeholder="Ej: No fumar en el vehículo, Llegada puntual, Compartir gasolina, etc."
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Opcional: Añade información importante para los pasajeros</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                            Crear Viaje
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>