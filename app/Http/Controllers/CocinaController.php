<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CocinaController extends Controller
{
    // 1. Mostrar la Pantalla KDS
    public function index() {
        // CORRECCIÓN: 'pedidos' -> 'pedido', 'mesas' -> 'mesa', 'usuarios' -> 'usuario'
        $pedidos = DB::table('pedido')
            ->join('mesa', 'pedido.mesa_id', '=', 'mesa.mesa_id')
            ->join('usuario', 'pedido.usuario_id', '=', 'usuario.id_usuario')
            ->where('pedido.estado', 'Pendiente')
            ->orderBy('pedido.fecha_hora', 'asc')
            ->select('pedido.*', 'mesa.numero_mesa', 'usuario.nombre_completo as mesero')
            ->get();

        // Obtenemos los platillos y notas de esos pedidos específicos
        $detalles = [];
        if ($pedidos->count() > 0) {
            // CORRECCIÓN: 'detalles_pedido' -> 'detalle', 'menu' -> 'producto'
            $detalles = DB::table('detalle')
                ->join('producto', 'detalle.producto_id', '=', 'producto.producto_id')
                ->whereIn('pedido_id', $pedidos->pluck('pedido_id'))
                ->select('detalle.*', 'producto.nombre')
                ->get();
        }

        return view('cocina.kds', compact('pedidos', 'detalles'));
    }

    // 2. Botón para marcar la orden como "Listo"
    public function marcarListo($pedido_id) {
        // CORRECCIÓN: 'pedidos' -> 'pedido'
        DB::table('pedido')
            ->where('pedido_id', $pedido_id)
            ->update(['estado' => 'Listo']);

        return back()->with('success', '¡Orden #' . $pedido_id . ' marcada como Lista para servir!');
    }
}