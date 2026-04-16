<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caja - Sr. Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <style>
        body { display: block; overflow: hidden; height: 100vh; }
    </style>
</head>
<body>


<nav class="liquid-navbar">
    <h3 class="m-0 fw-bold text-accent">Sr. Pizza</h3>
    <div class="d-flex align-items-center gap-3">
        <span class="text-liquid-muted d-none d-md-block">
            Cajero: <span class="fw-bold text-white">{{ Session::get('nombre', 'Usuario') }}</span>
        </span>
        {{-- CORRECCIÓN: apuntaba a mesero.mesas, ahora apunta a cocina.kds --}}
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger-unified px-3">Cerrar Sesión</a>
    </div>
</nav>

<div class="pos-container">
    
    <div class="col-md-4 pos-panel">
        <div class="p-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-white">ÓRDENES PENDIENTES</h6>
            <a href="{{ route('caja.ordenes') }}" class="btn btn-xs btn-outline-accent py-0 px-2">↻</a>
        </div>
        <div class="flex-grow-1 overflow-auto custom-scroll">
            <table class="terminal-table">
                <thead>
                    <tr>
                        <th>MESA</th>
                        <th class="text-end">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $ped)
                        <tr onclick="location.href='/caja/ordenes?pedido_id={{ $ped->pedido_id }}'" 
                            class="cursor-pointer {{ (isset($pedidoSeleccionado) && $pedidoSeleccionado->pedido_id == $ped->pedido_id) ? 'terminal-row-active' : '' }}"
                            style="cursor: pointer;">
                            <td class="fw-bold">MESA {{ $ped->mesa->numero_mesa ?? 'N/A' }}</td>
                            <td class="text-end text-accent fw-bold">${{ number_format($ped->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center py-5 text-liquid-muted">No hay cuentas pendientes</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-8 pos-panel">
        @if($pedidoSeleccionado)
            <div class="p-4 d-flex flex-column h-100">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="m-0 fw-bold text-white">MESA {{ $pedidoSeleccionado->mesa->numero_mesa ?? 'N/A' }}</h2>
                        <span class="text-accent small fw-bold">ID DE ORDEN: #{{ $pedidoSeleccionado->pedido_id }}</span>
                    </div>
                    <div class="text-end text-liquid-muted">
                        {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="flex-grow-1 overflow-auto custom-scroll mb-4" style="background: rgba(0,0,0,0.2); border-radius: 8px;">
                    <table class="terminal-table">
                        <thead>
                            <tr>
                                <th>CANT</th>
                                <th>PRODUCTO</th>
                                <th class="text-end">P. UNIT</th>
                                <th class="text-end">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Agrupamos los detalles por ID de producto para hacer el ticket limpio
                                $agrupadosCaja = $detalles->groupBy('producto_id');
                            @endphp

                            @foreach($agrupadosCaja as $grupo)
                                @php
                                    $primerItem = $grupo->first();
                                    // Sumamos las cantidades (si hay dos de 1x, ahora será 2x)
                                    $cantidadTotal = (int) $grupo->sum('cantidad');
                                    
                                    // Obtenemos el tamaño y armamos el nombre final (Ej: Pizza Peperoni (Familiar))
                                    $tamano = isset($primerItem->producto) ? $primerItem->producto->tamano : '';
                                    $nombreBase = $primerItem->producto->nombre ?? 'Desconocido';
                                    $nombreFinal = $tamano ? $nombreBase . ' (' . $tamano . ')' : $nombreBase;
                                    
                                    // Cálculos financieros por grupo
                                    $precioUnitario = $primerItem->precio_unitario;
                                    $totalFila = $cantidadTotal * $precioUnitario;
                                @endphp
                                <tr>
                                    <td class="text-accent fw-bold">{{ $cantidadTotal }}x</td>
                                    <td class="text-white">{{ $nombreFinal }}</td>
                                    <td class="text-end text-liquid-muted">${{ number_format($precioUnitario, 2) }}</td>
                                    <td class="text-end fw-bold text-white">${{ number_format($totalFila, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pos-checkout">
                    <div class="row align-items-center">
                        <div class="col-md-6 border-end border-secondary border-opacity-25">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-liquid-muted">Subtotal:</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-liquid-muted">IVA (16%):</span>
                                <span id="monto_iva">${{ number_format($iva, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-liquid-muted">Desc (%):</span>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" id="descuento_porcentaje" class="form-control liquid-input text-center py-0" 
                                           style="width: 60px; height: 26px; font-size: 0.8rem; border-color: var(--accent-orange) !important;" 
                                           value="0" oninput="calcularTotal()">
                                    <span class="text-danger fw-bold" id="monto_descuento">-$0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-4 text-center">
                            <span class="text-accent fw-bold small">TOTAL A PAGAR</span>
                            <div class="pos-total-text" id="total_pagar_texto">${{ number_format($totalAPagar, 2) }}</div>
                            
                            <form action="{{ route('caja.procesar_pago', $pedidoSeleccionado->pedido_id) }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="total_final" id="total_final" value="{{ $totalAPagar }}">
                                <div class="d-flex justify-content-center gap-3 mb-3">
                                    <div class="form-check small">
                                        <input class="form-check-input" type="radio" name="metodo" id="efectivo" value="efectivo" checked>
                                        <label class="form-check-label text-white" for="efectivo">EFECTIVO</label>
                                    </div>
                                    <div class="form-check small">
                                        <input class="form-check-input" type="radio" name="metodo" id="tarjeta" value="tarjeta">
                                        <label class="form-check-label text-white" for="tarjeta">TARJETA</label>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sr-pizza w-100 fw-bold">COBRAR</button>
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmarCancelacion()">✕</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <form id="form-cancelar" action="{{ route('caja.cancelar_pedido', $pedidoSeleccionado->pedido_id) }}" method="POST" class="d-none">@csrf</form>
        @else
            <div class="h-100 d-flex flex-column align-items-center justify-content-center opacity-25">
                <h1 style="font-size: 5rem;">💳</h1>
                <h4 class="fw-bold">TERMINAL DE PAGO</h4>
                <p>Seleccione una mesa para iniciar el proceso de cobro.</p>
            </div>
        @endif
    </div>
</div>

<script>
    function calcularTotal() {
        let subtotal = {{ $subtotal ?? 0 }};
        let limite = {{ $limiteDescuento ?? 0 }};
        let inp = document.getElementById('descuento_porcentaje');
        let porc = parseFloat(inp.value) || 0;
        
        if (porc > limite) {
            alert('Límite de descuento excedido: ' + limite + '%');
            porc = limite;
            inp.value = limite;
        }
        
        let mDesc = subtotal * (porc / 100);
        let neto = subtotal - mDesc;
        let iva = neto * 0.16;
        let total = neto + iva;
        
        document.getElementById('monto_descuento').innerText = "-$" + mDesc.toFixed(2);
        document.getElementById('monto_iva').innerText = "$" + iva.toFixed(2);
        document.getElementById('total_pagar_texto').innerText = "$" + total.toFixed(2);
        document.getElementById('total_final').value = total.toFixed(2);
    }

    function confirmarCancelacion() {
        if(confirm('¿Seguro que deseas cancelar esta orden?')) document.getElementById('form-cancelar').submit();
    }
</script>

@if(session('ticket_id'))
    <script>
        window.onload = function() {
            window.open("{{ route('caja.imprimir_ticket', session('ticket_id')) }}", "Ticket", "width=400,height=600");
        };
    </script>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>