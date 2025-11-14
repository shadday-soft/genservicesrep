<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\TecnicoController;
use App\Http\Middleware\VerifyadminRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard')->middleware(VerifyadminRole::class);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('clients', ClientController::class)->middleware(VerifyadminRole::class);
    Route::resource('users', UserController::class)->middleware(VerifyadminRole::class);
    Route::put('users/{user}/role', [\App\Http\Controllers\Auth\UserController::class, 'updateRole'])->name('users.updateRole')->middleware(VerifyadminRole::class);
    Route::resource('sucursals', SucursalController::class)->middleware(VerifyadminRole::class);
    Route::resource('equipos', EquipoController::class)->middleware(VerifyadminRole::class);
    Route::post('equipos/{equipo}/renew', [EquipoController::class, 'renew'])->name('equipos.renew')->middleware(VerifyadminRole::class);
    Route::resource('solicituds', SolicitudController::class);
    Route::post('solicituds/{solicitud}/cancelar', [SolicitudController::class, 'cancelar'])->name('solicituds.cancelar')->middleware(VerifyadminRole::class);
    Route::get('solicituds-cronograma', [SolicitudController::class, 'cronograma'])->name('solicituds.cronograma');
    Route::resource('actividads', ActividadController::class)->middleware(VerifyadminRole::class);
    Route::resource('tecnicos', TecnicoController::class)->middleware(VerifyadminRole::class);

    Route::post('StoreInforme', [InformeController::class, 'store'])->name('StoreInforme');
    Route::get('informe/{solicitud}', [InformeController::class, 'create'])->name('informe');
    Route::get('informe/{solicitud}/pdf', [InformeController::class, 'generatePDF'])->name('informe.pdf');

    // Rutas para geocodificación
    Route::get('geocoding/search', [GeocodingController::class, 'search'])->name('geocoding.search');
    Route::get('geocoding/reverse', [GeocodingController::class, 'reverse'])->name('geocoding.reverse');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
