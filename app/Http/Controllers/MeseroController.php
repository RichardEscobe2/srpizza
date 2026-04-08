<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\Categoria;
use App\Models\Menu;
use Illuminate\Support\Facades\DB; // <-- Herramienta clave para la base de datos
use Illuminate\Support\Facades\Session;

class MeseroController extends Controller
{
    // 1. Mostrar las mesas
    public function index() {
        $mesas = Mesa::all();
        return view('mesero.mesas', compact('mesas'));
    }

    // 2. Abrir la comanda y recuperar lo que ya se pidió
    public function tomaPedido($mesa_id) {
        $mesa = Mesa::findOrFail($mesa_id);
        $categorias = Categoria::all();

        // 3. Traemos todo el menú activo y calculamos el stock disponible (RN-03)
        // AQUI CORREGIMOS EL ERROR DE "ONLY_FULL_GROUP_BY" LISTANDO TODAS LAS COLUMNAS
        $productos = collect(DB::select("
            SELECT m.producto_id, m.categoria_id, m.nombre, m.precio, m.tamano, m.es_preparado, m.activo, m.es_recomendado,
                   IFNULL(FLOOR(MIN(i.stock_actual / r.cantidad_requerida)), 999) as stock_disponible
            FROM menu m
            LEFT JOIN recetas r ON m.producto_id = r.producto_id
            LEFT JOIN insumos i ON r.insumo_id = i.insumo_id
            WHERE m.activo = 1
            GROUP BY m.producto_id, m.categoria_id, m.nombre, m.precio, m.tamano, m.es_preparado, m.activo, m.es_recomendado
        "));

        // Verificar si la mesa ya tiene un pedido activo (Pendiente, En Horno o Listo)
        $pedidoActivo = DB::table('pedidos')
            ->where('mesa_id', $mesa_id)
            ->whereNotIn('estado', ['Pagado', 'Cancelado'])
            ->first();

        // Si ya hay un pedido, traemos los productos que ya están en cocina
        $detallesPrevios = [];
        if ($pedidoActivo) {
            $detallesPrevios = DB::table('detalles_pedido')
                ->join('menu', 'detalles_pedido.producto_id', '=', 'menu.producto_id')
                ->where('pedido_id', $pedidoActivo->pedido_id)
                ->select('detalles_pedido.*', 'menu.nombre')
                ->get();
        }

        return view('mesero.pedido', compact('mesa', 'categorias', 'productos', 'pedidoActivo', 'detallesPrevios'));
    }

    // 4. Guardar la orden en MariaDB
    public function guardarPedido(Request $request, $mesa_id) {
        $json = $request->input('json_pedido');
        $items = json_decode($json, true); // Convertimos el JSON a un arreglo PHP

        if (empty($items)) {
            return back(); 
        }

        $usuario_id = Session::get('usuario_id'); 

        // Iniciamos una transacción SQL segura
        DB::beginTransaction();
        try {
            $pedido = DB::table('pedidos')
                ->where('mesa_id', $mesa_id)
                ->whereNotIn('estado', ['Pagado', 'Cancelado'])
                ->first();

            $pedido_id = null;
            $total_nuevo = 0;

            if (!$pedido) {
                // Si es una mesa libre, CREAMOS un nuevo pedido
                $pedido_id = DB::table('pedidos')->insertGetId([
                    'usuario_id' => $usuario_id,
                    'mesa_id' => $mesa_id,
                    'estado' => 'Pendiente',
                    'total' => 0,
                    'fecha_hora' => now()
                ]);

                // ¡Cambia el estado de la Mesa a OCUPADA automáticamente!
                DB::table('mesas')->where('mesa_id', $mesa_id)->update(['estado' => 'Ocupada']);
            } else {
                // Si ya había orden, usamos el ID existente para agregar más cosas
                $pedido_id = $pedido->pedido_id;
            }

            // Insertamos cada platillo
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

            // Actualizamos el costo total de la cuenta
            $total_actual = $pedido ? $pedido->total : 0;
            DB::table('pedidos')->where('pedido_id', $pedido_id)->update([
                'total' => $total_actual + $total_nuevo
            ]);

            DB::commit(); // Guardamos los cambios permanentemente en MariaDB

            // Redirigimos a las mesas con un mensaje de éxito
            return redirect()->route('mesero.mesas')->with('success', '¡Comanda enviada satisfactoriamente a cocina!');

        } catch (\Exception $e) {
            DB::rollBack(); // Si hay error, deshacemos todo para proteger la BD
            return back()->withErrors(['error' => 'Error al guardar la orden. Verifica el inventario.']);
        }
    }
}