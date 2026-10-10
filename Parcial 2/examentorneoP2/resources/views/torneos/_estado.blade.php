{{-- Etiqueta de color con el estado real del torneo. Uso: @include('torneos._estado', ['torneo' => $torneo]) --}}
@php
    $estado = $torneo->estadoActual();
    [$color, $icono] = [
        'Abierto' => ['text-bg-success', 'bi-unlock-fill'],
        'Lleno' => ['text-bg-warning', 'bi-people-fill'],
        'Cerrado' => ['text-bg-secondary', 'bi-lock-fill'],
        'Finalizado' => ['text-bg-dark', 'bi-flag-fill'],
    ][$estado];
@endphp
<span class="badge rounded-pill {{ $color }}"><i class="bi {{ $icono }}"></i> {{ $estado }}</span>
