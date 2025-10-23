<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\SucursalController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('dashboard');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('users', UserController::class);
    Route::resource('sucursals', SucursalController::class);
    Route::resource('equipos', EquipoController::class);
    Route::resource('solicituds', SolicitudController::class);
}); 

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
