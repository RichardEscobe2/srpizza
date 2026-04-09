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

        // 2. Buscar al usuario en MariaDB usando el Modelo
        $usuario = Usuario::where('matricula', $request->matricula)
                          ->where('activo', 1)
                          ->first();

        // 3. Validar las credenciales
        if ($usuario && Hash::check($request->contrasena, $usuario->contrasena)) {
            
            // 4. Iniciar sesión guardando datos vitales del empleado
           // Cuando la contraseña es correcta, guardamos las variables de sesión
                Session::put('usuario_id', $usuario->id_usuario);
                Session::put('nombre', $usuario->nombre_completo);
                Session::put('id_rol', $usuario->id_rol);

            // 8. Redirección basada en el id_rol (Corregido según tu BD)
            return match ($usuario->id_rol) {
                1 => redirect('/admin/dashboard'), // Rol 1: SuperAdministrador
                2 => redirect('/mesero/mesas'),    // Rol 2: Mesero
                3 => redirect('/gerente/dashboard'),// Rol 3: Gerente Operativo
                4 => redirect('/cocina/kds'),      // Rol 4: Cocinero
                5 => redirect('/caja/ordenes'),    // Rol 5: Cajero
                default => redirect('/login')->withErrors(['error' => 'Rol desconocido']),
            };
        }

        // Si la validación falla
        return back()->withErrors(['error' => 'Matrícula o contraseña incorrectas.']);
    }

    // Función para cerrar sesión
    public function logout() {
        Session::flush();
        return redirect('/login');
    }
}