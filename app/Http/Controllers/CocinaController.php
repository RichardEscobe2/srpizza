<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CocinaController extends Controller
{
    // 1. Mostrar la Pantalla KDS
    public function index() {
        // Obtenemos solo los pedidos que están "Pendientes", ordenados por el más antiguo primero (RF-11)
        $pedidos = DB::table('pedidos')
            ->join('mesas', 'pedidos.mesa_id', '=', 'mesas.mesa_id')
            ->join('usuarios', 'pedidos.usuario_id', '=', 'usuarios.id_usuario')
            ->where('pedidos.estado', 'Pendiente')
            ->orderBy('pedidos.fecha_hora', 'asc')
            ->select('pedidos.*', 'mesas.numero_mesa', 'usuarios.nombre_completo as mesero')
            ->get();

        // Obtenemos los platillos y notas de esos pedidos específicos
        $detalles = [];
        if ($pedidos->count() > 0) {
            $detalles = DB::table('detalles_pedido')
                ->join('menu', 'detalles_pedido.producto_id', '=', 'menu.producto_id')
                ->whereIn('pedido_id', $pedidos->pluck('pedido_id'))
                ->select('detalles_pedido.*', 'menu.nombre')
                ->get();
        }

        return view('cocina.kds', compact('pedidos', 'detalles'));
    }

    // 2. Botón para marcar la orden como "Listo"
    public function marcarListo($pedido_id) {
        // Actualizamos el estado del pedido, lo cual lo sacará de la pantalla de la cocina
        DB::table('pedidos')
            ->where('pedido_id', $pedido_id)
            ->update(['estado' => 'Listo']);

        return back()->with('success', '¡Orden #' . $pedido_id . ' marcada como Lista para servir!');
    }
}
