@extends('layouts.app')
@section('titulo', 'Mis torneos')

@section('contenido')
    <h1 class="h2 fw-bold mb-1">Mis torneos</h1>
    <p class="text-body-secondary mb-4">Torneos en los que estás inscrito. Puedes cancelar hasta antes de la fecha del evento.</p>

    @if ($inscripciones->isEmpty())
        <div class="text-center bg-white border rounded-3 py-5 px-3">
            <i class="bi bi-trophy display-4 text-body-tertiary"></i>
            <p class="mt-3 mb-3">Aún no estás inscrito en ningún torneo.</p>
            <a href="{{ route('torneos.index') }}" class="btn btn-primary">Ver torneos disponibles</a>
        </div>
    @else
        <div class="list-group">
            @foreach ($inscripciones as $inscripcion)
                @php($torneo = $inscripcion->torneo)
                <div class="list-group-item p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <a href="{{ route('torneos.show', $torneo) }}" class="fw-bold text-decoration-none">{{ $torneo->nombre }}</a>
                        <div class="small text-body-secondary">
                            <i class="bi bi-joystick"></i> {{ $torneo->juego }}
                            <span class="ms-2"><i class="bi bi-calendar-event"></i> {{ $torneo->fechaTexto() }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if ($torneo->yaPaso())
                            <span class="badge rounded-pill text-bg-dark"><i class="bi bi-flag-fill"></i> Finalizado</span>
                        @else
                            <span class="badge rounded-pill text-bg-primary"><i class="bi bi-check2-circle"></i> Inscrito</span>
                            <form method="POST" action="{{ route('inscripciones.cancelar', $inscripcion) }}"
                                  onsubmit="return confirm('¿Cancelar tu inscripción? Tu lugar quedará libre.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Cancelar inscripción</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
