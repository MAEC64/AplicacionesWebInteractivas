<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Toda cuenta nueva es de jugador
        $usuario = User::create($datos + ['rol' => 'jugador']);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('torneos.index')
            ->with('exito', "¡Bienvenido, {$usuario->name}! Tu cuenta de jugador está lista.");
    }

    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credenciales)) {
            return back()
                ->withErrors(['email' => 'El correo o la contraseña no son correctos.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // El admin entra directo a su panel; el jugador, al listado de torneos
        $destino = Auth::user()->esAdmin() ? 'admin.torneos' : 'torneos.index';

        return redirect()->route($destino)->with('exito', 'Hola, '.Auth::user()->name.'. Iniciaste sesión.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('torneos.index')->with('exito', 'Cerraste sesión.');
    }
}
