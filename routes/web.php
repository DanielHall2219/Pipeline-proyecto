<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\ClaveOlvidadaController;
use App\Http\Controllers\NuevaClaveController;
use App\Http\Controllers\OpinionController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', [AuthController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/clave-olvidada', [ClaveOlvidadaController::class, 'formulario'])
        ->name('clave.olvidada');

    Route::post('/clave-olvidada', [ClaveOlvidadaController::class, 'enviar'])
        ->name('clave.enviar');

    Route::get('/nueva-clave/{token}', [NuevaClaveController::class, 'formulario'])
        ->name('clave.reset');

    Route::post('/nueva-clave', [NuevaClaveController::class, 'guardar'])
        ->name('clave.guardar');
});

Route::middleware(['auth'])->group(function () {

    // Usuarios (solo admin)
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Inventario
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/create', [InventarioController::class, 'create'])->name('inventario.create');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    Route::get('/inventario/{id}/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
    Route::put('/inventario/{id}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::delete('/inventario/{id}', [InventarioController::class, 'destroy'])->name('inventario.destroy');

    // Movimientos de stock
    Route::put('/inventario/{id}/reducir', [InventarioController::class, 'reducir'])->name('inventario.reducir');
    Route::put('/inventario/{id}/entrada', [InventarioController::class, 'entrada'])->name('inventario.entrada');
    Route::put('/inventario/{id}/desactivar', [InventarioController::class, 'desactivar'])->name('inventario.desactivar');
    Route::put('/inventario/{id}/activar', [InventarioController::class, 'activar'])->name('inventario.activar');

    // Reportes
    Route::view('/reportes', 'reportes.index')->name('reportes.index');
    Route::get('/reportes/inventario', [ReporteController::class, 'reporteInventario'])->name('reportes.inventario');

    // Reservaciones
    Route::get('/reservaciones', [ReservacionController::class, 'index'])->name('reservaciones.index');
    Route::get('/reservaciones/create', [ReservacionController::class, 'create'])->name('reservaciones.create');
    Route::post('/reservaciones', [ReservacionController::class, 'store'])->name('reservaciones.store');
    Route::get('/reservaciones/{id}/edit', [ReservacionController::class, 'edit'])->whereNumber('id')->name('reservaciones.edit');
    Route::put('/reservaciones/{id}', [ReservacionController::class, 'update'])->whereNumber('id')->name('reservaciones.update');
    Route::delete('/reservaciones/{id}', [ReservacionController::class, 'destroy'])->whereNumber('id')->name('reservaciones.destroy');
    Route::put('/reservaciones/liberar-dia', [ReservacionController::class, 'liberarDia'])->name('reservaciones.liberarDia');
    Route::post('/reservaciones/ocupar-dia', [ReservacionController::class, 'ocuparDia'])->name('reservaciones.ocuparDia');
    Route::put('/mesas/{id}/estado', [MesaController::class, 'cambiarEstado'])->name('mesas.cambiarEstado');
    Route::put('/reservaciones/{id}/liberar-walkin', [ReservacionController::class, 'liberarWalkin'])->whereNumber('id')->name('reservaciones.liberarWalkin');
    Route::put('/reservaciones/{id}/liberar', [ReservacionController::class, 'liberarReserva'])->whereNumber('id')->name('reservaciones.liberarReserva');
    
    
    Route::middleware(['auth'])->group(function () {
    Route::get('/opiniones',[OpinionController::class,'index'])->name('opiniones.index');
    Route::get('/opiniones/crear',[OpinionController::class,'create'])->name('opiniones.crear');
    Route::post('/opiniones', [OpinionController::class,'store'])->name('opiniones.guardar');
});


    });

