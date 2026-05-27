<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4 max-w-7xl mx-auto w-full">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.svg') }}" alt="MoveShift" class="h-8 w-8">
                <h2 class="font-bold text-xl text-black dark:text-white">
                    MoveShift
                </h2>
            </div>
            <div class="flex items-center gap-4">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                @else
                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center font-bold text-gray-700 dark:text-gray-300">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:inline">{{ auth()->user()->name }}</span>
                <div class="flex gap-2">
                    <a href="{{ route('trips.create') }}" class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 text-sm font-bold transition">
                        + Viaje
                    </a>
                    <form action="{{ route('dashboard.switch-role') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium transition">
                            Pasajero
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-white dark:bg-black min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Solicitudes Pendientes --> 
             <!-- AGREGAR ESTO AL DASHBOARD DEL CONDUCTOR, ANTES DE "Solicitudes Pendientes" -->

<!-- Mis Vehículos -->
<div class="bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-gray-200 dark:border-gray-800 p-6">
    <div class="flex items-center gap-3 mb-6">
        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        <h3 class="text-lg font-bold text-black dark:text-white">
            Mi Vehículo
        </h3>
    </div>

    @if($vehicles->isEmpty())
        <!-- Sin vehículo registrado -->
        <div class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-8 text-center mb-6">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <p class="text-gray-700 dark:text-gray-300 mb-4 font-bold">No tienes vehículos registrados</p>
            <button type="button" onclick="toggleVehicleForm()" class="inline-block bg-black dark:bg-white text-white dark:text-black font-bold py-2.5 px-6 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition">
                + Registrar Vehículo
            </button>
        </div>
    @else
        <!-- Vehículos registrados -->
        <div class="space-y-4 mb-6">
            @foreach($vehicles as $vehicle)
                <div class="bg-white dark:bg-gray-800 border-2 {{ $vehicle->is_active ? 'border-black dark:border-white' : 'border-gray-300 dark:border-gray-700' }} p-5 rounded-lg transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-lg font-bold text-black dark:text-white">
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Placa: {{ $vehicle->plate }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Color: {{ $vehicle->color }}</p>
                        </div>
                        @if($vehicle->is_active)
                            <span class="bg-black dark:bg-white text-white dark:text-black px-3 py-1 rounded-full text-xs font-bold uppercase">
                                ✓ Activo
                            </span>
                        @else
                            <span class="bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                Inactivo
                            </span>
                        @endif
                    </div>

                    <div class="flex gap-2.5 pt-4 border-t-2 border-gray-300 dark:border-gray-700">
                        @if(!$vehicle->is_active)
                            <form action="{{ route('vehicles.switch', $vehicle->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-black dark:bg-white text-white dark:text-black font-bold py-2.5 px-3 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 text-sm transition">
                                    Activar
                                </button>
                            </form>
                        @endif
                        <button type="button" onclick="editVehicle({{ $vehicle->id }}, '{{ $vehicle->brand }}', '{{ $vehicle->model }}', '{{ $vehicle->plate }}', '{{ $vehicle->color }}')" class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold py-2.5 px-3 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 text-sm transition">
                            Editar
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Botón agregar más vehículos -->
        <button type="button" onclick="toggleVehicleForm()" class="w-full border-2 border-dashed border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold py-2.5 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-sm transition">
            + Agregar otro vehículo
        </button>
    @endif

    <!-- Formulario registrar/editar vehículo (oculto por defecto) -->
    <div id="vehicleForm" class="hidden bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 rounded-lg p-6 mt-6">
        <h4 class="text-base font-bold text-black dark:text-white mb-4" id="formTitle">
            Registrar Nuevo Vehículo
        </h4>

        <form action="{{ route('vehicles.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="vehicle_id" id="vehicleId" value="">

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">
                    Marca
                </label>
                <input type="text" name="brand" id="brand" placeholder="Ej: Toyota" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">
                    Modelo
                </label>
                <input type="text" name="model" id="model" placeholder="Ej: Corolla" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">
                    Placa
                </label>
                <input type="text" name="plate" id="plate" placeholder="Ej: ABC-1234" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition uppercase" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">
                    Color
                </label>
                <input type="text" name="color" id="color" placeholder="Ej: Blanco, Negro" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition" required>
            </div>

            <div class="flex gap-2.5 pt-4 border-t-2 border-gray-300 dark:border-gray-700">
                <button type="button" onclick="toggleVehicleForm()" class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold py-2.5 px-4 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 text-sm transition">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 bg-black dark:bg-white text-white dark:text-black font-bold py-2.5 px-4 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 text-sm transition">
                    Guardar Vehículo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para mostrar/ocultar formulario -->
<script>
function toggleVehicleForm() {
    const form = document.getElementById('vehicleForm');
    const title = document.getElementById('formTitle');
    
    form.classList.toggle('hidden');
    
    if (!form.classList.contains('hidden')) {
        // Limpiar formulario para nuevo vehículo
        document.getElementById('vehicleId').value = '';
        document.getElementById('brand').value = '';
        document.getElementById('model').value = '';
        document.getElementById('plate').value = '';
        document.getElementById('color').value = '';
        title.textContent = 'Registrar Nuevo Vehículo';
    }
}

function editVehicle(id, brand, model, plate, color) {
    const form = document.getElementById('vehicleForm');
    const title = document.getElementById('formTitle');
    
    document.getElementById('vehicleId').value = id;
    document.getElementById('brand').value = brand;
    document.getElementById('model').value = model;
    document.getElementById('plate').value = plate;
    document.getElementById('color').value = color;
    title.textContent = 'Editar Vehículo';
    
    form.classList.remove('hidden');
    form.scrollIntoView({ behavior: 'smooth' });
}
</script>
            @if($pendingRequests->isNotEmpty())
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-gray-200 dark:border-gray-800 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <h3 class="text-lg font-bold text-black dark:text-white">
                            Nuevas Solicitudes
                        </h3>
                        <span class="ml-auto bg-black dark:bg-white text-white dark:text-black text-xs font-bold px-3 py-1 rounded-full">
                            {{ $pendingRequests->count() }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($pendingRequests as $request)
                            <div class="bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 p-5 rounded-lg">
                                <div class="flex items-center gap-3 mb-4 pb-4 border-b-2 border-gray-300 dark:border-gray-700">
                                    @if($request->passenger->avatar)
                                        <img src="{{ asset('storage/' . $request->passenger->avatar) }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center font-bold text-gray-700 dark:text-gray-300">
                                            {{ substr($request->passenger->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-base text-black dark:text-white">{{ $request->passenger->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $request->passenger->email }}</p>
                                    </div>
                                </div>

                                <div class="mb-4 space-y-2 text-sm">
                                    <p class="text-gray-800 dark:text-gray-200 flex items-center gap-2 font-semibold">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        {{ $request->trip->origin_zone }} → {{ $request->trip->destination_zone }}
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $request->trip->departure_time->format('d/m/Y - H:i') }}
                                    </p>
                                </div>

                                <div class="flex gap-2.5 mt-4 pt-4 border-t-2 border-gray-300 dark:border-gray-700">
                                    <form action="{{ route('trips.accept-request', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-black dark:bg-white text-white dark:text-black font-bold py-2.5 px-3 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 text-sm transition">
                                            ✓ Aceptar
                                        </button>
                                    </form>
                                    <form action="{{ route('trips.reject-request', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold py-2.5 px-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-sm transition">
                                            ✕ Rechazar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Viajes Activos -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <h3 class="text-lg font-bold text-black dark:text-white">
                        Tus Viajes Activos
                    </h3>
                    <span class="ml-auto bg-black dark:bg-white text-white dark:text-black text-xs font-bold px-3 py-1 rounded-full">
                        {{ $activeTrips->count() }}
                    </span>
                </div>
                
                @if($activeTrips->isEmpty())
                    <div class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-10 text-center">
                        <p class="text-gray-700 dark:text-gray-300 mb-4 font-bold">No tienes viajes activos</p>
                        <a href="{{ route('trips.create') }}" class="inline-block bg-black dark:bg-white text-white dark:text-black font-bold py-2.5 px-6 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition">
                            + Crear Viaje Nuevo
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($activeTrips as $trip)
                            <div class="border-2 border-gray-300 dark:border-gray-700 hover:border-gray-500 dark:hover:border-gray-600 rounded-lg p-6 transition">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-black dark:text-white flex items-center gap-2">
                                            {{ $trip->origin_zone }} 
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            {{ $trip->destination_zone }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $trip->departure_time->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                    <span class="bg-black dark:bg-white text-white dark:text-black px-3 py-1 rounded-full text-xs font-bold uppercase">
                                        Activo
                                    </span>
                                </div>

                                <div class="grid grid-cols-3 gap-4 mb-5 p-4 bg-gray-200 dark:bg-gray-800 rounded-lg text-center border-2 border-gray-300 dark:border-gray-700">
                                    <div>
                                        <p class="text-2xl font-black text-black dark:text-white">{{ $trip->available_seats }}</p>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Disponibles</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-black text-black dark:text-white">{{ $trip->total_seats }}</p>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Totales</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-black text-black dark:text-white">{{ $trip->requests->where('status', 'accepted')->count() }}</p>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">A Bordo</p>
                                    </div>
                                </div>

                                @if($trip->notes)
                                    <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-800 border-l-4 border-gray-400 dark:border-gray-600 rounded-lg">
                                        <p class="text-sm text-gray-800 dark:text-gray-200 italic">
                                            "{{ $trip->notes }}"
                                        </p>
                                    </div>
                                @endif

                                @if($trip->requests->where('status', 'accepted')->isNotEmpty())
                                    <div class="mt-4 pt-4 border-t-2 border-gray-300 dark:border-gray-700 space-y-2.5">
                                        <p class="text-sm font-bold text-black dark:text-white mb-3">Pasajeros Confirmados:</p>
                                        @foreach($trip->requests->where('status', 'accepted') as $request)
                                            <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-800 p-3 rounded-lg border border-gray-300 dark:border-gray-700">
                                                <div class="flex items-center gap-3">
                                                    @if($request->passenger->avatar)
                                                        <img src="{{ asset('storage/' . $request->passenger->avatar) }}" alt="Avatar" class="h-9 w-9 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                                                    @else
                                                        <div class="h-9 w-9 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm font-bold text-gray-700 dark:text-gray-300">
                                                            {{ substr($request->passenger->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="text-sm font-bold text-black dark:text-white">{{ $request->passenger->name }}</p>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                            {{ $request->passenger->phone }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <span class="text-black dark:text-white text-xs font-bold">✓</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex gap-3 mt-6 pt-4 border-t-2 border-gray-300 dark:border-gray-700">
                                    <button onclick="showTripRouteDriver('{{ $trip->origin_zone }}', '{{ $trip->destination_zone }}')" class="flex-1 bg-black dark:bg-white text-white dark:text-black font-bold py-2.5 px-4 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 text-sm transition">
                                        Ver Ruta
                                    </button>
                                    <button class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold py-2.5 px-4 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 text-sm transition">
                                        Completar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Historial -->
            @if($completedTrips->isNotEmpty())
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-bold text-black dark:text-white mb-6">Historial Reciente</h3>
                    <div class="space-y-3 divide-y-2 divide-gray-300 dark:divide-gray-700">
                        @foreach($completedTrips as $trip)
                            <div class="py-3.5 flex justify-between items-center first:py-0">
                                <div>
                                    <p class="font-bold text-black dark:text-white">
                                        {{ $trip->origin_zone }} → {{ $trip->destination_zone }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $trip->departure_time->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400 bg-gray-200 dark:bg-gray-800 px-3 py-1 rounded-full uppercase">Finalizado</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Ruta -->
    <div id="routeModalDriver" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-lg p-6 max-w-2xl w-full border-2 border-gray-300 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-black dark:text-white">Ruta del Viaje</h3>
                <button onclick="closeRouteModalDriver()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">✕</button>
            </div>
            
            <div id="trip-route-map-driver" class="w-full h-80 rounded-lg border-2 border-gray-300 dark:border-gray-700 mb-4"></div>
            
            <div class="space-y-3 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border-2 border-gray-300 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-700 dark:text-gray-300">Distancia:</span>
                    <span id="route-distance-driver" class="font-bold text-black dark:text-white">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-700 dark:text-gray-300">Duración:</span>
                    <span id="route-duration-driver" class="font-bold text-black dark:text-white">-</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    
    <script>
        let routeMapDriver;
        let routePolylineDriver = null;

        function showTripRouteDriver(origin, destination) {
            document.getElementById('routeModalDriver').classList.remove('hidden');

            setTimeout(() => {
                if (!routeMapDriver) {
                    routeMapDriver = L.map('trip-route-map-driver').setView([-1.2381, -78.6255], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap',
                        maxZoom: 19
                    }).addTo(routeMapDriver);
                }

                fetch(`/api/geocode?zone=${encodeURIComponent(origin)}`)
                    .then(response => response.json())
                    .then(originData => {
                        if (!originData.success) throw new Error('Origin not found');
                        
                        fetch(`/api/geocode?zone=${encodeURIComponent(destination)}`)
                            .then(response => response.json())
                            .then(destData => {
                                if (!destData.success) throw new Error('Destination not found');
                                
                                fetch(`/api/directions?origin=${encodeURIComponent(origin)}&destination=${encodeURIComponent(destination)}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            document.getElementById('route-distance-driver').textContent = data.distance;
                                            document.getElementById('route-duration-driver').textContent = data.duration;

                                            if (routePolylineDriver) routeMapDriver.removeLayer(routePolylineDriver);

                                            const coords = data.geometry.coordinates.map(coord => [coord[1], coord[0]]);
                                            routePolylineDriver = L.polyline(coords, {
                                                color: '#000000',
                                                weight: 3,
                                                opacity: 0.8
                                            }).addTo(routeMapDriver);

                                            const [originLat, originLng] = data.coordinates.origin;
                                            L.marker([originLat, originLng], {
                                                icon: L.icon({
                                                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                                                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                                    iconSize: [25, 41],
                                                    iconAnchor: [12, 41],
                                                    popupAnchor: [1, -34],
                                                    shadowSize: [41, 41]
                                                })
                                            }).addTo(routeMapDriver).bindPopup('Origen');

                                            const [destLat, destLng] = data.coordinates.destination;
                                            L.marker([destLat, destLng], {
                                                icon: L.icon({
                                                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                                                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                                    iconSize: [25, 41],
                                                    iconAnchor: [12, 41],
                                                    popupAnchor: [1, -34],
                                                    shadowSize: [41, 41]
                                                })
                                            }).addTo(routeMapDriver).bindPopup('Destino');

                                            routeMapDriver.fitBounds(routePolylineDriver.getBounds());
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                            })
                            .catch(error => console.error('Error:', error));
                    })
                    .catch(error => console.error('Error:', error));
            }, 100);
        }

        function closeRouteModalDriver() {
            document.getElementById('routeModalDriver').classList.add('hidden');
        }
    </script>
</x-app-layout>