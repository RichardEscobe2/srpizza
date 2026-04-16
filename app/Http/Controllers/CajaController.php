<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Pedido;
// NOTA: Asegúrate de que tu modelo se llame Detalle (en singular) para que coincida con la tabla 'detalle'
use App\Models\Detalle; 

class CajaController extends Controller
{
    // 1. Mostrar las cuentas abiertas y el detalle de la seleccionada (RF-07)
    public function index(Request $request) {
        // Traemos todos los pedidos que no han sido pagados ni cancelados
        $pedidos = Pedido::with('mesa')->whereNotIn('estado', ['Pagado', 'Cancelado'])->get();

        $pedidoSeleccionado = null;
        $detalles = [];
        $subtotal = 0;
        $iva = 0;
        $totalAPagar = 0;
        
        // CORRECCIÓN: 'usuarios' -> 'usuario'
        $usuario_id = Session::get('usuario_id');
        $limiteDescuento = DB::table('usuario')->where('id_usuario', $usuario_id)->value('limite_descuento') ?? 0;

        // Si el cajero hizo clic en una orden, calculamos sus totales
        if ($request->has('pedido_id')) {
            $pedidoSeleccionado = Pedido::with('mesa')->find($request->pedido_id);
            
            // CORRECCIÓN: Usamos el modelo Detalle (que debe apuntar a la tabla 'detalle')
            $detalles = Detalle::with('producto')->where('pedido_id', $request->pedido_id)->get();

            // Calculamos el subtotal (asegúrate de que en el modelo Detalle o en la BD exista el cálculo o columna)
            $subtotal = $detalles->sum(function($d) {
                return $d->cantidad * $d->precio_unitario;
            });
            
            $iva = $subtotal * 0.16;
            $totalAPagar = $subtotal + $iva;
        }

        // Enviamos la variable $limiteDescuento a la pantalla
        return view('caja.ordenes', compact('pedidos', 'pedidoSeleccionado', 'detalles', 'subtotal', 'iva', 'totalAPagar', 'limiteDescuento'));
    }

    // 2. Procesar el pago y liberar la mesa (RF-09, RF-10, RN-04)
    public function procesarPago(Request $request, $pedido_id) {
        DB::beginTransaction();
        try {
             $pedido = Pedido::find($pedido_id);

            if (!$pedido) {
                return back()->withErrors(['error' => 'No se encontró la orden.']);
            }

            // 1. CORRECCIÓN: 'pedidos' -> 'pedido'
            DB::table('pedido')->where('pedido_id', $pedido_id)->update([
                'estado' => 'Pagado',
                'total' => $request->input('total_final')
            ]);

            // 2. CORRECCIÓN: 'mesas' -> 'mesa'
            DB::table('mesa')->where('mesa_id', $pedido->mesa_id)->update([
                'estado' => 'Sucia'
            ]);
            DB::commit();

            return redirect()->route('caja.ordenes')
                ->with('success', '¡Cuenta cobrada exitosamente! La Mesa ' . $pedido->mesa_id . ' ahora está pendiente de limpieza.')
                ->with('ticket_id', $pedido_id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al procesar el pago.']);
        }
    }


    // 3. Cancelar orden (Borrado lógico RN-01)
    public function cancelarPedido($pedido_id) {
        DB::beginTransaction();
        try {
             $pedido = Pedido::find($pedido_id);

            if (!$pedido) {
                return back()->withErrors(['error' => 'No se encontró la orden.']);
            }

            // 1. CORRECCIÓN: 'pedidos' -> 'pedido'
            DB::table('pedido')->where('pedido_id', $pedido_id)->update([
                'estado' => 'Cancelado'
            ]);

            // 2. CORRECCIÓN: 'mesas' -> 'mesa'
            DB::table('mesa')->where('mesa_id', $pedido->mesa_id)->update([
                'estado' => 'Disponible'
            ]);

            DB::commit();

            return redirect()->route('caja.ordenes')->with('success', '¡Orden #' . $pedido_id . ' cancelada exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al intentar cancelar la orden.']);
        }
    }


    // 4. Generar e imprimir Ticket de Venta (Could Have)
    public function imprimirTicket($pedido_id) {
        $pedido = Pedido::with(['mesa', 'usuario'])->where('pedido_id', $pedido_id)->first();

        if (!$pedido) {
            return back()->withErrors(['error' => 'Orden no encontrada.']);
        }

        // CORRECCIÓN: Usamos el modelo Detalle
        $detalles = Detalle::with('producto')->where('pedido_id', $pedido_id)->get();
         
        // CORRECCIÓN: 'configuracion_sistema' -> 'config'
        $config = DB::table('config')->first();

        return view('caja.ticket', compact('pedido', 'detalles', 'config'));
    }
}