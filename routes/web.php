<?php

use App\Http\Controllers\Auth\MicrosoftController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== RUTAS DE AUTENTICACIÓN CON MICROSOFT =====
Route::middleware('guest')->group(function () {
    Route::get('/auth/microsoft', [MicrosoftController::class, 'redirect'])
        ->name('auth.microsoft');
    Route::get('/auth/callback/microsoft', [MicrosoftController::class, 'callback'])
        ->name('auth.microsoft.callback');
});

// ===== RUTAS PROTEGIDAS DE PERFIL =====
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'changePassword'])
        ->name('profile.password');
});

// ===== RUTAS DE AUTENTICACIÓN (LOGIN, REGISTRO, etc) =====
require __DIR__.'/auth.php';