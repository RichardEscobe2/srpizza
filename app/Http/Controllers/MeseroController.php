<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\Categoria;
// IMPORTANTE: Asegúrate de tener el modelo Producto creado (en lugar de Menu)
use App\Models\Producto; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MeseroController extends Controller
{
    public function index() {
        $mesas = Mesa::all();
        return view('mesero.mesas', compact('mesas'));
    }

    public function tomaPedido($mesa_id) {
        $mesa = Mesa::findOrFail($mesa_id);
        $categorias = Categoria::all();

        // Corrección: Cambiamos 'menu' por 'producto', 'recetas' por 'receta' y 'insumos' por 'insumo'
        $productos = collect(DB::select("
            SELECT m.producto_id, m.categoria_id, m.nombre, m.precio, m.tamano, m.es_preparado, m.activo, m.es_recomendado,
                   IFNULL(FLOOR(MIN(i.stock_actual / r.cantidad_requerida)), 999) as stock_disponible
            FROM producto m
            LEFT JOIN receta r ON m.producto_id = r.producto_id
            LEFT JOIN insumo i ON r.insumo_id = i.insumo_id
            WHERE m.activo = 1
            GROUP BY m.producto_id, m.categoria_id, m.nombre, m.precio, m.tamano, m.es_preparado, m.activo, m.es_recomendado
        "));

        // Corrección: Cambiamos 'pedidos' por 'pedido'
        $pedidosActivos = DB::table('pedido')
            ->where('mesa_id', $mesa_id)
            ->whereNotIn('estado', ['Pagado', 'Cancelado'])
            ->get();

        $detallesCocina = collect();
        $detallesListo = collect();

        if ($pedidosActivos->isNotEmpty()) {
            // Corrección: Cambiamos 'detalles_pedido' por 'detalle', 'menu' por 'producto', y 'pedidos' por 'pedido'
            $todosDetalles = DB::table('detalle')
                ->join('producto', 'detalle.producto_id', '=', 'producto.producto_id')
                ->join('pedido', 'detalle.pedido_id', '=', 'pedido.pedido_id')
                ->whereIn('detalle.pedido_id', $pedidosActivos->pluck('pedido_id'))
                ->select('detalle.*', 'producto.nombre', 'pedido.estado')
                ->get();

            // Aquí está la magia: separamos los platillos según el estado del pedido
            $detallesCocina = $todosDetalles->where('estado', 'Pendiente');
            $detallesListo = $todosDetalles->where('estado', 'Listo');
        }

        return view('mesero.pedido', compact('mesa', 'categorias', 'productos', 'detallesCocina', 'detallesListo'));
    }

    public function guardarPedido(Request $request, $mesa_id) {
        $json = $request->input('json_pedido');
        $items = json_decode($json, true);

        if (empty($items)) {
            return back(); 
        }

        $usuario_id = Session::get('usuario_id'); 

        DB::beginTransaction();
        try {
            // Corrección: 'pedidos' a 'pedido'
            $pedido = DB::table('pedido')
                ->where('mesa_id', $mesa_id)
                ->where('estado', 'Pendiente')
                ->first();

            $pedido_id = null;
            $total_nuevo = 0;

            if (!$pedido) {
                // Corrección: 'pedidos' a 'pedido'
                $pedido_id = DB::table('pedido')->insertGetId([
                    'usuario_id' => $usuario_id,
                    'mesa_id' => $mesa_id,
                    'estado' => 'Pendiente',
                    'total' => 0,
                    'fecha_hora' => now()
                ]);
                // Corrección: 'mesas' a 'mesa'
                DB::table('mesa')->where('mesa_id', $mesa_id)->update(['estado' => 'Ocupada']);
            } else {
                $pedido_id = $pedido->pedido_id;
            }

            foreach ($items as $item) {
                // Corrección: 'menu' a 'producto'
                $precio_real = DB::table('producto')->where('producto_id', $item['id'])->value('precio') ?? 0;
                
                // Corrección: 'detalles_pedido' a 'detalle'
                DB::table('detalle')->insert([
                    'pedido_id' => $pedido_id,
                    'producto_id' => $item['id'],
                    'cantidad' => 1,
                    'precio_unitario' => $precio_real,
                    'comentarios' => $item['nota'] ?? null
                ]);
                $total_nuevo += $precio_real;
            }

            $total_actual = $pedido ? $pedido->total : 0;
            // Corrección: 'pedidos' a 'pedido'
            DB::table('pedido')->where('pedido_id', $pedido_id)->update([
                'total' => $total_actual + $total_nuevo
            ]);

            DB::commit(); 
            return redirect()->route('mesero.mesas')->with('success', '¡Comanda enviada satisfactoriamente a cocina!');

        } catch (\Exception $e) {
            DB::rollBack(); 
            return back()->withErrors(['error' => 'Error al guardar la orden. Verifica el inventario.']);
        }
    }

    public function limpiarMesa($mesa_id) {
        // Corrección: 'mesas' a 'mesa'
        DB::table('mesa')->where('mesa_id', $mesa_id)->update(['estado' => 'Disponible']);
        return redirect()->route('mesero.mesas')->with('success', '¡Mesa lista para recibir nuevos clientes!');
    }

    public function checarAlertas() {
        $usuario_id = Session::get('usuario_id');
        
        // Corrección: 'pedidos' a 'pedido' y 'mesas' a 'mesa'
        $pedidosListos = DB::table('pedido')
            ->join('mesa', 'pedido.mesa_id', '=', 'mesa.mesa_id')
            ->where('pedido.usuario_id', $usuario_id)
            ->where('pedido.estado', 'Listo')
            ->select('pedido.pedido_id', 'mesa.numero_mesa')
            ->get();

        return response()->json($pedidosListos);
    }
}