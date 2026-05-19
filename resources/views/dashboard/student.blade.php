<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard - Pasajero') }}
            </h2>
            <form action="{{ route('dashboard.switch-role') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">
                    Cambiar a Conductor
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Búsqueda y Filtros (RF4) -->
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Buscar Viajes</h3>
                <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Zona de Origen</label>
                        <input type="text" name="origin_zone" value="{{ request('origin_zone') }}" 
                            placeholder="Ej: Centro, La Merced"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Destino</label>
                        <input type="text" name="destination_zone" value="{{ request('destination_zone') }}"
                            placeholder="Campus, Zona..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha</label>
                        <input type="date" name="date" value="{{ request('date') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Viajes Disponibles (RF4) -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Viajes Disponibles</h3>
                
                @if($availableTrips->isEmpty())
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400">No hay viajes disponibles con esos criterios</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($availableTrips as $trip)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition">
                                <!-- Driver Info -->
                                <div class="flex items-center mb-4">
                                    @if($trip->driver->avatar)
                                        <img src="{{ asset('storage/' . $trip->driver->avatar) }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover mr-3">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center mr-3 font-bold">
                                            {{ substr($trip->driver->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $trip->driver->name }}</p>
                                        <p class="text-sm text-gray-500">Conductor</p>
                                    </div>
                                </div>

                                <!-- Trip Details -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold mr-2">📍 Origen:</span>
                                        {{ $trip->origin_zone }}
                                    </div>
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold mr-2">🎯 Destino:</span>
                                        {{ $trip->destination_zone }}
                                    </div>
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold mr-2">🕐 Salida:</span>
                                        {{ $trip->departure_time->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold mr-2">👥 Asientos:</span>
                                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-sm">
                                            {{ $trip->available_seats }} / {{ $trip->total_seats }}
                                        </span>
                                    </div>
                                    @if($trip->notes)
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <span class="font-semibold">📝 Notas:</span>
                                            {{ $trip->notes }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Request Button (RF5) -->
                                @php
                                    $hasRequested = $myRequests->contains(function($req) use ($trip) {
                                        return $req->trip_id === $trip->id && $req->status !== 'rejected';
                                    });
                                @endphp

                                @if($hasRequested)
                                    <button disabled class="w-full bg-gray-400 text-white font-medium py-2 px-4 rounded-lg cursor-not-allowed">
                                        Solicitud Enviada
                                    </button>
                                @else
                                    <form action="{{ route('trips.request', $trip) }}" method="POST" class="inline-block w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                                            Solicitar Viaje
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Mis Solicitudes Pendientes -->
            @if($myRequests->whereIn('status', ['pending'])->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Mis Solicitudes Pendientes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($myRequests->where('status', 'pending') as $request)
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded">
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $request->trip->origin_zone }} → {{ $request->trip->destination_zone }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Salida: {{ $request->trip->departure_time->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-2 font-medium">
                                    ⏳ Esperando respuesta del conductor
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Mis Viajes Confirmados (RF7) -->
            @if($confirmedTrips->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Mis Viajes Confirmados</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($confirmedTrips as $request)
                            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded">
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $request->trip->origin_zone }} → {{ $request->trip->destination_zone }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Conductor: <strong>{{ $request->trip->driver->name }}</strong>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Salida: {{ $request->trip->departure_time->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-sm text-green-700 dark:text-green-300 mt-2 font-medium">
                                    ✅ Confirmado
                                </p>
                                
                                @if($request->trip->status === 'completed' && !$request->trip->ratings->where('from_user_id', auth()->id())->first())
                                    <a href="{{ route('trips.rate', $request->trip) }}" class="mt-3 inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-1 px-3 rounded">
                                        Calificar Viaje
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>