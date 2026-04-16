<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class GerenteController extends Controller
{
    public function index() {
        $hoy = Carbon::today();

        // 1. OPERACIÓN: Mesas y Tiempos
        $mesas = DB::table('mesa')
            ->leftJoin('pedido', function($join) {
                $join->on('mesa.mesa_id', '=', 'pedido.mesa_id')
                     ->whereNotIn('pedido.estado', ['Pagado', 'Cancelado', 'Entregado']);
            })
            ->select('mesa.*', 'pedido.fecha_hora as pedido_fecha')
            ->get();

        $pedidosEnCola = DB::table('pedido')->whereIn('estado', ['Pendiente', 'En Horno', 'En Preparación'])->count();
        $fechasPedidos = DB::table('pedido')->whereIn('estado', ['Pendiente', 'En Horno', 'En Preparación'])->pluck('fecha_hora');

        $tiempoTotal = 0;
        foreach ($fechasPedidos as $fecha) {
            $minutos = abs(\Carbon\Carbon::parse($fecha)->diffInMinutes(now()));
            $tiempoTotal += $minutos;
        }
        $tiempoPromedio = $pedidosEnCola > 0 ? round($tiempoTotal / $pedidosEnCola) : 0;

        $pedidosCaja = DB::table('pedido')
            ->join('mesa', 'pedido.mesa_id', '=', 'mesa.mesa_id')
            ->whereIn('pedido.estado', ['Listo', 'Entregado'])
            ->select('pedido.pedido_id', 'pedido.total', 'pedido.estado', 'mesa.numero_mesa')
            ->get();

        // 2. CAJA: Finanzas del Día
        $ingresosHoy = DB::table('pedido')->where('estado', 'Pagado')->whereDate('fecha_hora', $hoy)->sum('total');
        $volumenVentasHoy = DB::table('pedido')->where('estado', 'Pagado')->whereDate('fecha_hora', $hoy)->count();

        // CIRUGÍA NUEVA: Traer los detalles de los tickets cobrados
        $ventasHoy = DB::table('pedido')
            ->join('mesa', 'pedido.mesa_id', '=', 'mesa.mesa_id')
            ->where('pedido.estado', 'Pagado')
            ->whereDate('pedido.fecha_hora', $hoy)
            ->select('pedido.pedido_id', 'mesa.numero_mesa', 'pedido.fecha_hora', 'pedido.total')
            ->orderBy('pedido.fecha_hora', 'desc')
            ->get();

        // 3. INSUMOS Y MENÚ
        $alertasStock = DB::table('insumo')->whereColumn('stock_actual', '<=', 'stock_minimo')
                          ->select('nombre', 'stock_actual as stock_actual', 'stock_minimo')->get();
        
        $productosMenu = DB::table('producto')->get(); // Para edición de precios

        // 4. PERSONAL OPERATIVO
        $usuarios = DB::table('usuario')
            ->join('rol', 'usuario.id_rol', '=', 'rol.rol_id')
            ->whereIn('usuario.id_rol', [2, 4, 5]) // Solo roles operativos (Mesero, Cocinero, Cajero)
            ->select('usuario.id_usuario', 'usuario.nombre_completo', 'usuario.matricula', 'rol.nombre_rol', 'usuario.id_rol', 'usuario.activo', 'usuario.porcentaje_comision')
            ->get();

        // Obtener los roles operativos para los selects de los modales
        $rolesOperativos = DB::table('rol')->whereIn('rol_id', [2, 4, 5])->get();

        // 5. BITÁCORA DE AUDITORÍA (Try-Catch por si la tabla tiene otro nombre)
        try {
            // Cambiamos 'fecha_hora' por 'fecha'
            $bitacora = DB::table('bitacora')->orderBy('fecha', 'desc')->limit(50)->get();
        } catch (\Exception $e) {
            $bitacora = collect([]); 
        }

        return view('gerente.dashboard', compact(
            'mesas', 'pedidosEnCola', 'tiempoPromedio', 'pedidosCaja', 
            'ingresosHoy', 'volumenVentasHoy', 'ventasHoy', 'alertasStock', 'productosMenu', 'usuarios', 'bitacora', 'rolesOperativos'
        ));
    }

   public function alternarEstado($id) {
        $usuario = DB::table('usuario')->where('id_usuario', $id)->first();
        
        if ($usuario) {
            $nuevoEstado = $usuario->activo == 1 ? 0 : 1;
            DB::table('usuario')->where('id_usuario', $id)->update(['activo' => $nuevoEstado]);
            
            $tipoAccion = $nuevoEstado == 1 ? 'REACTIVACIÓN DE PERSONAL' : 'BAJA DE PERSONAL';
            
            // 🚨 Sincronizado con tu BD
            DB::table('bitacora')->insert([
                'fecha' => now(), 
                'accion' => $tipoAccion,
                'tabla_afectada' => 'usuario',
                'detalles' => "El empleado {$usuario->nombre_completo} (Matrícula: {$usuario->matricula}) cambió a estado " . ($nuevoEstado == 1 ? 'Activo' : 'Inactivo')
            ]);
            
            return back()->with('success', $nuevoEstado == 1 ? 'Usuario reactivado.' : 'Usuario dado de baja.');
        }
        return back()->withErrors(['error' => 'Usuario no encontrado.']);
    }

    public function cambiarPassword(Request $request, $id) {
        $request->validate(['nueva_password' => 'required|min:3']);
        DB::table('usuario')->where('id_usuario', $id)->update(['contrasena' => Hash::make($request->nueva_password)]);
        return back()->with('success', 'Contraseña actualizada.');
    }

    public function cambiarComision(Request $request, $id) {
        $request->validate(['nueva_comision' => 'required|numeric|min:0|max:100']);
        DB::table('usuario')->where('id_usuario', $id)->update(['porcentaje_comision' => $request->nueva_comision]);
        return back()->with('success', 'Comisión actualizada.');
    }

    public function crearPersonal(Request $request) {
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
            $puestoEnum = match((int)$request->id_rol) { 2 => 'Mesero', 4 => 'Cocinero', 5 => 'Cajero', default => 'Mesero' };
            $nuevoEmpleadoId = DB::table('empleado')->max('empleado_id') + 1;

            DB::table('empleado')->insert([
                'empleado_id' => $nuevoEmpleadoId,
                'nombre_completo' => $request->nombre_completo,
                'telefono' => $request->telefono,
                'puesto' => $puestoEnum,
                'activo' => 1,
                'fecha_contratacion' => now()
            ]);

            $nuevoUsuarioId = DB::table('usuario')->max('id_usuario') + 1;
            DB::table('usuario')->insert([
                'id_usuario' => $nuevoUsuarioId,
                'empleado_id' => $nuevoEmpleadoId,
                'nombre_completo' => $request->nombre_completo,
                'matricula' => $request->matricula,
                'contrasena' => Hash::make($request->contrasena),
                'id_rol' => $request->id_rol,
                'activo' => 1,
                'limite_descuento' => 0, 
                'porcentaje_comision' => $request->comision ?? 0 
            ]);

            DB::commit();
            return back()->with('success', 'Personal creado exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

  public function actualizarPrecio(Request $request, $id) {
        $request->validate(['nuevo_precio' => 'required|numeric|min:0']);
        
        $producto = DB::table('producto')->where('producto_id', $id)->first();
        DB::table('producto')->where('producto_id', $id)->update(['precio' => $request->nuevo_precio]);
        
        // 🚨 Sincronizado con tu BD
        DB::table('bitacora')->insert([
            'fecha' => now(), 
            'accion' => 'MODIFICACIÓN DE PRECIO',
            'tabla_afectada' => 'producto',
            'detalles' => "Se modificó el precio de '{$producto->nombre}'. Precio anterior: \${$producto->precio} -> Nuevo precio: \${$request->nuevo_precio}"
        ]);

        return back()->with('success', 'Precio del platillo actualizado correctamente.');
    }


    // --- NUEVA FUNCIÓN: PROCESAR CORTE DE CAJA ---
    public function procesarCorte(Request $request) {
        $hoy = Carbon::today();
        
        // 1. Calculamos cuánto dinero se va a cortar (solo los Pagados de hoy)
        $totalVentas = DB::table('pedido')
            ->where('estado', 'Pagado')
            ->whereDate('fecha_hora', $hoy)
            ->sum('total');
            
        // Si no hay ventas, avisamos que no hay nada que cortar
        if ($totalVentas == 0) {
            return back()->withErrors(['error' => 'No hay ventas cobradas pendientes de corte.']);
        }
        
        DB::beginTransaction();
        try {
            // 2. Cambiamos el estado a 'Cerrado' para que la caja se reinicie a $0
            DB::table('pedido')
                ->where('estado', 'Pagado')
                ->whereDate('fecha_hora', $hoy)
                ->update(['estado' => 'Cerrado']);
                
            // 3. (Opcional pero recomendado) Guardamos el movimiento en tu bitácora
            try {
                DB::table('bitacora')->insert([
                    'fecha_hora' => now(),
                    'accion' => 'CORTE DE CAJA',
                    'tabla' => 'pedido',
                    'detalles' => 'Se autorizó corte por un total de $' . number_format($totalVentas + 500, 2) . ' (Incluyendo $500 de fondo inicial).'
                ]);
            } catch (\Exception $e) {
                // Silencioso por si la tabla bitacora no tiene esa estructura exacta
            }

            DB::commit();
            return back()->with('success', '¡Corte de caja autorizado! Total retirado: $' . number_format($totalVentas + 500, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar el corte: ' . $e->getMessage()]);
        }
    }

    // --- NUEVA FUNCIÓN: ACTUALIZAR ROL Y SALARIO/COMISIÓN ---
    public function actualizarEmpleado(Request $request, $id) {
        $request->validate([
            'nuevo_rol' => 'required|in:2,4,5',
            'nueva_comision' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // 1. Obtener el usuario para saber qué empleado es
            $usuario = DB::table('usuario')->where('id_usuario', $id)->first();
            
            if(!$usuario) {
                return back()->withErrors(['error' => 'Usuario no encontrado.']);
            }

            // 2. Actualizar la tabla usuario (Rol y Comisión/Salario)
            DB::table('usuario')->where('id_usuario', $id)->update([
                'id_rol' => $request->nuevo_rol,
                'porcentaje_comision' => $request->nueva_comision
            ]);

            // 3. Mapear el nuevo ID de rol al nombre del puesto para la tabla empleado
            $puestoEnum = match((int)$request->nuevo_rol) {
                2 => 'Mesero',
                4 => 'Cocinero',
                5 => 'Cajero',
                default => 'Mesero'
            };

            // 4. Actualizar la tabla empleado (Puesto)
            DB::table('empleado')->where('empleado_id', $usuario->empleado_id)->update([
                'puesto' => $puestoEnum
            ]);

            DB::commit();
            return back()->with('success', 'Datos del empleado actualizados correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }
}