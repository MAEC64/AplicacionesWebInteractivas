<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            // cascadeOnDelete: si se borra el torneo (o el usuario), se borran sus inscripciones
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('torneo_id')->constrained('torneos')->cascadeOnDelete();
            $table->timestamps();

            // Un jugador no puede inscribirse dos veces al mismo torneo
            $table->unique(['user_id', 'torneo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
