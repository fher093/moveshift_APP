<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black dark:text-white">
            Crear Nuevo Viaje
        </h2>
    </x-slot>

    <div class="py-8 bg-white dark:bg-black min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-8">
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/50 rounded-lg p-4">
                        <p class="text-sm font-bold text-red-700 dark:text-red-400 mb-2">Por favor corrige los errores:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-red-600 dark:text-red-300">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('trips.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Seleccionar Vehículo -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Vehículo
                        </label>
                        <select name="vehicle_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition @error('vehicle_id') border-red-500 @enderror">
                            <option value="">-- Selecciona un vehículo --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id || $activeVehicle?->id == $vehicle->id)>
                                    {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate }})
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror

                        @if($vehicles->isEmpty())
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                No tienes vehículos registrados. Registra uno primero.
                            </p>
                        @endif
                    </div>

                    <!-- Origen -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Zona de Origen
                        </label>
                        <input type="text" name="origin_zone" value="{{ old('origin_zone') }}" placeholder="Ej: Centro, La Merced" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition @error('origin_zone') border-red-500 @enderror">
                        @error('origin_zone')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Destino -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Destino
                        </label>
                        <input type="text" name="destination_zone" value="{{ old('destination_zone') }}" placeholder="Ej: Campus UTA, Huachi" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition @error('destination_zone') border-red-500 @enderror">
                        @error('destination_zone')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha y Hora -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Fecha y Hora de Salida
                        </label>
                        <input type="datetime-local" name="departure_time" value="{{ old('departure_time') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition @error('departure_time') border-red-500 @enderror">
                        @error('departure_time')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asientos -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Total de Asientos
                        </label>
                        <select name="total_seats" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition @error('total_seats') border-red-500 @enderror">
                            <option value="">-- Selecciona --</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" @selected(old('total_seats') == $i)>{{ $i }} asiento{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                        @error('total_seats')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Precio por Pasajero ($)
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}" placeholder="0.00" step="0.01" min="0" max="999.99" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notas -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Notas Adicionales (Opcional)
                        </label>
                        <textarea name="notes" placeholder="Ej: Música a bajo volumen, no fumar, etc" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3 pt-6 border-t border-gray-300 dark:border-gray-700">
                        <a href="{{ route('dashboard') }}" class="flex-1 text-center bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold py-2.5 px-4 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="flex-1 bg-black dark:bg-white text-white dark:text-black font-bold py-2.5 px-4 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition">
                            Crear Viaje
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>