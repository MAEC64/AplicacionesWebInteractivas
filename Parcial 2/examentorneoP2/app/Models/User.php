<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'rol'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // la contraseña se encripta sola al guardar
        ];
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    // Un jugador tiene muchas inscripciones
    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class);
    }
}
