<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\Categoria;
use App\Models\Menu;
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

        // Traemos menú con la validación de stock
        $productos = collect(DB::select("
            SELECT m.producto_id, m.categoria_id, m.nombre, m.precio, m.tamano, m.es_preparado, m.activo, m.es_recomendado,
                   IFNULL(FLOOR(MIN(i.stock_actual / r.cantidad_requerida)), 999) as stock_disponible
            FROM menu m
            LEFT JOIN recetas r ON m.producto_id = r.producto_id
            LEFT JOIN insumos i ON r.insumo_id = i.insumo_id
            WHERE m.activo = 1
            GROUP BY m.producto_id, m.categoria_id, m.nombre, m.precio, m.tamano, m.es_preparado, m.activo, m.es_recomendado
        "));

        // Obtenemos TODOS los pedidos activos de esta mesa
        $pedidosActivos = DB::table('pedidos')
            ->where('mesa_id', $mesa_id)
            ->whereNotIn('estado', ['Pagado', 'Cancelado'])
            ->get();

        $detallesCocina = collect();
        $detallesListo = collect();

        if ($pedidosActivos->isNotEmpty()) {
            $todosDetalles = DB::table('detalles_pedido')
                ->join('menu', 'detalles_pedido.producto_id', '=', 'menu.producto_id')
                ->join('pedidos', 'detalles_pedido.pedido_id', '=', 'pedidos.pedido_id')
                ->whereIn('detalles_pedido.pedido_id', $pedidosActivos->pluck('pedido_id'))
                ->select('detalles_pedido.*', 'menu.nombre', 'pedidos.estado')
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
            // Buscamos si existe un pedido estrictamente 'Pendiente' (En cocina)
            $pedido = DB::table('pedidos')
                ->where('mesa_id', $mesa_id)
                ->where('estado', 'Pendiente')
                ->first();

            $pedido_id = null;
            $total_nuevo = 0;

            // Si no hay pedido pendiente (porque los anteriores ya están "Listos"), CREAMOS uno nuevo
            // para que no se revuelvan en la pantalla del cocinero.
            if (!$pedido) {
                $pedido_id = DB::table('pedidos')->insertGetId([
                    'usuario_id' => $usuario_id,
                    'mesa_id' => $mesa_id,
                    'estado' => 'Pendiente',
                    'total' => 0,
                    'fecha_hora' => now()
                ]);
                DB::table('mesas')->where('mesa_id', $mesa_id)->update(['estado' => 'Ocupada']);
            } else {
                $pedido_id = $pedido->pedido_id;
            }

            foreach ($items as $item) {
                DB::table('detalles_pedido')->insert([
                    'pedido_id' => $pedido_id,
                    'producto_id' => $item['id'],
                    'cantidad' => 1,
                    'precio_unitario' => $item['precio'],
                    'comentarios' => $item['nota'] ?? null
                ]);
                $total_nuevo += $item['precio'];
            }

            $total_actual = $pedido ? $pedido->total : 0;
            DB::table('pedidos')->where('pedido_id', $pedido_id)->update([
                'total' => $total_actual + $total_nuevo
            ]);

            DB::commit(); 
            return redirect()->route('mesero.mesas')->with('success', '¡Comanda enviada satisfactoriamente a cocina!');

        } catch (\Exception $e) {
            DB::rollBack(); 
            return back()->withErrors(['error' => 'Error al guardar la orden. Verifica el inventario.']);
        }
    }
    // 5. Marcar la mesa como limpia y disponible (RN-05)
    public function limpiarMesa($mesa_id) {
        DB::table('mesas')->where('mesa_id', $mesa_id)->update(['estado' => 'Disponible']);
        return redirect()->route('mesero.mesas')->with('success', '¡Mesa lista para recibir nuevos clientes!');
    }
}