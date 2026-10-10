<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Deja pasar solo a usuarios con el rol indicado.
 * Uso en rutas: ->middleware('rol:admin') o ->middleware('rol:jugador')
 */
class VerificarRol
{
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        // Invitado (sin sesión): lo mandamos a iniciar sesión
        if (! auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para entrar a esa página.');
        }

        // Tiene sesión pero no el rol correcto
        if (auth()->user()->rol !== $rol) {
            return redirect()->route('torneos.index')
                ->with('error', 'No tienes permiso para entrar a esa página.');
        }

        return $next($request);
    }
}
