<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ==========================================
    // 1. FUNCIÓN PRINCIPAL (CARGA EL DASHBOARD)
    // ==========================================
    public function index() {
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        // BALANCE FINANCIERO MENSUAL
        $ingresosMes = DB::table('pedido')
            ->whereIn('estado', ['Pagado', 'Cerrado'])
            ->whereMonth('fecha_hora', $mesActual)
            ->whereYear('fecha_hora', $anioActual)
            ->sum('total');

        try {
            $costosComprasMes = DB::table('compra_insumo')
                ->whereMonth('fecha_compra', $mesActual)
                ->sum('costo_total');
        } catch (\Exception $e) {
            $costosComprasMes = 0; 
        }

        $nominaMes = DB::table('usuario')->where('activo', 1)->sum('porcentaje_comision') * 30;
        $utilidadNeta = $ingresosMes - $costosComprasMes - $nominaMes;

        // GESTIÓN DE PERSONAL
        $usuariosTodos = DB::table('usuario')
            ->join('rol', 'usuario.id_rol', '=', 'rol.rol_id')
            ->select('usuario.id_usuario', 'usuario.nombre_completo', 'usuario.matricula', 'rol.nombre_rol', 'usuario.id_rol', 'usuario.activo', 'usuario.porcentaje_comision')
            ->get();
            
        $rolesTodos = DB::table('rol')->get();

        // CATÁLOGO DE MENÚ
        $productosMenu = DB::table('producto')->get();

        // ABASTECIMIENTO
        $insumos = DB::table('insumo')->select('insumo_id', 'nombre', 'unidad_medida', 'stock_actual')->get();
        try {
            $proveedores = DB::table('proveedor')->get();
        } catch (\Exception $e) {
            $proveedores = collect([]);
        }

        // AJUSTES: Fila única de configuración
        $configBD = DB::table('config')->first(); 

        return view('admin.dashboard', compact(
            'ingresosMes', 'costosComprasMes', 'nominaMes', 'utilidadNeta',
            'usuariosTodos', 'rolesTodos', 'productosMenu', 'insumos', 'proveedores', 'configBD'
        ));
    }

    // ==========================================
    // 2. MÓDULO PERSONAL
    // ==========================================

    public function crearPersonal(Request $request) {
        $request->validate([
            'nombre_completo' => 'required|string',
            'telefono' => 'required|string',
            'matricula' => 'required|integer|unique:usuario,matricula',
            'contrasena' => 'required|string|min:3',
            'id_rol' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $puestoEnum = match((int)$request->id_rol) { 
                1 => 'Administrador', 2 => 'Mesero', 3 => 'Gerente', 4 => 'Cocinero', 5 => 'Cajero', default => 'Mesero' 
            };

            $nuevoEmpleadoId = (DB::table('empleado')->max('empleado_id') ?? 0) + 1;
            DB::table('empleado')->insert([
                'empleado_id' => $nuevoEmpleadoId,
                'nombre_completo' => $request->nombre_completo,
                'telefono' => $request->telefono,
                'puesto' => $puestoEnum,
                'activo' => 1,
                'fecha_contratacion' => now()
            ]);

            $nuevoUsuarioId = (DB::table('usuario')->max('id_usuario') ?? 0) + 1;
            DB::table('usuario')->insert([
                'id_usuario' => $nuevoUsuarioId,
                'empleado_id' => $nuevoEmpleadoId,
                'nombre_completo' => $request->nombre_completo,
                'matricula' => $request->matricula,
                'contrasena' => Hash::make($request->contrasena),
                'id_rol' => $request->id_rol,
                'activo' => 1,
                'limite_descuento' => 0, 
                'porcentaje_comision' => 0 
            ]);

            DB::commit();
            return back()->with('success', 'Personal creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function eliminarPersonalDefinitivo($id) {
        try {
            $usuario = DB::table('usuario')->where('id_usuario', $id)->first();
            if($usuario) {
                DB::table('usuario')->where('id_usuario', $id)->delete();
                DB::table('empleado')->where('empleado_id', $usuario->empleado_id)->delete();
                return back()->with('success', 'Usuario eliminado de raíz.');
            }
            return back()->withErrors(['error' => 'No encontrado.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar: ' . $e->getMessage()]);
        }
    }

    // ==========================================
    // 3. MÓDULO MENÚ Y RECETAS
    // ==========================================

    public function crearProducto(Request $request) {
        $request->validate([
            'nombre' => 'required', 'tamano' => 'required', 'precio' => 'required',
            'insumos' => 'required|array', 'cantidades' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $nuevoProdId = (DB::table('producto')->max('producto_id') ?? 0) + 1;
            DB::table('producto')->insert([
                'producto_id' => $nuevoProdId,
                'nombre' => $request->nombre,
                'tamano' => $request->tamano,
                'precio' => $request->precio
            ]);

            foreach ($request->insumos as $i => $insumo_id) {
                if (!empty($insumo_id) && !empty($request->cantidades[$i])) {
                    $nuevaRecId = (DB::table('receta')->max('receta_id') ?? 0) + 1;
                    DB::table('receta')->insert([
                        'receta_id' => $nuevaRecId,
                        'producto_id' => $nuevoProdId,
                        'insumo_id' => $insumo_id,
                        'cantidad_requerida' => $request->cantidades[$i] 
                    ]);
                }
            }
            DB::commit();
            return back()->with('success', '¡Platillo y Receta creados!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function actualizarReceta(Request $request) {
        $request->validate(['producto_id' => 'required', 'insumos' => 'required|array', 'cantidades' => 'required|array']);
        DB::beginTransaction();
        try {
            DB::table('receta')->where('producto_id', $request->producto_id)->delete();
            foreach ($request->insumos as $i => $insumo_id) {
                if (!empty($insumo_id) && !empty($request->cantidades[$i])) {
                    $nuevaRecId = (DB::table('receta')->max('receta_id') ?? 0) + 1;
                    DB::table('receta')->insert([
                        'receta_id' => $nuevaRecId,
                        'producto_id' => $request->producto_id,
                        'insumo_id' => $insumo_id,
                        'cantidad_requerida' => $request->cantidades[$i]
                    ]);
                }
            }
            DB::commit();
            return back()->with('success', 'Receta actualizada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function eliminarProducto($id) {
        try {
            DB::table('receta')->where('producto_id', $id)->delete();
            DB::table('producto')->where('producto_id', $id)->delete();
            return back()->with('success', 'Producto eliminado.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se puede eliminar: tiene registros históricos.']);
        }
    }

    // ==========================================
    // 4. MÓDULO ABASTECIMIENTO
    // ==========================================

    public function registrarCompra(Request $request) {
        $request->validate(['proveedor_id' => 'required', 'insumo_id' => 'required', 'cantidad' => 'required', 'costo_total' => 'required']);
        DB::beginTransaction();
        try {
            DB::table('compra_insumo')->insert([
                'proveedor_id' => $request->proveedor_id,
                'insumo_id' => $request->insumo_id,
                'cantidad' => $request->cantidad,
                'costo_total' => $request->costo_total,
                'fecha_compra' => now()
            ]);
            DB::table('insumo')->where('insumo_id', $request->insumo_id)->increment('stock_actual', $request->cantidad);
            DB::commit();
            return back()->with('success', 'Compra registrada y stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ==========================================
    // 5. MÓDULO AJUSTES Y RESPALDO
    // ==========================================
    public function actualizarConfiguracion(Request $request) {
        try {
            DB::table('config')->where('config_id', 1)->update([
                'nombre_empresa' => $request->nombre_empresa,
                'iva'            => $request->iva,
            ]);
            
            return back()->with('success', 'Ajustes actualizados correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function respaldarBaseDatos() {
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $fileName = "Backup_SrPizza_" . date('Y-m-d_His') . ".sql";
        
        $command = "mysqldump --user=$dbUser --password=$dbPass $dbName";
        
        $output = []; $returnVar = null;
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return response(implode("\n", $output))
                ->header('Content-Type', 'application/sql')
                ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
        }
        return back()->withErrors(['error' => 'No se pudo generar el respaldo. Revisa permisos de mysqldump.']);
    }
}