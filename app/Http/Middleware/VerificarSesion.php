<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class VerificarSesion
{
    // Agregamos ...$roles para recibir los IDs permitidos desde el archivo de rutas
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verificamos si existe la sesión (RF-02)
        if (!Session::has('usuario_id')) {
            return redirect('/login')->withErrors(['error' => 'Acceso denegado: Debes iniciar sesión primero.']);
        }

        // 2. Si la ruta exige roles específicos, verificamos la autorización
        if (!empty($roles)) {
            $rolUsuario = Session::get('id_rol'); // Obtenemos el rol del usuario activo

            // Si el rol de este usuario NO está en la lista de permitidos
            if (!in_array($rolUsuario, $roles)) {
                // Lo rebotamos a su área de trabajo con una alerta
                return $this->redirigirSegunRol($rolUsuario);
            }
        }

        return $next($request);
    }

    // Función de seguridad para devolver al intruso a su área correspondiente
    private function redirigirSegunRol($rol) {
        return match ((int) $rol) {
            1 => redirect('/admin/dashboard')->withErrors(['error' => 'Acceso denegado: Esa área no te corresponde.']),
            2 => redirect('/mesero/mesas')->withErrors(['error' => 'Acceso denegado: Tú eres de piso, no tienes acceso a esa área.']),
            3 => redirect('/gerente/dashboard')->withErrors(['error' => 'Acceso denegado: Esa área no te corresponde.']),
            4 => redirect('/cocina/kds')->withErrors(['error' => 'Acceso denegado: Tú eres de cocina, no tienes acceso a piso ni caja.']),
            5 => redirect('/caja/ordenes')->withErrors(['error' => 'Acceso denegado: Tu rol solo permite operaciones de caja.']),
            default => redirect('/login')->withErrors(['error' => 'Rol desconocido.']),
        };
    }
}