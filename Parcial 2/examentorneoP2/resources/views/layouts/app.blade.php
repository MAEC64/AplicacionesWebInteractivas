<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Torneos') | TorneosApp</title>

    {{-- Bootstrap e iconos desde CDN: no hace falta instalar nada con npm --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap">

    <style>
        /* Colores de la app (morado "gamer" sobre fondo claro) */
        :root {
            --tinta: #211a4a;
            --acento: #5b3df5;
            --acento-oscuro: #4529d6;
            --fondo: #f6f5fb;
            --borde: #e5e2f3;
            --bs-link-color-rgb: 91, 61, 245;        /* = --acento */
            --bs-link-hover-color-rgb: 69, 41, 214;  /* = --acento-oscuro */
        }
        body { font-family: 'Rubik', system-ui, sans-serif; background: var(--fondo); color: #26223d; }
        .navbar { background: var(--tinta); }
        .card { border-color: var(--borde); }
        .btn-primary {
            --bs-btn-bg: var(--acento); --bs-btn-border-color: var(--acento);
            --bs-btn-hover-bg: var(--acento-oscuro); --bs-btn-hover-border-color: var(--acento-oscuro);
            --bs-btn-active-bg: var(--acento-oscuro); --bs-btn-active-border-color: var(--acento-oscuro);
            --bs-btn-disabled-bg: var(--acento); --bs-btn-disabled-border-color: var(--acento);
        }
        .btn-outline-primary {
            --bs-btn-color: var(--acento); --bs-btn-border-color: var(--acento);
            --bs-btn-hover-bg: var(--acento); --bs-btn-hover-border-color: var(--acento);
            --bs-btn-active-bg: var(--acento-oscuro); --bs-btn-active-border-color: var(--acento-oscuro);
        }
        .progress { --bs-progress-bar-bg: var(--acento); height: .5rem; }
        .form-control:focus, .form-select:focus { border-color: var(--acento); box-shadow: 0 0 0 .2rem rgba(91, 61, 245, .2); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('torneos.index') }}">
                <i class="bi bi-controller"></i> TorneosApp
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                    aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('torneos.index') ? 'active' : '' }}" href="{{ route('torneos.index') }}">Torneos</a>
                    </li>
                    @auth
                        @if (auth()->user()->esAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.torneos') ? 'active' : '' }}" href="{{ route('admin.torneos') }}">Administrar</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mis-torneos') ? 'active' : '' }}" href="{{ route('mis-torneos') }}">Mis torneos</a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <div class="d-flex flex-wrap align-items-center gap-2 py-2 py-md-0">
                    @auth
                        <span class="text-white-50 small">
                            {{ auth()->user()->name }}
                            <span class="badge text-bg-light">{{ auth()->user()->esAdmin() ? 'Admin' : 'Jugador' }}</span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                        </form>
                    @else
                        <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Iniciar sesión</a>
                        <a class="btn btn-light btn-sm" href="{{ route('registro') }}">Crear cuenta</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        {{-- Mensajes de éxito y error que mandan los controladores con ->with(...) --}}
        @if (session('exito'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('exito') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @yield('contenido')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
