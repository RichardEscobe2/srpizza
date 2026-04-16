<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Función para mostrar la vista HTML
    public function showLogin() {
        return view('auth.login');
    }

    // Función para procesar la autenticación
    public function login(Request $request) {
        // 1. Validar que los campos no vengan vacíos
        $request->validate([
            'matricula' => 'required|numeric',
            'contrasena' => 'required'
        ]);

        // 2. Buscar al usuario usando el Modelo Usuario (Tabla 'usuario')
        // Buscamos por 'matricula' y que esté 'activo'
        $usuario = Usuario::where('matricula', $request->matricula)
                          ->where('activo', 1)
                          ->first();

        // 3. Validar las credenciales
        $es_valida = false;
        if ($usuario) {
            // Caso A: La contraseña está en texto plano (como el '111' de tu seeder)
            if (!str_starts_with($usuario->contrasena, '$2y$')) {
                if ($usuario->contrasena === $request->contrasena) {
                    $es_valida = true;
                }
            } 
            // Caso B: La contraseña ya es un hash seguro de Laravel
            else {
                if (Hash::check($request->contrasena, $usuario->contrasena)) {
                    $es_valida = true;
                }
            }
        }

       if ($usuario && $es_valida) {
            // 4. Iniciar sesión guardando datos del usuario
            Session::put('usuario_id', $usuario->id_usuario);
            // CORRECCIÓN: El campo en tu BD es 'nombre_completo'
            Session::put('nombre', $usuario->nombre_completo); 
            Session::put('id_rol', $usuario->id_rol);

            // 5. Redirección basada en el id_rol de tus migraciones
            return match ((int)$usuario->id_rol) {
                1 => redirect('/admin/dashboard'), // Administrador
                2 => redirect('/mesero/mesas'),    // Mesero
                3 => redirect('/gerente/dashboard'),// Gerente
                4 => redirect('/cocina/kds'),      // Cocinero
                5 => redirect('/caja/ordenes'),    // Cajero
                default => redirect('/login')->withErrors(['error' => 'Rol no configurado']),
            };
        }

        // Si los datos no coinciden
        return back()->withErrors(['error' => 'Matrícula o contraseña incorrectas.']);
    }

    // Función para cerrar sesión
    public function logout() {
        Session::flush();
        return redirect('/login');
    }
}