@extends('layouts.app')
@section('titulo', 'Crear cuenta')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-body p-4">
                <h1 class="h3 fw-bold mb-1">Crear cuenta de jugador</h1>
                <p class="text-body-secondary mb-4">Con tu cuenta podrás inscribirte en torneos.</p>

                <form method="POST" action="{{ route('registro') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre o gamertag</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" autofocus
                               class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña (mínimo 8 caracteres)</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Repite la contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                    <button class="btn btn-primary w-100">Crear cuenta</button>
                </form>

                <p class="text-center mt-3 mb-0">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
            </div>
        </div>
    </div>
@endsection
