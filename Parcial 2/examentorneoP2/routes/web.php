<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\TorneoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('torneos.index'));

// ---------- Autenticación ----------
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registrar']);
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Público (cualquiera, con o sin cuenta) ----------
Route::get('/torneos', [TorneoController::class, 'index'])->name('torneos.index');
Route::get('/torneos/{torneo}', [TorneoController::class, 'show'])->whereNumber('torneo')->name('torneos.show');

// ---------- Solo jugadores ----------
Route::middleware('rol:jugador')->group(function () {
    Route::post('/torneos/{torneo}/inscribirme', [InscripcionController::class, 'store'])->name('inscripciones.store');
    Route::get('/mis-torneos', [InscripcionController::class, 'misTorneos'])->name('mis-torneos');
    Route::delete('/mis-torneos/{inscripcion}', [InscripcionController::class, 'cancelar'])->name('inscripciones.cancelar');
});

// ---------- Solo administrador ----------
Route::middleware('rol:admin')->prefix('admin')->group(function () {
    Route::get('/torneos', [TorneoController::class, 'admin'])->name('admin.torneos');
    Route::get('/torneos/crear', [TorneoController::class, 'create'])->name('torneos.create');
    Route::post('/torneos', [TorneoController::class, 'store'])->name('torneos.store');
    Route::get('/torneos/{torneo}/editar', [TorneoController::class, 'edit'])->name('torneos.edit');
    Route::put('/torneos/{torneo}', [TorneoController::class, 'update'])->name('torneos.update');
    Route::delete('/torneos/{torneo}', [TorneoController::class, 'destroy'])->name('torneos.destroy');
    Route::delete('/inscripciones/{inscripcion}', [InscripcionController::class, 'darDeBaja'])->name('inscripciones.baja');
});
