<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\Categoria;
use App\Models\Menu;
use App\Models\Pedido;
use App\Models\DetallePedido;
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
        $pedidosActivos = Pedido::where('mesa_id', $mesa_id)
            ->whereNotIn('estado', ['Pagado', 'Cancelado'])
            ->get();

        $detallesCocina = collect();
        $detallesListo = collect();

        if ($pedidosActivos->isNotEmpty()) {
            $todosDetalles = DetallePedido::with(['producto', 'pedido'])
                ->whereIn('pedido_id', $pedidosActivos->pluck('pedido_id'))
                ->get();

            // Aquí está la magia: separamos los platillos según el estado del pedido
            $detallesCocina = $todosDetalles->filter(fn($det) => $det->pedido && $det->pedido->estado === 'Pendiente');
            $detallesListo = $todosDetalles->filter(fn($det) => $det->pedido && $det->pedido->estado === 'Listo');
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
            $pedido = Pedido::where('mesa_id', $mesa_id)->where('estado', 'Pendiente')->first();

            $pedido_id = null;
            $total_nuevo = 0;

            // Si no hay pedido pendiente (porque los anteriores ya están "Listos"), CREAMOS uno nuevo
            // para que no se revuelvan en la pantalla del cocinero.
            if (!$pedido) {
                $nuevoPedido = new Pedido();
                $nuevoPedido->usuario_id = $usuario_id;
                $nuevoPedido->mesa_id = $mesa_id;
                $nuevoPedido->estado = 'Pendiente';
                $nuevoPedido->total = 0;
                $nuevoPedido->fecha_hora = now();
                $nuevoPedido->save();
                $pedido_id = $nuevoPedido->pedido_id;

                Mesa::where('mesa_id', $mesa_id)->update(['estado' => 'Ocupada']);
            } else {
                $pedido_id = $pedido->pedido_id;
            }

            foreach ($items as $item) {
                  // Obtenemos el precio real directo desde la base de datos para evitar alteraciones en el frontend
                // Si el producto no existe o el ID es manipulado, se asigna 0 por seguridad para que la app no truene
                $precio_real = Menu::where('producto_id', $item['id'])->value('precio') ?? 0;

                $detalle = new DetallePedido();
                $detalle->pedido_id = $pedido_id;
                $detalle->producto_id = $item['id'];
                $detalle->cantidad = 1;
                $detalle->precio_unitario = $precio_real;
                $detalle->comentarios = $item['nota'] ?? null;
                $detalle->save();

                $total_nuevo += $precio_real;
            }

            $total_actual = $pedido ? $pedido->total : 0;
            Pedido::where('pedido_id', $pedido_id)->update([
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
        Mesa::where('mesa_id', $mesa_id)->update(['estado' => 'Disponible']);
        return redirect()->route('mesero.mesas')->with('success', '¡Mesa lista para recibir nuevos clientes!');
    }

    // 6. Consultar alertas en tiempo real para el mesero (Could Have)
    public function checarAlertas() {
        $usuario_id = Session::get('usuario_id');
        
        // Buscamos pedidos que estén en estado 'Listo' y pertenezcan a este mesero
        $pedidosListos = Pedido::with('mesa')
            ->where('usuario_id', $usuario_id)
            ->where('estado', 'Listo')
            ->get()
            ->map(function($pedido) {
                return [
                    'pedido_id' => $pedido->pedido_id,
                    'numero_mesa' => $pedido->mesa->numero_mesa ?? 'N/A'
                ];
            });

        return response()->json($pedidosListos);
    }
}