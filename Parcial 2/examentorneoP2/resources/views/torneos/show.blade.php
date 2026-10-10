@extends('layouts.app')
@section('titulo', $torneo->nombre)

@section('contenido')
    <a href="{{ auth()->user()?->esAdmin() ? route('admin.torneos') : route('torneos.index') }}" class="d-inline-block mb-3">
        <i class="bi bi-arrow-left"></i> Volver a torneos
    </a>

    <div class="row g-4">
        {{-- Columna izquierda: datos del torneo y acciones --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <span class="text-body-secondary"><i class="bi bi-joystick"></i> {{ $torneo->juego }}</span>
                        @include('torneos._estado')
                    </div>
                    <h1 class="h2 fw-bold mt-2">{{ $torneo->nombre }}</h1>
                    <p class="mb-3"><i class="bi bi-calendar-event"></i> {{ $torneo->fechaTexto() }}</p>

                    @if ($torneo->descripcion)
                        <p class="text-body-secondary" style="white-space: pre-line">{{ $torneo->descripcion }}</p>
                    @endif

                    <div class="mb-4">@include('torneos._plazas')</div>

                    {{-- Acciones según quién está viendo la página --}}
                    @guest
                        <div class="alert alert-light border mb-0">
                            <a href="{{ route('login') }}">Inicia sesión</a> o <a href="{{ route('registro') }}">crea una cuenta</a> de jugador para inscribirte.
                        </div>
                    @else
                        @if (auth()->user()->esAdmin())
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('torneos.edit', $torneo) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar torneo</a>
                                <form method="POST" action="{{ route('torneos.destroy', $torneo) }}"
                                      onsubmit="return confirm('¿Eliminar este torneo? También se borrarán sus inscripciones.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Eliminar torneo</button>
                                </form>
                            </div>
                        @elseif ($miInscripcion)
                            <div class="alert alert-primary d-flex flex-wrap justify-content-between align-items-center gap-2 mb-0">
                                <span><i class="bi bi-check2-circle"></i> Ya estás inscrito en este torneo.</span>
                                @unless ($torneo->yaPaso())
                                    <form method="POST" action="{{ route('inscripciones.cancelar', $miInscripcion) }}"
                                          onsubmit="return confirm('¿Cancelar tu inscripción? Tu lugar quedará libre.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Cancelar inscripción</button>
                                    </form>
                                @endunless
                            </div>
                        @elseif ($torneo->cerrado())
                            <div class="alert alert-secondary mb-0">
                                <i class="bi bi-lock-fill"></i>
                                @if ($torneo->estado === 'cerrado')
                                    El administrador cerró las inscripciones de este torneo.
                                @elseif ($torneo->yaPaso())
                                    Este torneo ya se jugó.
                                @else
                                    Este torneo está lleno. No quedan lugares.
                                @endif
                            </div>
                        @else
                            <form method="POST" action="{{ route('inscripciones.store', $torneo) }}">
                                @csrf
                                <button class="btn btn-primary btn-lg"><i class="bi bi-person-plus"></i> Inscribirme</button>
                            </form>
                        @endif
                    @endguest
                </div>
            </div>
        </div>

        {{-- Columna derecha: participantes --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-people"></i> Participantes ({{ $torneo->inscripciones->count() }}/{{ $torneo->cupo }})
                </div>
                @if ($torneo->inscripciones->isEmpty())
                    <div class="card-body text-body-secondary">Todavía no hay jugadores inscritos.</div>
                @else
                    <ol class="list-group list-group-flush list-group-numbered">
                        @foreach ($torneo->inscripciones as $inscripcion)
                            <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
                                <span class="me-auto ms-2">{{ $inscripcion->user->name }}</span>

                                {{-- Solo el admin ve el botón para dar de baja --}}
                                @if (auth()->user()?->esAdmin())
                                    <form method="POST" action="{{ route('inscripciones.baja', $inscripcion) }}"
                                          onsubmit="return confirm('¿Dar de baja a este jugador del torneo?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Dar de baja"><i class="bi bi-person-dash"></i> Dar de baja</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
    </div>
@endsection
