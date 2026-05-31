<x-app-layout>
    <div class="flex min-h-screen bg-gray-50 font-sans">
        
        <aside class="w-64 bg-gray-900 text-white flex flex-col justify-between hidden md:flex">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center font-black text-gray-950 text-sm shadow-sm">
                        M
                    </div>
                    <span class="font-black text-lg tracking-tight">U-Ride Admin</span>
                </div>
                
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 ml-2">Moderación</p>
                <nav class="space-y-2">
                    <a href="#" onclick="switchTab('drivers-reports', event)" id="tab-drivers-reports" class="flex items-center gap-3 px-4 py-3 bg-gray-800 text-white rounded-xl font-bold text-sm transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Reportes de Choferes
                    </a>
                    
                    <a href="#" onclick="switchTab('students-reports', event)" id="tab-students-reports" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl font-bold text-sm transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Reportes de Pasajeros
                    </a>

                    <a href="#" onclick="switchTab('drivers-list', event)" id="tab-drivers-list" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl font-bold text-sm transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Control de Conductores
                    </a>
                </nav>
            </div>
            
            <div class="p-6 border-t border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gray-700 flex items-center justify-center font-bold text-sm shadow-inner">
                        A
                    </div>
                    <div>
                        <p class="text-xs font-bold leading-none text-white">SuperAdmin</p>
                        <p class="text-[10px] text-gray-400 mt-1">admin@uta.edu.ec</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-6 md:p-10">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 pb-4 border-b border-gray-200">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900">Centro de Moderación</h1>
                    <p class="text-sm text-gray-500 mt-1">Revisa las métricas, quejas y el rendimiento de la comunidad de U-Ride.</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 id="table-title" class="text-sm font-bold text-gray-400 uppercase tracking-wider">Denuncias contra Conductores</h3>
                    <span class="bg-red-50 text-red-700 border border-red-200 text-xs font-bold px-2.5 py-0.5 rounded-full">Acción Requerida</span>
                </div>

                <div id="content-drivers-reports" class="block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/50">
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider pl-6">Conductor Reportado</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Reportado por</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Motivo / Crítica</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right pr-6">Acciones Administrativas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($driverReports ?? [] as $report)
                                <tr id="report-driver-{{ $report->id }}" class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 pl-6 font-bold text-gray-900">
                                        {{ $report->toUser->name }}
                                        @if($report->toUser->account_status === 'suspended')
                                            <span class="ml-2 bg-red-100 text-red-700 px-2 py-0.5 rounded text-[10px] uppercase">Suspendido</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-gray-600">{{ $report->fromUser->name }}</td>
                                    <td class="p-4 text-gray-600 max-w-xs truncate" title="{{ $report->review }}">{{ $report->review }}</td>
                                    <td class="p-4 text-right pr-6 space-x-2">
                                        
                                        <button type="button" onclick="applySancion('driver', {{ $report->id }}, {{ $report->to_user_id }}, '{{ addslashes($report->toUser->name) }}', 'advertir')" class="text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-xl hover:bg-amber-100 transition shadow-sm">Advertir</button>
                                        
                                        @if($report->toUser->account_status === 'suspended')
                                            <button type="button" onclick="applySancion('driver', {{ $report->id }}, {{ $report->to_user_id }}, '{{ addslashes($report->toUser->name) }}', 'levantar')" class="text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-xl hover:bg-emerald-100 transition shadow-sm">Levantar Suspensión</button>
                                        @else
                                            <button type="button" onclick="applySancion('driver', {{ $report->id }}, {{ $report->to_user_id }}, '{{ addslashes($report->toUser->name) }}', 'suspender')" class="text-xs font-bold bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-xl hover:bg-red-100 transition shadow-sm">Suspender</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500">No hay reportes contra conductores.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="content-students-reports" class="hidden overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/50">
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider pl-6">Pasajero Reportado</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Reportado por</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Motivo / Queja</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right pr-6">Acciones Administrativas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($studentReports ?? [] as $report)
                                <tr id="report-student-{{ $report->id }}" class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 pl-6 font-bold text-gray-900">
                                        {{ $report->toUser->name }}
                                        @if($report->toUser->account_status === 'suspended')
                                            <span class="ml-2 bg-red-100 text-red-700 px-2 py-0.5 rounded text-[10px] uppercase">Suspendido</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-gray-600">{{ $report->fromUser->name }}</td>
                                    <td class="p-4 text-gray-600 max-w-xs truncate" title="{{ $report->review }}">{{ $report->review }}</td>
                                    <td class="p-4 text-right pr-6 space-x-2">
                                        
                                        <button type="button" onclick="applySancion('student', {{ $report->id }}, {{ $report->to_user_id }}, '{{ addslashes($report->toUser->name) }}', 'advertir')" class="text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-xl hover:bg-amber-100 transition shadow-sm">Advertir</button>
                                        
                                        @if($report->toUser->account_status === 'suspended')
                                            <button type="button" onclick="applySancion('student', {{ $report->id }}, {{ $report->to_user_id }}, '{{ addslashes($report->toUser->name) }}', 'levantar')" class="text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-xl hover:bg-emerald-100 transition shadow-sm">Levantar Suspensión</button>
                                        @else
                                            <button type="button" onclick="applySancion('student', {{ $report->id }}, {{ $report->to_user_id }}, '{{ addslashes($report->toUser->name) }}', 'suspender')" class="text-xs font-bold bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-xl hover:bg-red-100 transition shadow-sm">Suspender</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500">No hay reportes contra pasajeros.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="content-drivers-list" class="hidden overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/50">
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider pl-6">Nombre de Conductor</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Contacto</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Viajes Hechos</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Calificación Promedio</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right pr-6">Acción Rápida</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($driversPerformance ?? [] as $driver)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 pl-6">
                                        <p class="font-bold text-gray-900">
                                            {{ $driver->name }} {{ $driver->last_name }}
                                            @if($driver->account_status === 'suspended')
                                                <span class="ml-2 bg-red-100 text-red-700 px-2 py-0.5 rounded text-[10px] uppercase">Suspendido</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $driver->email }}</p>
                                    </td>
                                    <td class="p-4 font-mono text-gray-600">{{ $driver->phone ?? 'Sin Teléfono' }}</td>
                                    <td class="p-4 text-center font-bold text-gray-900">{{ $driver->completed_trips_count }} viajes</td>
                                    <td class="p-4 text-center">
                                        @if($driver->rating_average)
                                            <div class="flex items-center justify-center gap-1">
                                                <span class="font-black text-gray-900">{{ number_format($driver->rating_average, 1) }}</span>
                                                <span class="text-yellow-400 text-base">★</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Sin calificaciones</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right pr-6">
                                        @if($driver->account_status === 'suspended')
                                            <button type="button" onclick="applySancion('driver-list', {{ $driver->id }}, {{ $driver->id }}, '{{ addslashes($driver->name) }}', 'levantar')" class="text-xs font-bold border border-emerald-200 text-emerald-700 hover:bg-emerald-50 px-3 py-1.5 rounded-xl transition">
                                                Levantar Suspensión
                                            </button>
                                        @else
                                            <button type="button" onclick="applySancion('driver-list', {{ $driver->id }}, {{ $driver->id }}, '{{ addslashes($driver->name) }}', 'suspender')" class="text-xs font-bold border border-gray-200 text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-xl transition">
                                                Suspender Chofer
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500 font-medium bg-gray-50/50">
                                        No hay conductores registrados en la plataforma.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function switchTab(tabId, event) {
            if(event) event.preventDefault();

            document.getElementById('content-drivers-reports').classList.add('hidden');
            document.getElementById('content-students-reports').classList.add('hidden');
            document.getElementById('content-drivers-list').classList.add('hidden');

            const inactiveClass = "flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl font-bold text-sm transition";
            document.getElementById('tab-drivers-reports').className = inactiveClass;
            document.getElementById('tab-students-reports').className = inactiveClass;
            document.getElementById('tab-drivers-list').className = inactiveClass;

            const activeClass = "flex items-center gap-3 px-4 py-3 bg-gray-800 text-white rounded-xl font-bold text-sm transition shadow-sm";
            document.getElementById('content-' + tabId).classList.remove('hidden');
            document.getElementById('tab-' + tabId).className = activeClass;
            
            const titleElement = document.getElementById('table-title');
            if (tabId === 'drivers-reports') {
                titleElement.textContent = 'Denuncias contra Conductores';
            } else if (tabId === 'students-reports') {
                titleElement.textContent = 'Denuncias contra Estudiantes';
            } else if (tabId === 'drivers-list') {
                titleElement.textContent = 'Historial y Rendimiento de Conductores';
            }
        }

        // AHORA LA FUNCIÓN RECIBE EL userId CORRECTAMENTE
        function applySancion(type, rowId, userId, userName, actionType) {
            let title = ''; let text = ''; let confirmColor = '';
            
            if (actionType === 'advertir') {
                title = '¿Enviar Advertencia?';
                text = `Se le mostrará un llamado de atención a ${userName} apenas abra la aplicación.`;
                confirmColor = '#D97706';
            } else if (actionType === 'suspender') {
                title = '¿Suspender Usuario?';
                text = `Se bloqueará la cuenta de ${userName} por 2 horas inmediatas.`;
                confirmColor = '#DC2626';
            } else if (actionType === 'levantar') {
                title = '¿Levantar Suspensión?';
                text = `Se le devolverá el acceso inmediato a ${userName}.`;
                confirmColor = '#059669'; // Verde
            }

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                iconColor: confirmColor,
                showCancelButton: true,
                confirmButtonText: 'Sí, Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#9CA3AF',
                customClass: {
                    popup: 'rounded-3xl border border-gray-100 shadow-2xl font-sans',
                    title: 'font-black text-gray-900',
                    confirmButton: 'font-bold py-3 px-6 rounded-xl text-white',
                    cancelButton: 'font-bold py-3 px-6 rounded-xl text-white'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // LLAMADA AL BACKEND DE LARAVEL PARA CAMBIAR EL ESTADO
                    fetch(`/admin/users/${userId}/sancion`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ action: actionType })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire({
                                title: '¡Acción Aplicada!',
                                text: 'El sistema ha sido actualizado con éxito.',
                                icon: 'success',
                                confirmButtonColor: '#000',
                                customClass: { popup: 'rounded-3xl font-sans', title: 'font-black' }
                            }).then(() => {
                                // Recargar la página para ver el botón nuevo ("Levantar" o "Suspender")
                                window.location.reload(); 
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Hubo un problema al procesar la solicitud.', 'error');
                    });
                }
            });
        }
    </script>
</x-app-layout>