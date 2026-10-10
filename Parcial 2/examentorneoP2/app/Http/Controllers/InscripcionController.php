<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Torneo;

class InscripcionController extends Controller
{
    // ===================== JUGADOR =====================

    // Botón "Inscribirme"
    public function store(Torneo $torneo)
    {
        $yaInscrito = $torneo->inscripciones()->where('user_id', auth()->id())->exists();

        if ($yaInscrito) {
            return back()->with('error', 'Ya estás inscrito en este torneo.');
        }
        if ($torneo->estado === 'cerrado' || $torneo->yaPaso()) {
            return back()->with('error', 'Este torneo está cerrado y ya no acepta inscripciones.');
        }
        if ($torneo->lleno()) {
            return back()->with('error', 'Este torneo ya está lleno. No quedan lugares.');
        }

        $torneo->inscripciones()->create(['user_id' => auth()->id()]);

        return back()->with('exito', "Te inscribiste en \"{$torneo->nombre}\". ¡Mucha suerte!");
    }

    // Pantalla "Mis torneos"
    public function misTorneos()
    {
        $inscripciones = auth()->user()->inscripciones()
            ->with('torneo')
            ->get()
            ->sortBy('torneo.fecha');

        return view('inscripciones.mis-torneos', compact('inscripciones'));
    }

    // El jugador cancela su inscripción (solo antes de la fecha del torneo)
    public function cancelar(Inscripcion $inscripcion)
    {
        if ($inscripcion->user_id != auth()->id()) {
            return back()->with('error', 'Solo puedes cancelar tus propias inscripciones.');
        }
        if ($inscripcion->torneo->yaPaso()) {
            return back()->with('error', 'El torneo ya se jugó; ya no puedes cancelar tu inscripción.');
        }

        $nombreTorneo = $inscripcion->torneo->nombre;
        $inscripcion->delete(); // al borrarla, el lugar queda libre

        return back()->with('exito', "Cancelaste tu inscripción en \"{$nombreTorneo}\". Tu lugar quedó libre.");
    }

    // ===================== ADMINISTRADOR =====================

    // El admin da de baja a cualquier jugador de un torneo
    public function darDeBaja(Inscripcion $inscripcion)
    {
        $jugador = $inscripcion->user->name;
        $inscripcion->delete();

        return back()->with('exito', "{$jugador} fue dado de baja del torneo.");
    }
}
