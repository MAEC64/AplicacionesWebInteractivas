<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Torneo extends Model
{
    protected $fillable = ['nombre', 'juego', 'fecha', 'cupo', 'descripcion', 'estado'];

    protected function casts(): array
    {
        return ['fecha' => 'datetime']; // así $torneo->fecha es un objeto de fecha (Carbon)
    }

    // Un torneo tiene muchas inscripciones
    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class);
    }

    // ---------- Ayudantes para las reglas del examen ----------

    // Número de inscritos. Si la consulta usó withCount('inscripciones') ya viene calculado.
    public function inscritos(): int
    {
        return $this->inscripciones_count ?? $this->inscripciones()->count();
    }

    public function plazasLibres(): int
    {
        return max(0, $this->cupo - $this->inscritos());
    }

    public function lleno(): bool
    {
        return $this->inscritos() >= $this->cupo;
    }

    public function yaPaso(): bool
    {
        return $this->fecha->isPast();
    }

    // Regla de cerrado: lo marcó el admin, ya pasó la fecha o se llenó el cupo
    public function cerrado(): bool
    {
        return $this->estado === 'cerrado' || $this->yaPaso() || $this->lleno();
    }

    // Texto de la etiqueta de estado que se muestra en las vistas
    public function estadoActual(): string
    {
        if ($this->estado === 'cerrado') {
            return 'Cerrado';
        }
        if ($this->yaPaso()) {
            return 'Finalizado';
        }
        if ($this->lleno()) {
            return 'Lleno';
        }

        return 'Abierto';
    }

    // Ejemplo: "sáb 18 oct 2026, 18:00"
    public function fechaTexto(): string
    {
        return $this->fecha->translatedFormat('D j M Y, H:i');
    }
}
