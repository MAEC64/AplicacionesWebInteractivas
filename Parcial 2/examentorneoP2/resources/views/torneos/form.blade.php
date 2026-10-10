{{-- Un solo formulario para crear y editar. Si $torneo ya existe en la BD, estamos editando --}}
@extends('layouts.app')
@section('titulo', $torneo->exists ? 'Editar torneo' : 'Nuevo torneo')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h2 fw-bold mb-4">{{ $torneo->exists ? 'Editar torneo' : 'Nuevo torneo' }}</h1>

            <form method="POST" novalidate class="card card-body p-4"
                  action="{{ $torneo->exists ? route('torneos.update', $torneo) : route('torneos.store') }}">
                @csrf
                @if ($torneo->exists)
                    @method('PUT')
                @endif

                {{-- Nombre --}}
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del torneo *</label>
                    <input type="text" id="nombre" name="nombre" maxlength="100"
                           value="{{ old('nombre', $torneo->nombre) }}"
                           class="form-control @error('nombre') is-invalid @enderror">
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Juego (con sugerencias) --}}
                <div class="mb-3">
                    <label for="juego" class="form-label">Videojuego *</label>
                    <input type="text" id="juego" name="juego" list="juegos" maxlength="100"
                           value="{{ old('juego', $torneo->juego) }}" placeholder="Ej. Mario Kart 8"
                           class="form-control @error('juego') is-invalid @enderror">
                    <datalist id="juegos">
                        <option value="EA Sports FC 25">
                        <option value="Super Smash Bros. Ultimate">
                        <option value="Mario Kart 8 Deluxe">
                        <option value="Street Fighter 6">
                        <option value="Valorant">
                        <option value="Fortnite">
                        <option value="Rocket League">
                        <option value="Clash Royale">
                    </datalist>
                    @error('juego') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    {{-- Fecha y hora --}}
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="form-label">Fecha y hora *</label>
                        <input type="datetime-local" id="fecha" name="fecha"
                               value="{{ old('fecha', $torneo->fecha?->format('Y-m-d\TH:i')) }}"
                               class="form-control @error('fecha') is-invalid @enderror">
                        @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Cupo --}}
                    <div class="col-md-6 mb-3">
                        <label for="cupo" class="form-label">Cupo (2 a 100) *</label>
                        <input type="number" id="cupo" name="cupo" min="2" max="100"
                               value="{{ old('cupo', $torneo->cupo) }}"
                               class="form-control @error('cupo') is-invalid @enderror">
                        @error('cupo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            @if ($torneo->exists)
                                <div class="form-text">Inscritos ahora: {{ $torneo->inscritos() }}. El cupo no puede ser menor.</div>
                            @endif
                        @enderror
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción (opcional)</label>
                    <textarea id="descripcion" name="descripcion" rows="4" maxlength="1000"
                              class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $torneo->descripcion) }}</textarea>
                    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Estado --}}
                <div class="mb-4">
                    <label for="estado" class="form-label">Estado *</label>
                    <select id="estado" name="estado" class="form-select @error('estado') is-invalid @enderror">
                        <option value="abierto" @selected(old('estado', $torneo->estado) === 'abierto')>Abierto (acepta inscripciones)</option>
                        <option value="cerrado" @selected(old('estado', $torneo->estado) === 'cerrado')>Cerrado</option>
                    </select>
                    @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary"><i class="bi bi-save"></i> Guardar torneo</button>
                    <a href="{{ route('admin.torneos') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
