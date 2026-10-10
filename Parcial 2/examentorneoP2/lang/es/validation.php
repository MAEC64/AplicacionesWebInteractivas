<?php

// Mensajes de validación en español (se usan porque APP_LOCALE=es en el .env)
return [
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'email' => 'Escribe un correo electrónico válido.',
    'unique' => 'Ese :attribute ya está registrado.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'after' => 'El campo :attribute debe ser posterior a :date.',
    'in' => 'El valor de :attribute no es válido.',
    'between' => ['numeric' => 'El campo :attribute debe estar entre :min y :max.'],
    'max' => ['string' => 'El campo :attribute no puede pasar de :max caracteres.'],
    'min' => ['string' => 'El campo :attribute debe tener al menos :min caracteres.'],

    // Mensajes especiales para un campo y regla concretos
    'custom' => [
        'fecha' => ['after' => 'La fecha debe ser futura.'],
        'cupo' => ['min' => 'Ya hay :min jugadores inscritos; el cupo no puede ser menor.'],
    ],

    // Nombre en español de cada campo del formulario
    'attributes' => [
        'name' => 'nombre',
        'email' => 'correo',
        'password' => 'contraseña',
        'nombre' => 'nombre',
        'juego' => 'juego',
        'fecha' => 'fecha',
        'cupo' => 'cupo',
        'descripcion' => 'descripción',
        'estado' => 'estado',
    ],
];
