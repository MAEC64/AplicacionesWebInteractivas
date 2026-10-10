@extends('layouts.app')
@section('titulo', 'Torneos disponibles')

@section('contenido')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Torneos disponibles</h1>
            <p class="text-body-secondary mb-0">Abiertos y con lugar, del más próximo al más lejano.</p>
        </div>

        {{-- Buscador por nombre o juego --}}
        <form method="GET" action="{{ route('torneos.index') }}" class="d-flex gap-2" role="search">
            <input type="search" name="buscar" value="{{ $buscar }}" class="form-control" style="min-width: 16rem"
                   placeholder="Buscar por nombre o juego" aria-label="Buscar torneo">
            <button class="btn btn-primary" title="Buscar"><i class="bi bi-search"></i></button>
        </form>
    </div>

    @if ($torneos->isEmpty())
        <div class="text-center bg-white border rounded-3 py-5 px-3">
            <i class="bi bi-controller display-4 text-body-tertiary"></i>
            @if ($buscar)
                <p class="mt-3 mb-2">No hay torneos disponibles que coincidan con "{{ $buscar }}".</p>
                <a href="{{ route('torneos.index') }}">Ver todos los torneos</a>
            @else
                <p class="mt-3 mb-0">No hay torneos disponibles por ahora. Vuelve pronto.</p>
            @endif
        </div>
    @else
        <div class="row g-3">
            @foreach ($torneos as $torneo)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <span class="small text-body-secondary"><i class="bi bi-joystick"></i> {{ $torneo->juego }}</span>
                                @if (in_array($torneo->id, $misTorneos))
                                    <span class="badge rounded-pill text-bg-primary"><i class="bi bi-check2-circle"></i> Inscrito</span>
                                @else
                                    @include('torneos._estado')
                                @endif
                            </div>

                            <h2 class="h5 fw-bold">{{ $torneo->nombre }}</h2>
                            <p class="small mb-3"><i class="bi bi-calendar-event"></i> {{ $torneo->fechaTexto() }}</p>

                            <div class="mt-auto">
                                @include('torneos._plazas')
                                <a href="{{ route('torneos.show', $torneo) }}" class="btn btn-outline-primary w-100 mt-3">Ver detalle</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
