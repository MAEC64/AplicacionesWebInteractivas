@extends('layouts.app')
@section('titulo', 'Administrar torneos')

@section('contenido')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Administrar torneos</h1>
            <p class="text-body-secondary mb-0">Todos los torneos, incluidos los cerrados, llenos y finalizados.</p>
        </div>
        <a href="{{ route('torneos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo torneo</a>
    </div>

    @if ($torneos->isEmpty())
        <div class="text-center bg-white border rounded-3 py-5">
            <p class="mb-3">Aún no hay torneos. Crea el primero.</p>
            <a href="{{ route('torneos.create') }}" class="btn btn-primary">Nuevo torneo</a>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Torneo</th>
                            <th>Fecha</th>
                            <th>Inscritos</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($torneos as $torneo)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $torneo->nombre }}</div>
                                    <small class="text-body-secondary">{{ $torneo->juego }}</small>
                                </td>
                                <td class="text-nowrap">{{ $torneo->fechaTexto() }}</td>
                                <td>{{ $torneo->inscritos() }} / {{ $torneo->cupo }}</td>
                                <td>@include('torneos._estado')</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('torneos.show', $torneo) }}" class="btn btn-sm btn-outline-secondary" title="Ver inscritos">
                                        <i class="bi bi-people"></i> Inscritos
                                    </a>
                                    <a href="{{ route('torneos.edit', $torneo) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i><span class="visually-hidden">Editar</span>
                                    </a>
                                    <form method="POST" action="{{ route('torneos.destroy', $torneo) }}" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar este torneo? También se borrarán sus inscripciones.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i><span class="visually-hidden">Eliminar</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
