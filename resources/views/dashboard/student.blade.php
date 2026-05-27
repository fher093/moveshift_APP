<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4 max-w-7xl mx-auto w-full">
            <div class="flex items-center gap-3">
                <img src="/images/logo-moveshift.svg" alt="MoveShift" class="h-8 w-8">
                <h2 class="font-bold text-xl text-black dark:text-white">
                    MoveShift
                </h2>
            </div>
            <div class="flex items-center gap-4">
                @if(auth()->user()->avatar)
                    <img src="/storage/{{ auth()->user()->avatar }}" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                @else
                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center font-bold text-gray-700 dark:text-gray-300">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:inline">{{ auth()->user()->name }}</span>
                <form action="{{ route('dashboard.switch-role') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium transition">
                        Conductor
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-white dark:bg-black min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-3">
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-black dark:text-white mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Próximos Viajes
                    </h3>
                    
                    @if($confirmedTrips->isEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400">No tienes viajes programados</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($confirmedTrips as $request)
                                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-600 transition cursor-pointer" data-trip-id="{{ $request->trip->id }}">
                                    <p class="font-bold text-sm text-black dark:text-white leading-tight">
                                        {{ $request->trip->origin_zone }}
                                    </p>
                                    <div class="flex justify-center my-1">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    </div>
                                    <p class="font-bold text-sm text-black dark:text-white">
                                        {{ $request->trip->destination_zone }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $request->trip->driver->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->trip->departure_time->format('d M, H:i') }}</p>
                                    
                                    <button onclick="showTripRoute('{{ $request->trip->origin_zone }}', '{{ $request->trip->destination_zone }}')" class="mt-3 w-full text-xs font-bold text-indigo-700 dark:text-indigo-400 border border-indigo-300 dark:border-indigo-600 px-2 py-1 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                                        Ver Mapa
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-6 mb-6">
                    <h3 class="text-lg font-bold text-black dark:text-white mb-4">Buscar Viaje</h3>
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-2">De</label>
                            <input type="text" name="origin_zone" value="{{ request('origin_zone') }}" placeholder="Ej: Centro" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-2">Hasta</label>
                            <input type="text" name="destination_zone" value="{{ request('destination_zone') }}" placeholder="Ej: Campus" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition">
                        </div>
                        <div class="sm:col-span-2 flex gap-3 items-end">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-2">Fecha</label>
                                <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-black dark:text-white focus:border-gray-600 dark:focus:border-gray-500 transition">
                            </div>
                            <button type="submit" class="bg-blue-600 dark:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-400 transition h-10 flex items-center justify-center">
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-bold text-black dark:text-white mb-4">Viajes Disponibles</h3>
                    
                    @if($availableTrips->isEmpty())
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 text-center">
                            <p class="text-gray-600 dark:text-gray-400 font-medium">No se encontraron viajes</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($availableTrips as $trip)
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:border-gray-400 dark:hover:border-gray-600 transition">
                                    <div class="flex items-center gap-4 w-full sm:w-auto">
                                        @if($trip->driver->avatar)
                                            <img src="/storage/{{ $trip->driver->avatar }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center font-bold text-gray-700 dark:text-gray-300">
                                                {{ substr($trip->driver->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-black dark:text-white">{{ $trip->origin_zone }} → {{ $trip->destination_zone }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $trip->departure_time->format('d M Y, H:i') }} • {{ $trip->driver->name }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                                        <div class="text-center">
                                            <p class="font-bold text-lg text-black dark:text-white">{{ $trip->available_seats }}</p>
                                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Plazas</p>
                                        </div>

                                        @php
                                            $hasRequested = $myRequests->contains(function($req) use ($trip) {
                                                return $req->trip_id === $trip->id && $req->status !== 'rejected';
                                            });
                                        @endphp

                                        @if($hasRequested)
                                            <button disabled class="bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400 font-bold py-2.5 px-5 rounded-lg cursor-not-allowed text-xs border border-green-300 dark:border-green-700">
                                                ✓ Solicitado
                                            </button>
                                        @else
                                            <form action="{{ route('trips.request', $trip) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-blue-600 dark:bg-blue-500 text-white font-bold py-2.5 px-5 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-400 text-xs transition shadow-sm">
                                                    Reservar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-black dark:text-white mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Notificaciones
                    </h3>
                    
                    @if($myRequests->where('status', 'pending')->isEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400">No hay notificaciones nuevas</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($myRequests->where('status', 'pending') as $request)
                                <div class="bg-white dark:bg-gray-800 border-l-4 border-gray-400 dark:border-gray-600 p-4 rounded-lg">
                                    <p class="font-bold text-sm text-black dark:text-white">⏳ Solicitud Enviada</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Para {{ $request->trip->destination_zone }}</p>
                                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 mt-2 uppercase">Esperando respuesta</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div id="routeModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-lg p-6 max-w-2xl w-full border-2 border-gray-300 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-black dark:text-white">Ruta del Viaje</h3>
                <button onclick="closeRouteModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">✕</button>
            </div>
            
            <div id="trip-route-map" class="w-full h-80 rounded-lg border-2 border-gray-300 dark:border-gray-700 mb-4"></div>
            
            <div class="space-y-3 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-700 dark:text-gray-300">Distancia:</span>
                    <span id="route-distance" class="font-bold text-black dark:text-white">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-700 dark:text-gray-300">Duración:</span>
                    <span id="route-duration" class="font-bold text-black dark:text-white">-</span>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    
    <script>
        let routeMap;
        let routePolyline = null;

        function showTripRoute(origin, destination) {
            document.getElementById('routeModal').classList.remove('hidden');

            setTimeout(() => {
                if (!routeMap) {
                    routeMap = L.map('trip-route-map').setView([-1.2381, -78.6255], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap',
                        maxZoom: 19
                    }).addTo(routeMap);
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
                                            document.getElementById('route-distance').textContent = data.distance;
                                            document.getElementById('route-duration').textContent = data.duration;

                                            if (routePolyline) routeMap.removeLayer(routePolyline);

                                            const coords = data.geometry.coordinates.map(coord => [coord[1], coord[0]]);
                                            routePolyline = L.polyline(coords, {
                                                color: '#000000',
                                                weight: 3,
                                                opacity: 0.8
                                            }).addTo(routeMap);

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
                                            }).addTo(routeMap).bindPopup('Origen');

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
                                            }).addTo(routeMap).bindPopup('Destino');

                                            routeMap.fitBounds(routePolyline.getBounds());
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                            })
                            .catch(error => console.error('Error:', error));
                    })
                    .catch(error => console.error('Error:', error));
            }, 100);
        }

        function closeRouteModal() {
            document.getElementById('routeModal').classList.add('hidden');
        }
    </script>
</x-app-layout>