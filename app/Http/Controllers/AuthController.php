<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;

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
        if ($usuario && $usuario->contrasena === $request->contrasena) {
            
            // 4. Iniciar sesión guardando datos vitales del empleado
           // Cuando la contraseña es correcta, guardamos las variables de sesión
                Session::put('usuario_id', $usuario->id_usuario);
                Session::put('nombre', $usuario->nombre_completo);
                Session::put('id_rol', $usuario->id_rol);

            // 5. Asignar el usuario de MariaDB basado en el rol (RNF-04)
            $db_user = match ($usuario->id_rol) {
                1 => 'admin_restaurante', 
                3 => 'gerente_restaurante', 
                default => 'empleado_restaurante' 
            };

            // 6. Asignar la contraseña del servidor MariaDB
            $db_pass = match ($usuario->id_rol) {
                1 => '123',     // Contraseña de admin_restaurante
                3 => '456',     // Contraseña de gerente_restaurante
                default => '789'// Contraseña de empleado_restaurante
            };

            // 7. Guardamos las credenciales del servidor en la sesión para el Middleware
            Session::put('db_user', $db_user);
            Session::put('db_pass', $db_pass);

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