<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard - Conductor') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('trips.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">
                    + Crear Viaje
                </a>
                <form action="{{ route('dashboard.switch-role') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">
                        Cambiar a Pasajero
                    </button>
                </form>                
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Solicitudes Pendientes (RF6) -->
            @if($pendingRequests->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100 flex items-center">
                        <span class="text-2xl mr-2">🔔</span>
                        Solicitudes Pendientes
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($pendingRequests as $request)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 p-4 rounded-lg">
                                <!-- Passenger Info -->
                                <div class="flex items-center mb-4">
                                    @if($request->passenger->avatar)
                                        <img src="{{ asset('storage/' . $request->passenger->avatar) }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover mr-3">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center mr-3 font-bold">
                                            {{ substr($request->passenger->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $request->passenger->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $request->passenger->email }}</p>
                                    </div>
                                </div>

                                <!-- Trip Info -->
                                <div class="bg-white dark:bg-gray-800 rounded p-3 mb-4 text-sm">
                                    <p class="text-gray-700 dark:text-gray-300">
                                        <strong>{{ $request->trip->origin_zone }}</strong> → <strong>{{ $request->trip->destination_zone }}</strong>
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $request->trip->departure_time->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <form action="{{ route('trips.accept-request', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-3 rounded text-sm">
                                            ✓ Aceptar
                                        </button>
                                    </form>
                                    <form action="{{ route('trips.reject-request', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded text-sm">
                                            ✕ Rechazar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Mis Viajes Activos (RF3) -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100 flex items-center">
                    <span class="text-2xl mr-2">🚗</span>
                    Mis Viajes Activos
                </h3>
                
                @if($activeTrips->isEmpty())
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">No tienes viajes activos</p>
                        <a href="{{ route('trips.create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                            Crear un viaje
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($activeTrips as $trip)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                                <!-- Trip Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $trip->origin_zone }} → {{ $trip->destination_zone }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $trip->departure_time->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                                        Activo
                                    </span>
                                </div>

                                <!-- Trip Details -->
                                <div class="grid grid-cols-3 gap-4 mb-4 py-4 border-y border-gray-200 dark:border-gray-700">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $trip->available_seats }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Asientos disponibles</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $trip->total_seats }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Asientos totales</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $trip->requests->where('status', 'accepted')->count() }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Confirmados</p>
                                    </div>
                                </div>

                                @if($trip->notes)
                                    <div class="mb-4 p-3 bg-gray-100 dark:bg-gray-700 rounded">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            <strong>📝 Notas:</strong> {{ $trip->notes }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Confirmed Passengers (RF7) -->
                                @if($trip->requests->where('status', 'accepted')->isNotEmpty())
                                    <div class="mt-4">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-3">Pasajeros Confirmados:</p>
                                        <div class="space-y-2">
                                            @foreach($trip->requests->where('status', 'accepted') as $request)
                                                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                                    <div class="flex items-center">
                                                        @if($request->passenger->avatar)
                                                            <img src="{{ asset('storage/' . $request->passenger->avatar) }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover mr-2">
                                                        @else
                                                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center mr-2 text-xs font-bold">
                                                                {{ substr($request->passenger->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $request->passenger->name }}</p>
                                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $request->passenger->phone }}</p>
                                                        </div>
                                                    </div>
                                                    <span class="text-green-600 dark:text-green-400 text-sm font-medium">✓ Confirmado</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-2 mt-4">
                                    <a href="#" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                                        Editar
                                    </a>
                                    <form action="#" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                                            Completar Viaje
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Viajes Completados -->
            @if($completedTrips->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100 flex items-center">
                        <span class="text-2xl mr-2">✅</span>
                        Viajes Recientes Completados
                    </h3>
                    <div class="space-y-3">
                        @foreach($completedTrips as $trip)
                            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $trip->origin_zone }} → {{ $trip->destination_zone }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $trip->departure_time->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <span class="text-green-700 dark:text-green-300 font-semibold">Completado</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>