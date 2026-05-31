<x-app-layout>
    <div class="bg-gray-900 pt-8 pb-8 border-b border-gray-800 font-sans">
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

    <div class="pt-16 pb-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 space-y-8">
            
            <!-- SECCIÓN VEHÍCULOS -->
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

            <!-- SECCIÓN SOLICITUDES PENDIENTES -->
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

            <!-- SECCIÓN RUTAS ACTIVAS -->
            <section>
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 ml-1">Mis Rutas Activas</h3>
                
                @if($activeTrips->isEmpty())
                    <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-10 text-center shadow-sm">
                        <p class="text-gray-500 font-medium">No estás manejando en este momento.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($activeTrips as $trip)
                            <div id="trip-card-{{ $trip->id }}" class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm transition-all duration-300">
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
                                    
                                    <form id="complete-trip-form-{{ $trip->id }}" action="{{ route('trips.complete', $trip) }}" method="POST" class="hidden">
                                        @csrf
                                        <!-- AQUÍ SE AGREGARÁ EL TEXTAREA DINÁMICAMENTE SI HAY REPORTE -->
                                    </form>
                                    <button type="button" onclick="finishTrip({{ $trip->id }})" class="flex-1 bg-teal-600 text-white border border-teal-700 font-bold py-3.5 px-4 rounded-xl hover:bg-teal-700 transition shadow-sm">
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

    <!-- MODAL MAPA DRIVER -->
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

    <!-- SCRIPTS Y LIBRERÍAS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    
    <script>
    // FUNCIONES BÁSICAS DE INTERFAZ
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

    // ====================================================
    // LÓGICA FINALIZAR VIAJE CON REPORTE DEL CONDUCTOR
    // ====================================================
    function finishTrip(tripId) {
        Swal.fire({
            title: '¡Viaje Completado!',
            html: `
                <div class="text-left mt-2 font-sans">
                    <p class="text-sm text-gray-600 mb-6 text-center">Recuerda realizar el cobro directamente con tus pasajeros en efectivo.</p>
                    
                    <div id="report-section-driver-${tripId}" class="hidden mb-4 transition-all">
                        <label class="block text-xs font-bold text-red-600 uppercase mb-2 tracking-wider">Reportar una incidencia</label>
                        <textarea id="driver-report-text-${tripId}" rows="3" class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-red-500 focus:border-red-500 bg-gray-50" placeholder="Ej: Un pasajero tuvo mal comportamiento, dañó algo, no se presentó..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="button" id="btn-show-report-driver-${tripId}" class="text-xs font-bold text-gray-400 hover:text-red-500 transition underline focus:outline-none">
                            Hubo un problema en el viaje, hacer un reporte
                        </button>
                    </div>
                </div>
            `,
            icon: 'success',
            iconColor: '#0D9488',
            showCancelButton: true,
            confirmButtonText: 'Confirmar y Finalizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#0D9488',
            cancelButtonColor: '#9CA3AF',
            customClass: {
                popup: 'rounded-3xl border border-gray-100 shadow-2xl font-sans',
                title: 'font-black text-gray-900 text-2xl',
                confirmButton: 'font-bold py-3 px-6 rounded-xl text-white',
                cancelButton: 'font-bold py-3 px-6 rounded-xl text-white'
            },
            didOpen: () => {
                const btnReport = document.getElementById(`btn-show-report-driver-${tripId}`);
                const reportSection = document.getElementById(`report-section-driver-${tripId}`);
                const confirmBtn = Swal.getConfirmButton();
                const icon = document.querySelector('.swal2-icon.swal2-success');

                btnReport.addEventListener('click', (e) => {
                    e.preventDefault();
                    reportSection.classList.remove('hidden');
                    btnReport.classList.add('hidden');
                    
                    // Cambiamos a modo advertencia
                    confirmBtn.style.backgroundColor = '#DC2626';
                    confirmBtn.textContent = 'Finalizar y Enviar Reporte';
                    
                    if(icon) {
                        icon.style.display = 'none'; // ocultamos el visto verde porque ahora es un reporte
                    }
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('complete-trip-form-' + tripId);
                const reportText = document.getElementById(`driver-report-text-${tripId}`).value;
                
                // Si el conductor escribió algo, inyectamos el input en el formulario antes de enviarlo
                if (reportText.trim() !== '') {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'driver_report';
                    input.value = reportText;
                    form.appendChild(input);
                }
                
                form.submit();
            }
        });
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

    // ====================================================
    // POLLING COMPLETO PARA CONDUCTORES
    // ====================================================
    let seenDriverNotifications = {
        new_requests: JSON.parse(localStorage.getItem('seen_driver_requests') || '[]'),
        soon_trips: JSON.parse(localStorage.getItem('seen_driver_soon') || '[]'),
        active_trips_updated: JSON.parse(localStorage.getItem('seen_driver_active') || '{}')
    };
    let driverPollingInterval = null;

    function startDriverPolling() {
        driverPollingInterval = setInterval(checkDriverNotifications, 3000);
        checkDriverNotifications();
    }

    function checkDriverNotifications() {
        fetch('/api/driver/notifications')
            .then(response => response.json())
            .then(data => {
                if (!data.success) return;

                // Nuevas solicitudes
                data.new_requests.forEach(notif => {
                    if (!seenDriverNotifications.new_requests.includes(notif.request_id)) {
                        showNewRequestAlert(notif);
                        seenDriverNotifications.new_requests.push(notif.request_id);
                        localStorage.setItem('seen_driver_requests', JSON.stringify(seenDriverNotifications.new_requests));
                    }
                });

                // Viajes por partir
                data.soon_trips.forEach(trip => {
                    if (!seenDriverNotifications.soon_trips.includes(trip.trip_id)) {
                        showSoonTripAlert(trip);
                        seenDriverNotifications.soon_trips.push(trip.trip_id);
                        localStorage.setItem('seen_driver_soon', JSON.stringify(seenDriverNotifications.soon_trips));
                    }
                });

                // Cambios en viajes activos
                data.active_trips.forEach(trip => {
                    let key = `trip-${trip.trip_id}`;
                    if (!seenDriverNotifications.active_trips_updated[key]) {
                        seenDriverNotifications.active_trips_updated[key] = {
                            passengers: trip.passengers_accepted,
                            available: trip.available_seats
                        };
                    } else {
                        if (trip.passengers_accepted > seenDriverNotifications.active_trips_updated[key].passengers) {
                            showNewPassengerAlert(trip);
                        }
                    }
                    seenDriverNotifications.active_trips_updated[key] = {
                        passengers: trip.passengers_accepted,
                        available: trip.available_seats
                    };
                    localStorage.setItem('seen_driver_active', JSON.stringify(seenDriverNotifications.active_trips_updated));
                });
            })
            .catch(error => console.error('Driver polling error:', error));
    }

    function showNewRequestAlert(notif) {
        Swal.fire({
            title: '📲 Nueva Solicitud',
            html: `
                <div class="text-left mt-2 font-sans">
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 mb-4">
                        <p class="text-lg font-black text-blue-900">${notif.passenger}</p>
                        <p class="text-xs font-bold text-blue-600 uppercase mt-2">Solicita ir a</p>
                        <p class="text-base font-bold text-blue-800">${notif.destination}</p>
                    </div>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Ver solicitud',
            cancelButtonText: 'Después',
            showCancelButton: true,
            confirmButtonColor: '#0284C7',
            cancelButtonColor: '#9CA3AF',
            customClass: {
                popup: 'rounded-3xl border border-gray-100 shadow-2xl font-sans',
                title: 'font-black text-gray-900'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = window.location.href; 
            }
        });
    }

    function showSoonTripAlert(trip) {
        Swal.fire({
            title: '🚗 ¡Viaje por partir!',
            html: `
                <div class="text-left mt-2 font-sans">
                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-200">
                        <p class="text-sm text-amber-900">
                            <strong>${trip.origin}</strong> → <strong>${trip.destination}</strong>
                        </p>
                        <p class="text-xs font-bold text-amber-600 uppercase mt-3">Pasajeros a bordo</p>
                        <p class="text-2xl font-black text-amber-700">${trip.passengers_accepted}/${trip.passengers_accepted + trip.available_seats}</p>
                    </div>
                </div>
            `,
            icon: 'warning',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#D97706',
            customClass: {
                popup: 'rounded-3xl border border-gray-100 shadow-2xl font-sans',
                title: 'font-black text-gray-900'
            }
        });
    }

    function showNewPassengerAlert(trip) {
        Swal.fire({
            title: '✅ Nuevo Pasajero Confirmado',
            html: `
                <div class="text-left mt-2 font-sans">
                    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200">
                        <p class="text-sm text-emerald-900 mb-3">
                            <strong>${trip.destination}</strong>
                        </p>
                        <p class="text-xs font-bold text-emerald-600 uppercase">Ocupación actual</p>
                        <p class="text-2xl font-black text-emerald-700">${trip.passengers_accepted} / ${trip.passengers_accepted + trip.available_seats}</p>
                        <p class="text-xs font-bold text-emerald-600 uppercase mt-3">${trip.available_seats} plaza(s) disponible(s)</p>
                    </div>
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#0D9488',
            customClass: {
                popup: 'rounded-3xl border border-gray-100 shadow-2xl font-sans',
                title: 'font-black text-gray-900'
            },
            timer: 4000,
            timerProgressBar: true
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        startDriverPolling();
    });

    window.addEventListener('beforeunload', function() {
        if (driverPollingInterval) clearInterval(driverPollingInterval);
    });
    </script> 
    @if(session('sweet_warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Advertencia Oficial!',
                    text: '{{ session('sweet_warning') }}',
                    icon: 'error', // Icono rojo
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#DC2626', // Rojo
                    allowOutsideClick: false,
                    customClass: {
                        popup: 'rounded-3xl border border-red-100 shadow-2xl font-sans',
                        title: 'font-black text-red-600',
                        confirmButton: 'font-bold py-3 px-6 rounded-xl text-white'
                    }
                });
            });
        </script>
    @endif
</x-app-layout>