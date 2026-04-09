<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class GerenteController extends Controller
{
     // 1. Mostrar el Dashboard principal
    public function index() {
        // A) DATOS PARA SECCIÓN OPERACIÓN (Monitor de Piso)
        $mesas = DB::table('mesas')
            ->leftJoin('pedidos', function($join) {
                $join->on('mesas.mesa_id', '=', 'pedidos.mesa_id')
                     ->whereNotIn('pedidos.estado', ['Pagado', 'Cancelado', 'Entregado']);
            })
            ->select('mesas.*', 'pedidos.fecha_hora as pedido_fecha')
            ->get();

        // B) DATOS PARA MONITOR DE COCINA
        $pedidosEnCola = DB::table('pedidos')
            ->whereIn('estado', ['Pendiente', 'En Horno'])
            ->count();

        // C) CORRECCIÓN DE TIEMPO PROMEDIO 
        $fechasPedidos = DB::table('pedidos')
            ->whereIn('estado', ['Pendiente', 'En Horno'])
            ->pluck('fecha_hora');

        $tiempoTotal = 0;
        foreach ($fechasPedidos as $fecha) {
            $minutos = abs(\Carbon\Carbon::parse($fecha)->diffInMinutes(now()));
            $tiempoTotal += $minutos;
        }
        $tiempoPromedio = $pedidosEnCola > 0 ? round($tiempoTotal / $pedidosEnCola) : 0;

       // NUEVO: D) DATOS PARA SECCIÓN PERSONAL (Filtrado solo a roles operativos: 2, 4 y 5)
        $usuarios = DB::table('usuarios')
            ->join('roles', 'usuarios.id_rol', '=', 'roles.rol_id')
            ->whereIn('usuarios.id_rol', [2, 4, 5])
            ->select('usuarios.id_usuario', 'usuarios.nombre_completo', 'usuarios.matricula', 'roles.nombre_rol', 'usuarios.activo', 'usuarios.porcentaje_comision')
            ->get();

        return view('gerente.dashboard', compact('mesas', 'pedidosEnCola', 'tiempoPromedio', 'usuarios'));
    }

    // 2. Baja Lógica o Reactivación de un usuario (RN-01) [1, 6]
    public function alternarEstado($id) {
        $usuario = DB::table('usuarios')->where('id_usuario', $id)->first();
        
        if ($usuario) {
            // Si está activo (1) lo pasa a inactivo (0) y viceversa
            $nuevoEstado = $usuario->activo == 1 ? 0 : 1;
            DB::table('usuarios')->where('id_usuario', $id)->update(['activo' => $nuevoEstado]);
            
            $mensaje = $nuevoEstado == 1 ? 'Usuario reactivado exitosamente.' : 'Usuario dado de baja (desactivado) exitosamente.';
            return back()->with('success', $mensaje);
        }
        return back()->withErrors(['error' => 'Usuario no encontrado.']);
    }

    // 3. Cambio de contraseña (Gestión básica) [1]
    public function cambiarPassword(Request $request, $id) {
        $request->validate(['nueva_password' => 'required|min:3']);
        
        DB::table('usuarios')->where('id_usuario', $id)->update([
           'contrasena' => Hash::make($request->nueva_password)
        ]);
        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

   // Actualizar porcentaje de comisión (Gestión Gerencial)
    public function cambiarComision(Request $request, $id) {
        $request->validate(['nueva_comision' => 'required|numeric|min:0|max:100']);
        
        DB::table('usuarios')->where('id_usuario', $id)->update([
            'porcentaje_comision' => $request->nueva_comision
        ]);

        return back()->with('success', 'Comisión actualizada correctamente.');
    }
    // Función para dar de alta nuevo personal operativo (Restringido para Gerente)
    public function crearPersonal(Request $request) {
        // 1. Candado de Seguridad: Solo permite roles 2(Mesero), 4(Cocinero) y 5(Cajero)
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'telefono' => 'required|string|max:15',
            'matricula' => 'required|integer|unique:usuarios,matricula',
            'contrasena' => 'required|string|min:3',
            'id_rol' => 'required|in:2,4,5', 
            'comision' => 'nullable|numeric|min:0|max:100'
        ]);

        DB::beginTransaction();
        try {
            // Mapeamos el ID del rol al texto que exige el ENUM 'puesto' de la tabla empleados
            $puestoEnum = match((int)$request->id_rol) {
                2 => 'Mesero',
                4 => 'Cocinero',
                5 => 'Cajero',
                default => 'Mesero'
            };

            // 2. Insertamos primero en la tabla `empleados` y obtenemos su ID generado
            $empleadoId = DB::table('empleados')->insertGetId([
                'nombre_completo' => $request->nombre_completo,
                'telefono' => $request->telefono,
                'puesto' => $puestoEnum,
                'activo' => 1,
                'fecha_contratacion' => now()
            ]);

            // 3. Insertamos en la tabla `usuarios` para darle acceso al sistema
            DB::table('usuarios')->insert([
                'empleado_id' => $empleadoId,
                'nombre_completo' => $request->nombre_completo,
                'matricula' => $request->matricula,
                        'contrasena' => Hash::make($request->contrasena),
                'id_rol' => $request->id_rol,
                'activo' => 1,
                'limite_descuento' => 0, // Por defecto 0, el Admin lo cambia después si es necesario
                'porcentaje_comision' => $request->comision ?? 0 // Se asigna la comisión que pidió el Gerente
            ]);

            DB::commit();
            return back()->with('success', '¡Personal operativo (' . $puestoEnum . ') creado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al registrar al empleado: ' . $e->getMessage()]);
        }
    }   
}