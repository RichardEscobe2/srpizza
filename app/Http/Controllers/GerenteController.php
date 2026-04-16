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
        // CORRECCIÓN: 'mesas' -> 'mesa', 'pedidos' -> 'pedido'
        $mesas = DB::table('mesa')
            ->leftJoin('pedido', function($join) {
                $join->on('mesa.mesa_id', '=', 'pedido.mesa_id')
                     ->whereNotIn('pedido.estado', ['Pagado', 'Cancelado', 'Entregado']);
            })
            ->select('mesa.*', 'pedido.fecha_hora as pedido_fecha')
            ->get();

        // B) DATOS PARA MONITOR DE COCINA
        // CORRECCIÓN: 'pedidos' -> 'pedido'
        $pedidosEnCola = DB::table('pedido')
            ->whereIn('estado', ['Pendiente', 'En Horno'])
            ->count();

        // C) CORRECCIÓN DE TIEMPO PROMEDIO 
        // CORRECCIÓN: 'pedidos' -> 'pedido'
        $fechasPedidos = DB::table('pedido')
            ->whereIn('estado', ['Pendiente', 'En Horno'])
            ->pluck('fecha_hora');

        $tiempoTotal = 0;
        foreach ($fechasPedidos as $fecha) {
            $minutos = abs(\Carbon\Carbon::parse($fecha)->diffInMinutes(now()));
            $tiempoTotal += $minutos;
        }
        $tiempoPromedio = $pedidosEnCola > 0 ? round($tiempoTotal / $pedidosEnCola) : 0;

       // NUEVO: D) DATOS PARA SECCIÓN PERSONAL (Filtrado solo a roles operativos: 2, 4 y 5)
        // CORRECCIÓN: 'usuarios' -> 'usuario', 'roles' -> 'rol'
        $usuarios = DB::table('usuario')
            ->join('rol', 'usuario.id_rol', '=', 'rol.rol_id')
            ->whereIn('usuario.id_rol', [2, 4, 5])
            ->select('usuario.id_usuario', 'usuario.nombre_completo', 'usuario.matricula', 'rol.nombre_rol', 'usuario.activo', 'usuario.porcentaje_comision')
            ->get();

        return view('gerente.dashboard', compact('mesas', 'pedidosEnCola', 'tiempoPromedio', 'usuarios'));
    }

    // 2. Baja Lógica o Reactivación de un usuario (RN-01) [1, 6]
    public function alternarEstado($id) {
        // CORRECCIÓN: 'usuarios' -> 'usuario'
        $usuario = DB::table('usuario')->where('id_usuario', $id)->first();
        
        if ($usuario) {
            // Si está activo (1) lo pasa a inactivo (0) y viceversa
            $nuevoEstado = $usuario->activo == 1 ? 0 : 1;
            // CORRECCIÓN: 'usuarios' -> 'usuario'
            DB::table('usuario')->where('id_usuario', $id)->update(['activo' => $nuevoEstado]);
            
            $mensaje = $nuevoEstado == 1 ? 'Usuario reactivado exitosamente.' : 'Usuario dado de baja (desactivado) exitosamente.';
            return back()->with('success', $mensaje);
        }
        return back()->withErrors(['error' => 'Usuario no encontrado.']);
    }

    // 3. Cambio de contraseña (Gestión básica) [1]
    public function cambiarPassword(Request $request, $id) {
        $request->validate(['nueva_password' => 'required|min:3']);
        
        // CORRECCIÓN: 'usuarios' -> 'usuario'
        DB::table('usuario')->where('id_usuario', $id)->update([
           'contrasena' => Hash::make($request->nueva_password)
        ]);
        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

   // Actualizar porcentaje de comisión (Gestión Gerencial)
    public function cambiarComision(Request $request, $id) {
        $request->validate(['nueva_comision' => 'required|numeric|min:0|max:100']);
        
        // CORRECCIÓN: 'usuarios' -> 'usuario'
        DB::table('usuario')->where('id_usuario', $id)->update([
            'porcentaje_comision' => $request->nueva_comision
        ]);

        return back()->with('success', 'Comisión actualizada correctamente.');
    }

    // Función para dar de alta nuevo personal operativo (Restringido para Gerente)
    public function crearPersonal(Request $request) {
        // 1. Candado de Seguridad: Solo permite roles 2(Mesero), 4(Cocinero) y 5(Cajero)
        // CORRECCIÓN en validación unique: 'usuarios' -> 'usuario'
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'telefono' => 'required|string|max:15',
            'matricula' => 'required|integer|unique:usuario,matricula',
            'contrasena' => 'required|string|min:3',
            'id_rol' => 'required|in:2,4,5', 
            'comision' => 'nullable|numeric|min:0|max:100'
        ]);

        DB::beginTransaction();
        try {
            // Mapeamos el ID del rol al texto que exige el puesto de la tabla empleado
            $puestoEnum = match((int)$request->id_rol) {
                2 => 'Mesero',
                4 => 'Cocinero',
                5 => 'Cajero',
                default => 'Mesero'
            };

            // 2. Insertamos primero en la tabla `empleado` y obtenemos su ID generado
            // CORRECCIÓN: 'empleados' -> 'empleado'
            $empleadoId = DB::table('empleado')->insertGetId([
                'nombre_completo' => $request->nombre_completo,
                'telefono' => $request->telefono,
                'puesto' => $puestoEnum,
                'activo' => 1,
                'fecha_contratacion' => now()
            ]);

            // 3. Insertamos en la tabla `usuario` para darle acceso al sistema
            // CORRECCIÓN: 'usuarios' -> 'usuario'
            DB::table('usuario')->insert([
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