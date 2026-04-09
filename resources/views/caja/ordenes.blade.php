<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caja - Sr. Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1e1e1e; color: white; }
        .header { background-color: #000; padding: 15px; border-bottom: 3px solid #f15a24; }
        .table-dark-custom { background-color: #2c2c2c; color: white; }
        .table-hover tbody tr:hover { background-color: #3d3d3d; cursor: pointer; }
        .panel-cuenta { background-color: #2b2b2b; border-radius: 8px; padding: 20px; }
        .btn-cobrar { background-color: #f15a24; color: white; font-weight: bold; border: none; }
        .btn-cobrar:hover { background-color: #d94e1e; color: white; }
    </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: #f15a24; margin: 0;">Sr. Pizza - Módulo de Caja</h3>
    <div>
        <span class="me-3">Cajero: {{ Session::get('nombre', 'Usuario') }}</span>
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger">Cerrar Sesión</a>
    </div>
</div>

<div class="container-fluid px-4">
    @if(session('success'))
        <div class="alert alert-success fw-bold">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <!-- COLUMNA IZQUIERDA: LISTA DE ÓRDENES PENDIENTES -->
        <div class="col-md-5">
            <div class="card bg-dark border-secondary shadow">
                <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Órdenes Pendientes</h5>
                    <a href="{{ route('caja.ordenes') }}" class="btn btn-sm btn-outline-light">↻ Actualizar</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Mesa</th>
                                <th>Estado</th>
                                <th>Total (Sin IVA)</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedidos as $ped)
                                <tr>
                                    <td class="fw-bold text-warning">Mesa {{ $ped->mesa->numero_mesa ?? 'N/A' }}</td>
                                    <td>{{ $ped->estado }}</td>
                                    <td>${{ number_format($ped->total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('caja.ordenes', ['pedido_id' => $ped->pedido_id]) }}" class="btn btn-sm btn-outline-info">Ver Cuenta</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">No hay cuentas pendientes por cobrar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: DETALLE DE CUENTA A COBRAR -->
        <div class="col-md-7">
            <div class="panel-cuenta shadow">
                <h5 class="border-bottom border-secondary pb-2 mb-3">Detalle de Cuenta</h5>
                
                @if($pedidoSeleccionado)
                    <h6 class="text-warning mb-3">MESA {{ $pedidoSeleccionado->mesa->numero_mesa ?? 'N/A' }} | Orden #{{ $pedidoSeleccionado->pedido_id }}</h6>
                    
                    <div style="height: 300px; overflow-y: auto;" class="mb-3 bg-dark p-2 rounded">
                        <table class="table table-dark table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Cant.</th>
                                    <th>Producto</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detalles as $det)
                                    <tr>
                                        <td>{{ $det->cantidad }}</td>
                                        <td>{{ $det->producto->nombre ?? 'Desconocido' }}</td>
                                        <td class="text-end">${{ number_format($det->precio_unitario, 2) }}</td>
                                        <td class="text-end">${{ number_format($det->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                   <!-- RESUMEN MATEMÁTICO CON DESCUENTO E IVA (RF-08) -->
                    <div class="row text-end mb-4 align-items-center">
                        
                        <!-- Cambiamos text-muted por text-white -->
                        <div class="col-8 text-white mb-2">Subtotal:</div>
                        <div class="col-4 mb-2">${{ isset($subtotal) ? number_format($subtotal, 2) : '0.00' }}</div>
                         <!-- CAMPO DE IVA 16% EN BLANCO -->
                        <div class="col-8 text-white mb-2">IVA (16%):</div>
                        <div class="col-4 mb-2" id="monto_iva">${{ isset($iva) ? number_format($iva, 2) : '0.00' }}</div>
                        <!-- NUEVO CAMPO DE DESCUENTO DINÁMICO -->
                        <div class="col-8 text-white d-flex justify-content-end align-items-center mb-2">
                            Descuento: 
                            <!-- Cambiamos a type="text" con inputmode="numeric" y agregamos el bloqueo de letras -->
                            <input type="text" inputmode="numeric" id="descuento_porcentaje" 
                                   class="form-control form-control-sm text-center mx-2 bg-dark text-white border-secondary" 
                                   style="width: 70px;" value="0" min="0" max="100" 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, ''); calcularTotal()"> %
                        </div>
                        <div class="col-4 text-warning mb-2" id="monto_descuento">-$0.00</div>
                        
                       
                        
                        <div class="col-8 fs-4 fw-bold mt-2 border-top border-secondary pt-2">TOTAL A PAGAR:</div>
                        <div class="col-4 fs-4 fw-bold text-success mt-2 border-top border-secondary pt-2" id="total_pagar_texto">${{ isset($totalAPagar) ? number_format($totalAPagar, 2) : '0.00' }}</div>
                    </div>

                   <!-- FORMULARIO DE COBRO SIMULADO (RF-09, RF-10) -->
                    <form action="{{ route('caja.procesar_pago', $pedidoSeleccionado->pedido_id) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="total_final" id="total_final" value="{{ $totalAPagar }}">
                        
                        <div class="d-flex justify-content-between align-items-center bg-dark p-3 rounded mb-3 border border-secondary">
                            <div>
                                <span class="me-3 text-white">Método:</span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="metodo" id="efectivo" value="efectivo" checked>
                                    <label class="form-check-label text-white" for="efectivo">Efectivo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="metodo" id="tarjeta" value="tarjeta">
                                    <label class="form-check-label text-white" for="tarjeta">Tarjeta</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-cobrar w-100 fs-5 py-3 mb-2">COBRAR Y LIBERAR MESA</button>
                    </form>


                    <!-- BOTÓN DE CANCELACIÓN (RN-01) -->
                    <form action="{{ route('caja.cancelar_pedido', $pedidoSeleccionado->pedido_id) }}" method="POST" 
                          onsubmit="return confirm('⚠️ ADVERTENCIA: ¿Estás seguro de que deseas CANCELAR esta orden por completo? Esta acción quedará registrada en la bitácora de auditoría para revisión de la Gerencia.');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 fw-bold py-2">CANCELAR ORDEN</button>
                    </form>

                @else
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted" style="min-height: 300px;">
                        <h4>Selecciona una cuenta de la lista para cobrar</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>




<!-- LÓGICA MATEMÁTICA EN TIEMPO REAL CON LÍMITE DE ROL -->
<script>
    function calcularTotal() {
        // 1. Variables desde PHP
        let subtotal = {{ isset($subtotal) ? $subtotal : 0 }};
        let limitePermitido = {{ isset($limiteDescuento) ? $limiteDescuento : 0 }}; // El límite de este usuario (ej. 20)
        
        // 2. Leemos el porcentaje tecleado
        let inputDescuentoObj = document.getElementById('descuento_porcentaje');
        let porcentaje = parseFloat(inputDescuentoObj.value) || 0;
        
        // 3. REGLA DE NEGOCIO: Validamos límites
        if (porcentaje < 0) {
            porcentaje = 0;
        }
        
        if (porcentaje > limitePermitido) {
            // Si intenta poner 50 y su límite es 20, le avisamos y lo forzamos a 20
            alert('Acción denegada: Tu rol solo tiene autorización para un máximo de ' + parseFloat(limitePermitido) + '% de descuento.');
            porcentaje = limitePermitido;
            inputDescuentoObj.value = limitePermitido; // Regresamos el número en pantalla al máximo permitido
        }
        
        // 4. Calculamos Matemática
        let montoDescuento = subtotal * (porcentaje / 100);
        let subtotalConDescuento = subtotal - montoDescuento;
        
        // 5. Calculamos el 16% de IVA 
        let iva = subtotalConDescuento * 0.16;
        let totalFinal = subtotalConDescuento + iva;
        
        // 6. Actualizamos Pantalla
        document.getElementById('monto_descuento').innerText = "-$" + montoDescuento.toFixed(2);
        document.getElementById('monto_iva').innerText = "$" + iva.toFixed(2);
        document.getElementById('total_pagar_texto').innerText = "$" + totalFinal.toFixed(2);
        
        // 7. Modificamos el valor que se irá a la BD
        let inputTotalFinal = document.getElementById('total_final');
        if (inputTotalFinal) {
            inputTotalFinal.value = totalFinal.toFixed(2);
        }
    }
</script>



<!-- AUTO-IMPRESIÓN DE TICKET DESPUÉS DE COBRAR -->
@if(session('ticket_id'))
    <script>
        // Si el controlador nos manda un ticket_id, abrimos la ventana de impresión automáticamente
        window.onload = function() {
            window.open("{{ route('caja.imprimir_ticket', session('ticket_id')) }}", "Ticket", "width=400,height=600");
        };
    </script>
@endif
</body>
</html>