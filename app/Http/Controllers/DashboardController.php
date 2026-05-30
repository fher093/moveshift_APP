<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripRequest;
use App\Models\Rating;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard según el rol del usuario
     */
    public function index(): View
    {
        $user = auth()->user();
        $role = $user->role ?? 'student';

        if ($role === 'driver') {
            return $this->driverDashboard();
        } else {
            return $this->studentDashboard();
        }
    }

    /**
     * Dashboard para conductor
     */
    private function driverDashboard(): View
    {
        $user = auth()->user();
        
        // Viajes activos del conductor
        $activeTrips = Trip::where('driver_id', $user->id)
            ->where('status', 'active')
            ->with('requests.passenger', 'vehicle')
            ->latest('departure_time')
            ->get();

        // Solicitudes pendientes
        $pendingRequests = TripRequest::whereHas('trip', function ($query) use ($user) {
            $query->where('driver_id', $user->id);
        })
            ->where('status', 'pending')
            ->with('passenger', 'trip')
            ->latest()
            ->get();

        // Viajes completados
        $completedTrips = Trip::where('driver_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        // Vehículos del conductor
        $vehicles = $user->vehicles()->get();
        $activeVehicle = $user->activeVehicle();

        return view('dashboard.driver', compact('activeTrips', 'pendingRequests', 'completedTrips', 'vehicles', 'activeVehicle'));
    }

    /**
     * Dashboard para estudiante
     */
    private function studentDashboard(): View
    {
        $user = auth()->user();
        
        // Búsqueda y filtrado de viajes
        $query = Trip::where('status', 'active')
            ->where('available_seats', '>', 0)
            ->with('driver', 'vehicle');

        // Filtros
        if (request('origin_zone')) {
            $query->where('origin_zone', 'like', '%' . request('origin_zone') . '%');
        }

        if (request('destination_zone')) {
            $query->where('destination_zone', 'like', '%' . request('destination_zone') . '%');
        }

        if (request('date')) {
            $query->whereDate('departure_time', request('date'));
        }

        $availableTrips = $query->latest('departure_time')->get();

        // Mis solicitudes de viaje
        $myRequests = TripRequest::where('passenger_id', $user->id)
            ->with('trip.driver', 'trip.vehicle')
            ->latest()
            ->get();

        // Mis viajes confirmados
        $confirmedTrips = TripRequest::where('passenger_id', $user->id)
            ->where('status', 'accepted')
            ->with('trip.driver', 'trip.vehicle')
            ->latest()
            ->get();

        return view('dashboard.student', compact('availableTrips', 'myRequests', 'confirmedTrips'));
    }

    /**
     * RF3: Crear un nuevo viaje (conductor)
     */
    public function createTrip(): View
    {
        $user = auth()->user();
        $vehicles = $user->vehicles()->get();
        $activeVehicle = $user->activeVehicle();

        return view('trips.create', compact('vehicles', 'activeVehicle'));
    }

    public function storeTrip(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'origin_zone' => 'required|string|max:255',
            'destination_zone' => 'required|string|max:255',
            'departure_time' => 'required|date|after:now',
            'total_seats' => 'required|integer|min:1|max:8',
            'price' => 'required|numeric|min:0|max:999.99',
            'vehicle_id' => 'required|exists:vehicles,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verificar que el vehículo le pertenece al conductor
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->driver_id !== auth()->id()) {
            return back()->with('error', 'Este vehículo no te pertenece');
        }

        $validated['driver_id'] = auth()->id();
        $validated['available_seats'] = $validated['total_seats'];

        Trip::create($validated);

        return redirect()->route('dashboard')->with('success', 'Viaje creado exitosamente');
    }

    /**
     * RF5: Solicitar unirse a un viaje (pasajero)
     */
    public function requestTrip(Trip $trip): RedirectResponse
    {
        $user = auth()->user();

        // Verificar si ya existe una solicitud
        $existingRequest = TripRequest::where('trip_id', $trip->id)
            ->where('passenger_id', $user->id)
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Ya has solicitado unirte a este viaje');
        }

        // Crear solicitud
        TripRequest::create([
            'trip_id' => $trip->id,
            'passenger_id' => $user->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Solicitud enviada al conductor');
    }

    /**
     * RF6: Gestionar solicitudes (aceptar/rechazar)
     */
    public function acceptRequest($tripRequestId): RedirectResponse
    {
        $tripRequest = TripRequest::findOrFail($tripRequestId);
        $trip = $tripRequest->trip;

        // Verificar que el usuario es el conductor
        if ($trip->driver_id !== auth()->id()) {
            return back()->with('error', 'No autorizado');
        }

        // Verificar disponibilidad
        if ($trip->available_seats <= 0) {
            return back()->with('error', 'No hay asientos disponibles');
        }

        // Aceptar solicitud
        $tripRequest->update(['status' => 'accepted']);
        $trip->decrement('available_seats');

        return back()->with('success', 'Solicitud aceptada');
    }

    public function rejectRequest($tripRequestId): RedirectResponse
    {
        $tripRequest = TripRequest::findOrFail($tripRequestId);
        $trip = $tripRequest->trip;

        // Verificar que el usuario es el conductor
        if ($trip->driver_id !== auth()->id()) {
            return back()->with('error', 'No autorizado');
        }

        $tripRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Solicitud rechazada');
    }

    /**
     * Crear/Editar vehículo
     */
    public function storeVehicle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate' => 'required|string|max:20|unique:vehicles,plate',
            'color' => 'required|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
        ]);

        $user = auth()->user();

        // Si se envía vehicle_id, es una edición
        if ($request->input('vehicle_id')) {
            $vehicle = Vehicle::findOrFail($request->input('vehicle_id'));
            
            // Verificar que le pertenece al conductor
            if ($vehicle->driver_id !== $user->id) {
                return back()->with('error', 'No autorizado');
            }

            $vehicle->update($validated);
            return back()->with('success', 'Vehículo actualizado exitosamente');
        }

        // Si no, es un nuevo vehículo
        // Desactivar vehículos anteriores
        $user->vehicles()->update(['is_active' => false]);

        // Crear nuevo vehículo
        $validated['driver_id'] = $user->id;
        $validated['is_active'] = true;
        Vehicle::create($validated);

        return back()->with('success', 'Vehículo registrado exitosamente');
    }

    /**
     * Cambiar vehículo activo
     */
    public function switchVehicle($vehicleId): RedirectResponse
    {
        $user = auth()->user();
        $vehicle = Vehicle::findOrFail($vehicleId);

        // Verificar que le pertenece al conductor
        if ($vehicle->driver_id !== $user->id) {
            return back()->with('error', 'No autorizado');
        }

        // Desactivar todos
        $user->vehicles()->update(['is_active' => false]);

        // Activar el seleccionado
        $vehicle->update(['is_active' => true]);

        return back()->with('success', 'Vehículo activado exitosamente');
    }

    /**
     * RF8: Calificar viaje
     */
    public function rateTrip(Trip $trip): View
    {
        $user = auth()->user();
        
        // Verificar que el usuario participó en el viaje
        $participated = TripRequest::where('trip_id', $trip->id)
            ->where('passenger_id', $user->id)
            ->where('status', 'accepted')
            ->exists();

        if (!$participated && $trip->driver_id !== $user->id) {
            abort(403);
        }

        // Obtener los usuarios a calificar
        if ($trip->driver_id === $user->id) {
            // El conductor califica a los pasajeros
            $usersToRate = $trip->confirmedPassengers()->get()->pluck('passenger');
        } else {
            // El pasajero califica al conductor
            $usersToRate = collect([$trip->driver]);
        }

        return view('trips.rate', compact('trip', 'usersToRate'));
    }

    public function submitRating(Trip $trip, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Verificar que no haya calificación duplicada
        $existingRating = Rating::where('trip_id', $trip->id)
            ->where('from_user_id', auth()->id())
            ->where('to_user_id', $validated['to_user_id'])
            ->first();

        if ($existingRating) {
            return back()->with('error', 'Ya has calificado a este usuario en este viaje');
        }

        $validated['trip_id'] = $trip->id;
        $validated['from_user_id'] = auth()->id();

        Rating::create($validated);

        return back()->with('success', 'Calificación registrada');
    }

    /**
     * Cambiar rol de usuario
     */
    public function switchRole(): RedirectResponse
    {
        $user = auth()->user();
        $user->role = $user->role === 'driver' ? 'student' : 'driver';
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Rol cambiado exitosamente');
    } 
    public function completeTrip(\App\Models\Trip $trip)
    {
    // Verificamos que el usuario logueado sea realmente el conductor de ese viaje
    if ($trip->driver_id === auth()->id()) {
        $trip->update([
            'status' => 'completed'
        ]);
    }

    return redirect()->back()->with('success', 'Viaje finalizado exitosamente.');
    }
}