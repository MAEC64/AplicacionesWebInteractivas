<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscripcion extends Model
{
    // Laravel pluraliza en inglés ("inscripcions"), por eso indicamos el nombre de la tabla
    protected $table = 'inscripciones';

    protected $fillable = ['user_id', 'torneo_id'];

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
