@extends('layouts.app')
@section('titulo', 'Iniciar sesión')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-body p-4">
                <h1 class="h3 fw-bold mb-4">Iniciar sesión</h1>

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" autofocus
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn btn-primary w-100">Iniciar sesión</button>
                </form>

                <p class="text-center mt-3 mb-0">¿No tienes cuenta? <a href="{{ route('registro') }}">Crea una</a></p>
            </div>

            {{-- Ayuda para la demo: solo aparece en modo local --}}
            @env('local')
                <div class="alert alert-light border small mt-3">
                    <strong>Cuentas demo</strong> (contraseña <code>password</code>):<br>
                    Admin: <code>admin@torneos.test</code><br>
                    Jugador: <code>jugador@torneos.test</code>
                </div>
            @endenv
        </div>
    </div>
@endsection
