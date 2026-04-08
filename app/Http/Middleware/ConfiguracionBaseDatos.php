<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class ConfiguracionBaseDatos
{
    public function handle(Request $request, Closure $next): Response
    {
        // Solo cambia la conexión si existen AMBOS datos (Usuario y Contraseña)
        if (Session::has('db_user') && Session::has('db_pass')) {
            \Illuminate\Support\Facades\Config::set('database.connections.mysql.username', Session::get('db_user'));
            \Illuminate\Support\Facades\Config::set('database.connections.mysql.password', Session::get('db_pass'));
            \Illuminate\Support\Facades\DB::purge('mysql');
        }
        
        return $next($request);
    }
}