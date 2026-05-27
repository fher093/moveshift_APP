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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/switch-role', [DashboardController::class, 'switchRole'])->name('dashboard.switch-role');

    // Trips Management
    Route::get('/trips/create', [DashboardController::class, 'createTrip'])->name('trips.create');
    Route::post('/trips', [DashboardController::class, 'storeTrip'])->name('trips.store');
    Route::post('/trips/{trip}/request', [DashboardController::class, 'requestTrip'])->name('trips.request');
    Route::post('/trips/{tripRequestId}/accept', [DashboardController::class, 'acceptRequest'])->name('trips.accept-request');
    Route::post('/trips/{tripRequestId}/reject', [DashboardController::class, 'rejectRequest'])->name('trips.reject-request');
    
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
});

require __DIR__.'/auth.php';