<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $pedido->pedido_id }} - {{ $config->nombre_empresa ?? 'Sr. Pizza' }}</title>
    <style>
        /* Estilos específicos para Impresora Térmica de 80mm */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 10px;
            width: 300px; /* Ancho estándar de ticket térmico */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 4px 0; }
        .item-row td { vertical-align: top; }
    </style>
</head>
<body>

    <div class="text-center">
        <h2 style="margin: 5px 0;">{{ $config->nombre_empresa ?? 'SR. PIZZA' }}</h2>
        <p style="margin: 0; font-size: 12px;">{{ $config->direccion ?? 'Ticket de Consumo Interno' }}</p>
    </div>

    <div class="divider"></div>

    <div>
        <p style="margin: 2px 0;"><strong>Orden #:</strong> {{ $pedido->pedido_id }}</p>
        <p style="margin: 2px 0;"><strong>Mesa:</strong> {{ $pedido->numero_mesa }}</p>
        <p style="margin: 2px 0;"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pedido->fecha_hora)->format('d/m/Y H:i') }}</p>
        <p style="margin: 2px 0;"><strong>Cajero:</strong> {{ $pedido->cajero }}</p>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr class="divider" style="border-bottom: 1px dashed #000;">
                <th class="text-left">Cant</th>
                <th class="text-left">Producto</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal_calculado = 0; @endphp
            @foreach($detalles as $item)
                @php $subtotal_calculado += $item->subtotal; @endphp
                <tr class="item-row">
                    <td class="text-left">{{ $item->cantidad }}x</td>
                    <td class="text-left">{{ $item->nombre }}</td>
                    <td class="text-right">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td class="text-left bold">Subtotal:</td>
            <td class="text-right">${{ number_format($subtotal_calculado, 2) }}</td>
        </tr>
        <tr>
            <td class="text-left bold">IVA (16%):</td>
            <td class="text-right">${{ number_format($subtotal_calculado * 0.16, 2) }}</td>
        </tr>
        <tr>
            <td class="text-left bold" style="font-size: 18px;">TOTAL:</td>
            <td class="text-right bold" style="font-size: 18px;">${{ number_format($subtotal_calculado * 1.16, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 15px;">
        <p style="margin: 0; font-size: 12px;">{{ $config->mensaje_ticket ?? '¡Gracias por su preferencia!' }}</p>
        <p style="margin: 5px 0 0 0; font-size: 10px;">Este comprobante no tiene efectos fiscales.</p>
    </div>

    <!-- Script para abrir el cuadro de diálogo de impresión automáticamente -->
    <script>
        window.onload = function() {
            window.print();
            // Cierra la pestaña automáticamente cuando se cancela o termina de imprimir
            window.onafterprint = function() {
                window.close();
            };
        }
    </script>
</body>
</html>