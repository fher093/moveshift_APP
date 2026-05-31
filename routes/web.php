<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/verify-email', [App\Http\Controllers\Auth\RegisteredUserController::class, 'showVerifyEmail'])->name('auth.verify-email');
    Route::post('/verify-email', [App\Http\Controllers\Auth\RegisteredUserController::class, 'verifyEmail'])->name('auth.verify-email.store');
    Route::post('/resend-code', [App\Http\Controllers\Auth\RegisteredUserController::class, 'resendCode'])->name('auth.resend-code');
    Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
    Route::get('/auth/microsoft', [App\Http\Controllers\Auth\MicrosoftController::class, 'redirect'])->name('auth.microsoft');
    Route::get('/auth/callback/microsoft', [App\Http\Controllers\Auth\MicrosoftController::class, 'callback'])->name('auth.microsoft.callback');
});

Route::middleware('auth')->group(function () {
    // Dashboard Estudiantes y Conductores
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/switch-role', [DashboardController::class, 'switchRole'])->name('dashboard.switch-role');

    // Trips Management
    Route::get('/trips/create', [DashboardController::class, 'createTrip'])->name('trips.create');
    Route::post('/trips', [DashboardController::class, 'storeTrip'])->name('trips.store');
    Route::post('/trips/{trip}/request', [DashboardController::class, 'requestTrip'])->name('trips.request');
    Route::post('/trips/{tripRequestId}/accept', [DashboardController::class, 'acceptRequest'])->name('trips.accept-request');
    Route::post('/trips/{tripRequestId}/reject', [DashboardController::class, 'rejectRequest'])->name('trips.reject-request');
    Route::post('/trips/{trip}/complete', [DashboardController::class, 'completeTrip'])->name('trips.complete');
    
    // Vehicle Management
    Route::post('/vehicles', [DashboardController::class, 'storeVehicle'])->name('vehicles.store');
    Route::post('/vehicles/{vehicleId}/switch', [DashboardController::class, 'switchVehicle'])->name('vehicles.switch');

    // Ratings & Reviews
    Route::get('/trips/{trip}/rate', [DashboardController::class, 'rateTrip'])->name('trips.rate');
    Route::post('/trips/{trip}/rating', [DashboardController::class, 'submitRating'])->name('trips.submit-rating');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password'); 

    // API Routes para Mapas
    Route::get('/api/trips/active', [\App\Http\Controllers\MapController::class, 'getActiveTrips']);
    Route::get('/api/geocode', [\App\Http\Controllers\MapController::class, 'geocode']);
    Route::get('/api/directions', [\App\Http\Controllers\MapController::class, 'getDirections']);
    
    // API para Polling (Tiempo Real)
    Route::get('/api/trips/completed-for-rating', [\App\Http\Controllers\DashboardController::class, 'getCompletedTripsForRating']);
    Route::get('/api/student/notifications', [\App\Http\Controllers\DashboardController::class, 'getStudentNotifications']); 
    Route::get('/api/driver/notifications', [\App\Http\Controllers\DashboardController::class, 'getDriverNotifications']);

    // ==========================================
    // RUTAS EXCLUSIVAS DEL ADMINISTRADOR
    // ==========================================
    Route::get('/admin/dashboard', function () {
        // Validamos que nadie extraño se cuele
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }
        
        // 1. Traer reportes/críticas hacia conductores
        $driverReports = \App\Models\Rating::where('rating', 1)
            ->whereHas('toUser', function($q) { $q->where('role', 'driver'); })
            ->with(['fromUser', 'toUser'])
            ->get();

        // 2. Traer reportes hacia pasajeros
        $studentReports = \App\Models\Rating::whereNotNull('review')
            ->whereHas('toUser', function($q) { $q->where('role', 'student'); })
            ->with(['fromUser', 'toUser'])
            ->get();

        // 3. Traer la lista de conductores con sus promedios
        $driversPerformance = \App\Models\User::where('role', 'driver')
            ->withCount(['trips as completed_trips_count' => function($query) {
                $query->where('status', 'completed'); 
            }])
            ->withAvg('ratingsReceived as rating_average', 'rating')
            ->get();

        return view('dashboard.admin', compact('driverReports', 'studentReports', 'driversPerformance')); 
    })->name('admin.dashboard'); 

    Route::post('/admin/users/{user}/sancion', function (\App\Models\User $user, \Illuminate\Http\Request $request) {
        if (auth()->user()->role !== 'admin') abort(403);

        $action = $request->action;

        if ($action === 'advertir') {
            $user->update(['account_status' => 'warned']);
        } elseif ($action === 'suspender') {
            $user->update([
                'account_status' => 'suspended',
                'suspended_until' => now()->addHours(2)
            ]);
        } elseif ($action === 'levantar') {
            $user->update([
                'account_status' => 'active',
                'suspended_until' => null
            ]);
        }
        return response()->json(['success' => true]);
    })->name('admin.sancion');

});

require __DIR__.'/auth.php';