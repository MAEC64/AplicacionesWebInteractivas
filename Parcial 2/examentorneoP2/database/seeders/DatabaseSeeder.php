<?php

namespace Database\Seeders;

use App\Models\Torneo;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Cuentas demo (contraseña de todas: password) ----------
        User::create(['name' => 'Administrador', 'email' => 'admin@torneos.test', 'password' => 'password', 'rol' => 'admin']);
        $jugador = User::create(['name' => 'Jugador Demo', 'email' => 'jugador@torneos.test', 'password' => 'password', 'rol' => 'jugador']);
        $ana = User::create(['name' => 'Ana', 'email' => 'ana@torneos.test', 'password' => 'password', 'rol' => 'jugador']);
        $luis = User::create(['name' => 'Luis', 'email' => 'luis@torneos.test', 'password' => 'password', 'rol' => 'jugador']);

        // ---------- Torneos de ejemplo (uno por cada caso a probar) ----------

        // Abierto, con 2 inscritos
        $smash = Torneo::create([
            'nombre' => 'Copa Smash de Otoño',
            'juego' => 'Super Smash Bros. Ultimate',
            'fecha' => now()->addDays(3)->setTime(17, 0),
            'cupo' => 16,
            'descripcion' => "Formato 1 vs 1, 3 vidas, 7 minutos.\nTrae tu propio control.",
            'estado' => 'abierto',
        ]);
        $smash->inscripciones()->create(['user_id' => $jugador->id]);
        $smash->inscripciones()->create(['user_id' => $ana->id]);

        // Abierto, con 1 inscrito
        $kart = Torneo::create([
            'nombre' => 'Gran Premio Mario Kart',
            'juego' => 'Mario Kart 8 Deluxe',
            'fecha' => now()->addDays(5)->setTime(18, 0),
            'cupo' => 12,
            'descripcion' => 'Cuatro copas, 150cc. Gana quien sume más puntos.',
            'estado' => 'abierto',
        ]);
        $kart->inscripciones()->create(['user_id' => $luis->id]);

        // Abierto, sin inscritos (cupo por defecto: 16)
        Torneo::create([
            'nombre' => 'Liga FC Universitaria',
            'juego' => 'EA Sports FC 25',
            'fecha' => now()->addDays(10)->setTime(16, 0),
            'cupo' => 16,
            'estado' => 'abierto',
        ]);

        // LLENO: cupo 2 con 2 inscritos (no sale en el listado)
        $sf = Torneo::create([
            'nombre' => 'Duelo Street Fighter',
            'juego' => 'Street Fighter 6',
            'fecha' => now()->addDays(7)->setTime(19, 0),
            'cupo' => 2,
            'descripcion' => 'Final directa a 3 de 5 rounds.',
            'estado' => 'abierto',
        ]);
        $sf->inscripciones()->create(['user_id' => $ana->id]);
        $sf->inscripciones()->create(['user_id' => $luis->id]);

        // CERRADO por el admin (no sale en el listado)
        Torneo::create([
            'nombre' => 'Rocket League 2 vs 2',
            'juego' => 'Rocket League',
            'fecha' => now()->addDays(14)->setTime(17, 30),
            'cupo' => 8,
            'estado' => 'cerrado',
        ]);

        // FECHA PASADA (no sale en el listado; en "Mis torneos" ya no se puede cancelar)
        $clash = Torneo::create([
            'nombre' => 'Reto Clash Royale',
            'juego' => 'Clash Royale',
            'fecha' => now()->subDays(2)->setTime(12, 0),
            'cupo' => 20,
            'estado' => 'abierto',
        ]);
        $clash->inscripciones()->create(['user_id' => $jugador->id]);
    }
}
