<x-app-layout>
    <div class="bg-gray-900 pt-8 pb-16 border-b border-gray-800 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-start sm:items-end">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-white">Panel de Conductor</h1>
                <p class="text-sm text-gray-400 mt-1">Gestiona tus rutas, vehículos y pasajeros.</p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3">
                <a href="{{ route('trips.create') }}" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-bold transition shadow-sm border border-blue-700">
                    Nuevo Viaje
                </a>
                <form action="{{ route('dashboard.switch-role') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-white text-gray-900 rounded-lg hover:bg-gray-200 text-sm font-bold transition shadow-sm">
                        Modo Pasajero
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="pb-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 space-y-8">
            
            <section>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Mis Vehículos</h3>
                    <button type="button" onclick="toggleVehicleForm()" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                        + Registrar otro
                    </button>
                </div>

                @if($vehicles->isEmpty())
                    <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-8 text-center shadow-sm">
                        <p class="text-gray-500 font-medium mb-4">Aún no tienes vehículos para tus viajes.</p>
                        <button type="button" onclick="toggleVehicleForm()" class="px-6 py-2 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition shadow-sm">
                            Registrar ahora
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($vehicles as $vehicle)
                            <div class="p-6 rounded-2xl border bg-white shadow-sm {{ $vehicle->is_active ? 'border-2 border-blue-500' : 'border border-gray-200 opacity-80' }}">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-black text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                                        <p class="text-xs text-gray-500 mt-1 font-mono uppercase">{{ $vehicle->plate }} • {{ $vehicle->color }}</p>
                                    </div>
                                    @if($vehicle->is_active)
                                        <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider border border-blue-200">Activo</span>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    @if(!$vehicle->is_active)
                                        <form action="{{ route('vehicles.switch', $vehicle->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-2 bg-gray-100 text-gray-800 border border-gray-200 text-xs font-bold rounded-lg hover:bg-gray-200 transition">
                                                Usar
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" onclick="editVehicle({{ $vehicle->id }}, '{{ $vehicle->brand }}', '{{ $vehicle->model }}', '{{ $vehicle->plate }}', '{{ $vehicle->color }}')" class="flex-1 py-2 border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-50 transition">
                                        Editar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div id="vehicleForm" class="hidden mt-6 bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
                    <h4 class="text-lg font-black text-gray-900 mb-6" id="formTitle">Detalles del Vehículo</h4>
                    <form action="{{ route('vehicles.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @csrf
                        <input type="hidden" name="vehicle_id" id="vehicleId" value="">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Marca</label>
                            <input type="text" name="brand" id="brand" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:border-blue-500 focus:ring-0 transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Modelo</label>
                            <input type="text" name="model" id="model" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:border-blue-500 focus:ring-0 transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Placa</label>
                            <input type="text" name="plate" id="plate" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:border-blue-500 focus:ring-0 uppercase transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Color</label>
                            <input type="text" name="color" id="color" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:border-blue-500 focus:ring-0 transition" required>
                        </div>
                        <div class="sm:col-span-2 flex justify-end gap-3 mt-4">
                            <button type="button" onclick="toggleVehicleForm()" class="px-6 py-3 font-bold text-gray-500 hover:text-gray-900 transition">Cancelar</button>
                            <button type="submit" class="px-8 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition shadow-sm">Guardar</button>
                        </div>
                    </form>
                </div>
            </section>

            @if($pendingRequests->isNotEmpty())
                <section>
                    <div class="flex items-center gap-3 mb-4 px-1">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Nuevas Solicitudes</h3>
                        <span class="bg-amber-100 text-amber-700 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingRequests->count() }} pendientes</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($pendingRequests as $request)
                            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center gap-4">
                                        @if($request->passenger->avatar)
                                            <img src="/storage/{{ $request->passenger->avatar }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover border border-gray-200">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-700 border border-gray-200">
                                                {{ substr($request->passenger->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $request->passenger->name }}</p>
                                            <p class="text-xs text-gray-500 font-mono">{{ $request->passenger->phone }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Destino</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $request->trip->destination_zone }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <form action="{{ route('trips.accept-request', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-emerald-500 text-white font-bold py-2.5 rounded-xl hover:bg-emerald-600 text-sm transition shadow-sm">
                                            Aceptar
                                        </button>
                                    </form>
                                    <form action="{{ route('trips.reject-request', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-50 text-red-600 border border-red-200 font-bold py-2.5 rounded-xl hover:bg-red-100 text-sm transition">
                                            Rechazar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <section>
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 ml-1">Mis Rutas Activas</h3>
                
                @if($activeTrips->isEmpty())
                    <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-10 text-center shadow-sm">
                        <p class="text-gray-500 font-medium">No estás manejando en este momento.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($activeTrips as $trip)
                            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                                <div class="p-6 sm:p-8 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="text-2xl font-black text-gray-900">{{ $trip->origin_zone }}</h4>
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            <h4 class="text-2xl font-black text-gray-900">{{ $trip->destination_zone }}</h4>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">{{ $trip->departure_time->format('d M • H:i') }}</p>
                                    </div>
                                    <div class="flex gap-6 text-center">
                                        <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                                            <p class="font-black text-xl text-gray-900">${{ number_format($trip->price, 2) }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Cobro</p>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                                            <p class="font-black text-xl text-gray-900">{{ $trip->available_seats }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Plazas</p>
                                        </div>
                                    </div>
                                </div>

                                @if($trip->requests->where('status', 'accepted')->isNotEmpty())
                                    <div class="p-6 sm:p-8 bg-indigo-50/50 border-b border-gray-100">
                                        <h5 class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-4">A bordo ({{ $trip->requests->where('status', 'accepted')->count() }})</h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($trip->requests->where('status', 'accepted') as $request)
                                                <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-indigo-100 shadow-sm">
                                                    @if($request->passenger->avatar)
                                                        <img src="/storage/{{ $request->passenger->avatar }}" alt="Avatar" class="h-8 w-8 rounded-full border border-gray-200">
                                                    @else
                                                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700">
                                                            {{ substr($request->passenger->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="text-sm font-bold text-gray-900">{{ $request->passenger->name }}</p>
                                                        <p class="text-xs text-indigo-600 font-mono font-bold">{{ $request->passenger->phone }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="p-6 flex flex-col sm:flex-row gap-3">
                                    <button onclick="showTripRouteDriver('{{ $trip->origin_zone }}', '{{ $trip->destination_zone }}')" class="flex-1 bg-gray-50 text-gray-700 border border-gray-200 font-bold py-3.5 px-4 rounded-xl hover:bg-gray-100 transition">
                                        Ver Ruta
                                    </button>
                                    <button class="flex-1 bg-teal-600 text-white border border-teal-700 font-bold py-3.5 px-4 rounded-xl hover:bg-teal-700 transition shadow-sm">
                                        Finalizar y Cobrar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>

    <div id="routeModalDriver" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-6 max-w-2xl w-full shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900">Ruta del Viaje</h3>
                <button onclick="closeRouteModalDriver()" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div id="trip-route-map-driver" class="w-full h-80 rounded-xl border border-gray-200 mb-6 bg-gray-50 z-0"></div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Distancia</p>
                    <p id="route-distance-driver" class="font-black text-lg text-gray-900">-</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Duración</p>
                    <p id="route-duration-driver" class="font-black text-lg text-gray-900">-</p>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
    function toggleVehicleForm() {
        const form = document.getElementById('vehicleForm');
        const title = document.getElementById('formTitle');
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            document.getElementById('vehicleId').value = '';
            document.getElementById('brand').value = '';
            document.getElementById('model').value = '';
            document.getElementById('plate').value = '';
            document.getElementById('color').value = '';
            title.textContent = 'Registrar Vehículo';
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

    let routeMapDriver; let routePolylineDriver = null;
    function showTripRouteDriver(origin, destination) {
        document.getElementById('routeModalDriver').classList.remove('hidden');
        setTimeout(() => {
            if (!routeMapDriver) {
                routeMapDriver = L.map('trip-route-map-driver').setView([-1.2381, -78.6255], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap', maxZoom: 19 }).addTo(routeMapDriver);
            }
            fetch(`/api/geocode?zone=${encodeURIComponent(origin)}`).then(res => res.json()).then(oData => {
                if (!oData.success) throw new Error('Origin not found');
                fetch(`/api/geocode?zone=${encodeURIComponent(destination)}`).then(res => res.json()).then(dData => {
                    if (!dData.success) throw new Error('Dest not found');
                    fetch(`/api/directions?origin=${encodeURIComponent(origin)}&destination=${encodeURIComponent(destination)}`).then(res => res.json()).then(data => {
                        if (data.success) {
                            document.getElementById('route-distance-driver').textContent = data.distance;
                            document.getElementById('route-duration-driver').textContent = data.duration;
                            if (routePolylineDriver) routeMapDriver.removeLayer(routePolylineDriver);
                            routePolylineDriver = L.polyline(data.geometry.coordinates.map(c => [c[1], c[0]]), {color: '#4F46E5', weight: 4}).addTo(routeMapDriver);
                            routeMapDriver.fitBounds(routePolylineDriver.getBounds());
                        }
                    });
                });
            });
        }, 100);
    }
    function closeRouteModalDriver() { document.getElementById('routeModalDriver').classList.add('hidden'); }
    </script>
</x-app-layout>