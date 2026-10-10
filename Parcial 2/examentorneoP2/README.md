# TorneosApp — Sistema de torneos de videojuegos con roles

Aplicación web en **Laravel 12** donde un **administrador** crea torneos de videojuegos y los **jugadores** se inscriben. Los visitantes sin cuenta solo pueden ver los torneos.

Herramientas: Laravel 12, SQLite, Blade y Bootstrap 5 (por CDN). La autenticación es manual, sin Breeze ni Jetstream. No se necesita Node ni npm.

---

## 1. Requisitos

- PHP 8.2 o superior, con la extensión `pdo_sqlite` activada (en XAMPP, quita el `;` de `extension=pdo_sqlite` y `extension=sqlite3` en `php.ini`)
- Composer
- Conexión a internet al abrir la app (Bootstrap y los iconos se cargan desde CDN)

## 2. Instalación

Opción rápida (un solo comando que instala, crea el `.env`, la base de datos y los datos demo):

```bash
composer setup
php artisan serve
```

Opción paso a paso:

```bash
composer install
cp .env.example .env            # En Windows: copy .env.example .env
php artisan key:generate
php artisan migrate --seed      # Si pregunta si crear database.sqlite, responde "yes"
php artisan serve
```

Abre **http://127.0.0.1:8000** en el navegador.

Para reiniciar la base de datos con los datos demo: `php artisan migrate:fresh --seed`

> La zona horaria es `America/Mexico_City` (en `config/app.php`). El idioma de los mensajes es español (`APP_LOCALE=es` en `.env`).

## 3. Cuentas demo

La cuenta de **administrador se crea con el seeder** (`database/seeders/DatabaseSeeder.php`). Toda cuenta creada desde "Crear cuenta" es de **jugador**.

| Rol | Correo | Contraseña |
| --- | --- | --- |
| Administrador | admin@torneos.test | password |
| Jugador | jugador@torneos.test | password |
| Jugador | ana@torneos.test | password |
| Jugador | luis@torneos.test | password |

## 4. Torneos de ejemplo (uno para cada caso)

| Torneo | Caso que permite probar |
| --- | --- |
| Copa Smash de Otoño | Abierto, con 2 inscritos (entre ellos `jugador@`) |
| Gran Premio Mario Kart | Abierto, con 1 inscrito |
| Liga FC Universitaria | Abierto, sin inscritos, cupo 16 |
| Duelo Street Fighter | **Lleno** (cupo 2, 2 inscritos): no sale en el listado |
| Rocket League 2 vs 2 | **Cerrado por el admin**: no sale en el listado |
| Reto Clash Royale | **Fecha pasada**: no sale en el listado; `jugador@` está inscrito y ya no puede cancelar |

## 5. Cómo probar cada punto

### 5.1 Autenticación y roles
1. Entra a **Crear cuenta** y envía el formulario vacío: aparecen los errores en español junto a cada campo.
2. Crea una cuenta válida: entras como **Jugador** (se ve la etiqueta junto a tu nombre en el menú).
3. **Cerrar sesión** e inicia sesión con `admin@torneos.test`: entras al panel **Administrar**.
4. Prueba un login con contraseña incorrecta: "El correo o la contraseña no son correctos."

### 5.2 CRUD de torneos (admin)
1. Como admin, ve a **Administrar → Nuevo torneo**. El cupo ya trae **16** y el estado **Abierto**.
2. Guarda con el nombre vacío, una fecha pasada y cupo 1 o 101: cada campo muestra su error.
3. Crea un torneo válido: aparece en la tabla y en el listado público.
4. **Editar** "Copa Smash de Otoño" y pon cupo **1**: error de rango (2–100). Para probar el cupo mínimo, inscribe 3 jugadores en un torneo y cambia su cupo a 2: "Ya hay 3 jugadores inscritos; el cupo no puede ser menor."
5. **Eliminar** un torneo con inscritos: pide confirmación y borra también sus inscripciones (revisa "Mis torneos" del jugador).

### 5.3 Inscripciones (jugador)
1. Como `jugador@torneos.test`, abre "Gran Premio Mario Kart" y pulsa **Inscribirme**: aparece el mensaje de éxito y aumenta el contador de plazas.
2. **Duplicado:** abre el mismo torneo en dos pestañas, inscríbete en una y pulsa **Inscribirme** en la otra: "Ya estás inscrito en este torneo."
3. **Lleno / cerrado:** abre directamente `/torneos/4` (Street Fighter) o `/torneos/5` (Rocket League). No aparece el botón; aparece el aviso del motivo.
4. **Mis torneos:** muestra tus inscripciones. **Cancelar inscripción** pide confirmación y libera el lugar. En "Reto Clash Royale" (fecha pasada) ya no hay botón para cancelar.

### 5.4 Consulta pública, permisos y mensajes
1. Sin sesión, `/torneos` muestra solo torneos abiertos, futuros y con lugar, ordenados del más próximo al más lejano.
2. Usa el **buscador** (por nombre o juego, p. ej. "kart"). Si no hay resultados aparece un mensaje.
3. El **detalle** muestra los datos y la lista de participantes. Como admin, cada participante tiene un botón **Dar de baja**.
4. **Permisos:** sin sesión entra a `/admin/torneos` → te manda al login con aviso. Como jugador entra a `/admin/torneos` → "No tienes permiso para entrar a esa página." Como admin intenta inscribirte (por URL) → mismo aviso.

## 6. Archivos principales

| Archivo | Qué contiene |
| --- | --- |
| `routes/web.php` | Todas las rutas, agrupadas por público, jugador y admin |
| `app/Http/Middleware/VerificarRol.php` | Control de acceso por rol (registrado como `rol` en `bootstrap/app.php`) |
| `app/Http/Controllers/AuthController.php` | Registro, login y logout |
| `app/Http/Controllers/TorneoController.php` | Listado público, detalle y CRUD del admin |
| `app/Http/Controllers/InscripcionController.php` | Inscribirse, Mis torneos, cancelar y dar de baja |
| `app/Models/Torneo.php` | Reglas: lleno, fecha pasada, cerrado y plazas libres |
| `database/migrations/` | Tablas `users` (con `rol`), `torneos` e `inscripciones` |
| `database/seeders/DatabaseSeeder.php` | Cuentas demo y torneos de ejemplo |
| `lang/es/validation.php` | Mensajes de validación en español |
| `resources/views/` | Vistas Blade (layout, auth, torneos, admin, inscripciones) |
