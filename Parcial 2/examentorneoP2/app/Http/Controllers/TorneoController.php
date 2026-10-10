<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    // ===================== PÚBLICO =====================

    // Listado: solo torneos disponibles (abiertos, con fecha futura y con lugar), del más próximo al más lejano
    public function index(Request $request)
    {
        $buscar = trim((string) $request->query('buscar'));

        $torneos = Torneo::withCount('inscripciones')
            ->where('estado', 'abierto')
            ->where('fecha', '>', now())
            ->when($buscar, function ($consulta) use ($buscar) {
                $consulta->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('juego', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('fecha')
            ->get()
            ->reject(fn ($torneo) => $torneo->lleno()); // quitamos los que ya no tienen lugar

        // IDs de los torneos donde está inscrito el jugador (para marcar "Inscrito")
        $misTorneos = auth()->check() ? auth()->user()->inscripciones()->pluck('torneo_id')->all() : [];

        return view('torneos.index', compact('torneos', 'buscar', 'misTorneos'));
    }

    // Detalle: datos + participantes. Se puede ver aunque esté cerrado (acceso directo)
    public function show(Torneo $torneo)
    {
        $torneo->load('inscripciones.user');

        $miInscripcion = auth()->check()
            ? $torneo->inscripciones->firstWhere('user_id', auth()->id())
            : null;

        return view('torneos.show', compact('torneo', 'miInscripcion'));
    }

    // ===================== ADMINISTRADOR =====================

    // Tabla con TODOS los torneos (también cerrados, llenos y pasados)
    public function admin()
    {
        $torneos = Torneo::withCount('inscripciones')->orderBy('fecha')->get();

        return view('admin.torneos', compact('torneos'));
    }

    public function create()
    {
        // Valores por defecto del formulario: cupo 16 y estado abierto
        $torneo = new Torneo(['cupo' => 16, 'estado' => 'abierto']);

        return view('torneos.form', compact('torneo'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas());
        $torneo = Torneo::create($datos);

        return redirect()->route('admin.torneos')->with('exito', "Torneo \"{$torneo->nombre}\" creado.");
    }

    public function edit(Torneo $torneo)
    {
        return view('torneos.form', compact('torneo'));
    }

    public function update(Request $request, Torneo $torneo)
    {
        // El cupo no puede quedar por debajo de los jugadores ya inscritos
        $inscritos = $torneo->inscripciones()->count();

        $datos = $request->validate($this->reglas($inscritos));
        $torneo->update($datos);

        return redirect()->route('admin.torneos')->with('exito', "Torneo \"{$torneo->nombre}\" actualizado.");
    }

    public function destroy(Torneo $torneo)
    {
        $torneo->delete(); // sus inscripciones se borran en cascada (ver migración)

        return redirect()->route('admin.torneos')
            ->with('exito', "Torneo \"{$torneo->nombre}\" eliminado junto con sus inscripciones.");
    }

    // Reglas de validación compartidas por crear y editar
    private function reglas(int $inscritos = 0): array
    {
        return [
            'nombre' => 'required|string|max:100',
            'juego' => 'required|string|max:100',
            'fecha' => 'required|date|after:now',
            'cupo' => "required|integer|between:2,100|min:{$inscritos}",
            'descripcion' => 'nullable|string|max:1000',
            'estado' => 'required|in:abierto,cerrado',
        ];
    }
}
