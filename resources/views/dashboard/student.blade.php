<x-app-layout>
    <div class="bg-gray-900 pt-8 pb-16 border-b border-gray-800 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-white">Explorar Viajes</h1>
                <p class="text-sm text-gray-400 mt-1">Encuentra tu próxima ruta al campus o a casa.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <form action="{{ route('dashboard.switch-role') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-white text-gray-900 rounded-lg hover:bg-gray-200 text-sm font-bold transition flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        Modo Conductor
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="pb-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-3">
                <div class="sticky top-6">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 ml-1">Próximos Viajes</h3>
                    
                    @if($confirmedTrips->isEmpty())
                        <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center shadow-sm">
                            <p class="text-sm text-gray-500">Ningún viaje programado</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($confirmedTrips as $request)
                                <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm hover:border-gray-300 transition group cursor-pointer" data-trip-id="{{ $request->trip->id }}">
                                    <p class="font-bold text-base text-gray-900 leading-tight">{{ $request->trip->origin_zone }}</p>
                                    <div class="py-2 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    </div>
                                    <p class="font-bold text-base text-gray-900">{{ $request->trip->destination_zone }}</p>
                                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                        <div>
                                            <p class="text-xs font-bold text-gray-800">{{ $request->trip->driver->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $request->trip->departure_time->format('d M, H:i') }}</p>
                                        </div>
                                    </div>
                                    <button onclick="showTripRoute('{{ $request->trip->origin_zone }}', '{{ $request->trip->destination_zone }}')" class="mt-4 w-full text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 py-2.5 rounded-xl hover:bg-indigo-100 transition">
                                        Ver Mapa
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-8 shadow-sm">
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Origen</label>
                            <input type="text" name="origin_zone" value="{{ request('origin_zone') }}" placeholder="Ej: Centro" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-0 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Destino</label>
                            <input type="text" name="destination_zone" value="{{ request('destination_zone') }}" placeholder="Ej: Campus UTA" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-0 focus:border-blue-500 transition">
                        </div>
                        <div class="sm:col-span-2 flex gap-4 items-end">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Fecha</label>
                                <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-0 focus:border-blue-500 transition">
                            </div>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-xl hover:bg-blue-700 transition shadow-sm border border-blue-700">
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 ml-1">Disponibles Hoy</h3>
                    
                    @if($availableTrips->isEmpty())
                        <div class="border border-dashed border-gray-300 rounded-2xl p-10 text-center bg-gray-50">
                            <p class="text-gray-500 font-medium">No se encontraron rutas para tu búsqueda.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($availableTrips as $trip)
                                <div class="bg-white border border-gray-200 rounded-2xl p-5 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 hover:shadow-md transition shadow-sm">
                                    
                                    <div class="flex items-center gap-4">
                                        @if($trip->driver->avatar)
                                            <img src="/storage/{{ $trip->driver->avatar }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover border border-gray-200 shadow-sm">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-700 border border-gray-200 shadow-sm">
                                                {{ substr($trip->driver->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <p class="font-black text-lg text-gray-900">{{ $trip->origin_zone }}</p>
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                <p class="font-black text-lg text-gray-900">{{ $trip->destination_zone }}</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm text-gray-500">{{ $trip->departure_time->format('H:i • d M') }}</p>
                                                <span class="text-gray-300">•</span>
                                                <p class="text-sm text-gray-500">Por: <span class="font-semibold text-gray-800">{{ $trip->driver->name }}</span></p>
                                                
                                                <button type="button" 
                                                    onclick="showDriverInfo('{{ addslashes($trip->driver->name) }}', '{{ $trip->driver->avatar ? asset('storage/' . $trip->driver->avatar) : '' }}', '{{ $trip->driver->phone ?? 'No registrado' }}', '{{ $trip->vehicle ? addslashes($trip->vehicle->brand) : 'N/A' }}', '{{ $trip->vehicle ? addslashes($trip->vehicle->model) : 'N/A' }}', '{{ $trip->vehicle ? addslashes($trip->vehicle->plate) : 'N/A' }}', '{{ $trip->vehicle ? addslashes($trip->vehicle->color) : 'N/A' }}')" 
                                                    class="ml-1 text-gray-400 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 border border-gray-100 p-1.5 rounded-full transition focus:outline-none shadow-sm" title="Información del viaje">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-5 sm:gap-8 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 border-gray-100 pt-4 sm:pt-0">
                                        <div class="text-center">
                                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Precio</p>
                                            <p class="font-black text-xl text-gray-900">${{ number_format($trip->price, 2) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Plazas</p>
                                            <p class="font-black text-xl text-gray-900">{{ $trip->available_seats }}</p>
                                        </div>

                                        @php
                                            $hasRequested = $myRequests->contains(function($req) use ($trip) {
                                                return $req->trip_id === $trip->id && $req->status !== 'rejected';
                                            });
                                        @endphp

                                        @if($hasRequested)
                                            <button disabled class="bg-amber-50 text-amber-700 font-bold py-3 px-6 rounded-xl cursor-not-allowed border border-amber-200 shadow-sm">
                                                En espera
                                            </button>
                                        @else
                                            <form action="{{ route('trips.request', $trip) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-emerald-700 transition shadow-sm border border-emerald-700">
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
                <div class="sticky top-6">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 ml-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Notificaciones
                    </h3>
                    
                    @if($myRequests->where('status', 'pending')->isEmpty())
                        <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center shadow-sm">
                            <p class="text-sm text-gray-500">Todo al día</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($myRequests->where('status', 'pending') as $request)
                                <div class="p-4 bg-white border border-blue-100 rounded-2xl shadow-sm relative overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                                    <div class="flex items-start gap-3 pl-2">
                                        <div>
                                            <p class="font-bold text-sm text-gray-900">Solicitud enviada</p>
                                            <p class="text-xs text-gray-500 mt-1">Destino: {{ $request->trip->destination_zone }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="routeModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-6 max-w-2xl w-full shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900">Ruta del Viaje</h3>
                <button onclick="closeRouteModal()" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div id="trip-route-map" class="w-full h-80 rounded-xl border border-gray-200 mb-6 bg-gray-50 z-0"></div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Distancia</p>
                    <p id="route-distance" class="font-black text-lg text-gray-900">-</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Duración</p>
                    <p id="route-duration" class="font-black text-lg text-gray-900">-</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    
    <script>
        function showDriverInfo(name, avatar, phone, brand, model, plate, color) {
            let avatarHtml = avatar 
                ? `<img src="${avatar}" class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-md">`
                : `<div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-2xl border-2 border-white shadow-md">${name.charAt(0)}</div>`;

            Swal.fire({
                html: `
                    <div class="text-left font-sans mt-2">
                        <div class="flex items-center gap-4 mb-6">
                            ${avatarHtml}
                            <div>
                                <h3 class="text-xl font-black text-gray-900 leading-tight">${name}</h3>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Conductor U-Ride</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-3 shadow-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-500 uppercase">Vehículo</span>
                                <span class="text-sm font-bold text-gray-900 text-right">${brand} ${model}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-500 uppercase">Placa</span>
                                <span class="text-sm font-mono font-bold bg-white px-2 py-0.5 rounded border border-gray-200 text-gray-800">${plate}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-500 uppercase">Color</span>
                                <span class="text-sm font-bold text-gray-900">${color}</span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                <span class="text-xs font-bold text-gray-500 uppercase">Contacto</span>
                                <span class="text-sm font-mono font-bold text-blue-600">${phone}</span>
                            </div>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'rounded-3xl border border-gray-100 shadow-2xl font-sans',
                    closeButton: 'text-gray-400 hover:text-red-500 focus:outline-none'
                }
            });
        }

        let routeMap; let routePolyline = null;
        function showTripRoute(origin, destination) {
            document.getElementById('routeModal').classList.remove('hidden');
            setTimeout(() => {
                if (!routeMap) {
                    routeMap = L.map('trip-route-map').setView([-1.2381, -78.6255], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap', maxZoom: 19 }).addTo(routeMap);
                }
                fetch(`/api/geocode?zone=${encodeURIComponent(origin)}`).then(res => res.json()).then(oData => {
                    if (!oData.success) throw new Error('Origin not found');
                    fetch(`/api/geocode?zone=${encodeURIComponent(destination)}`).then(res => res.json()).then(dData => {
                        if (!dData.success) throw new Error('Dest not found');
                        fetch(`/api/directions?origin=${encodeURIComponent(origin)}&destination=${encodeURIComponent(destination)}`).then(res => res.json()).then(data => {
                            if (data.success) {
                                document.getElementById('route-distance').textContent = data.distance;
                                document.getElementById('route-duration').textContent = data.duration;
                                if (routePolyline) routeMap.removeLayer(routePolyline);
                                routePolyline = L.polyline(data.geometry.coordinates.map(c => [c[1], c[0]]), {color: '#4F46E5', weight: 4}).addTo(routeMap);
                                routeMap.fitBounds(routePolyline.getBounds());
                            }
                        });
                    });
                });
            }, 100);
        }
        function closeRouteModal() { document.getElementById('routeModal').classList.add('hidden'); }
    </script>
</x-app-layout>