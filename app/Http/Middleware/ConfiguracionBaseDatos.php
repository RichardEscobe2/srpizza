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
        if (Session::has('id_rol')) {
            $id_rol = Session::get('id_rol');

            $db_user = match ((int) $id_rol) {
                1 => 'admin_restaurante',
                3 => 'gerente_restaurante',
                default => 'empleado_restaurante'
            };

            $db_pass = match ((int) $id_rol) {
                1 => '123',
                3 => '456',
                default => '789'
            };

            \Illuminate\Support\Facades\Config::set('database.connections.mysql.username', $db_user);
            \Illuminate\Support\Facades\Config::set('database.connections.mysql.password', $db_pass);
            \Illuminate\Support\Facades\DB::purge('mysql');
        }
        
        return $next($request);
    }
}