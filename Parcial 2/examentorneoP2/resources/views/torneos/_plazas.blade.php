{{-- Barra y contador de plazas. Uso: @include('torneos._plazas', ['torneo' => $torneo]) --}}
@php
    $inscritos = $torneo->inscritos();
    $libres = $torneo->plazasLibres();
    $porcentaje = round($inscritos * 100 / max(1, $torneo->cupo));
@endphp
<div class="progress mb-1" role="progressbar" aria-label="Plazas ocupadas"
     aria-valuenow="{{ $inscritos }}" aria-valuemin="0" aria-valuemax="{{ $torneo->cupo }}">
    <div class="progress-bar" style="width: {{ $porcentaje }}%"></div>
</div>
<small class="text-body-secondary">
    {{ $inscritos }} de {{ $torneo->cupo }} inscritos,
    {{ $libres === 0 ? 'sin lugares' : 'quedan '.$libres.($libres === 1 ? ' lugar' : ' lugares') }}
</small>
