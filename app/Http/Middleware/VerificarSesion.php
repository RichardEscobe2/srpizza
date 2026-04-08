<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class VerificarSesion
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si NO hay un 'usuario_id' guardado en la sesión, significa que se saltaron el Login
        if (!Session::has('usuario_id')) {
            // Los rebotamos de regreso a la pantalla de acceso con un mensaje de error
            return redirect('/login')->withErrors(['error' => 'Acceso denegado: Debes iniciar sesión primero.']);
        }

        // Si sí hay sesión, lo dejamos pasar a la pantalla que pidió
        return $next($request);
    }
}