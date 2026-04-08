<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDS - Cocina Sr. Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1e1e1e; color: white; }
        .header { background-color: #000; padding: 15px; border-bottom: 3px solid #f15a24; }
        .ticket-card { background-color: #fdfdfd; color: black; border-radius: 8px; border-top: 8px solid #ffc107; }
        .nota-especial { color: #dc3545; font-weight: bold; font-size: 0.85rem; }
    </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center mb-4 shadow">
    <h3 style="color: #f15a24; margin: 0;">Sr. Pizza - KDS Cocina</h3>
    <div>
        <span class="me-3">Cocinero: {{ Session::get('nombre', 'Usuario') }}</span>
        <!-- Botón para recargar la pantalla y ver nuevas órdenes -->
        <a href="{{ route('cocina.kds') }}" class="btn btn-sm btn-outline-warning me-2">Actualizar ↻</a>
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger">Cerrar Sesión</a>
    </div>
</div>

<div class="container-fluid px-4">
    @if(session('success'))
        <div class="alert alert-success text-center fw-bold">{{ session('success') }}</div>
    @endif

    <div class="row g-3 flex-nowrap overflow-auto" style="padding-bottom: 20px;">
        @forelse($pedidos as $pedido)
            <!-- TARJETA DE CADA ORDEN (Estilo Kanban) -->
            <div class="col-12 col-md-4 col-lg-3">
                <div class="card ticket-card h-100 shadow">
                    <div class="card-header bg-transparent border-bottom-0 pt-3 pb-0 d-flex justify-content-between">
                        <h4 class="mb-0 fw-bold">MESA {{ $pedido->numero_mesa }}</h4>
                        <span class="text-muted small">Ord #{{ $pedido->pedido_id }}</span>
                    </div>
                    <div class="card-body pt-1">
                        <p class="small text-muted mb-3 border-bottom pb-2">Mesero: {{ $pedido->mesero }}<br>Hora: {{ \Carbon\Carbon::parse($pedido->fecha_hora)->format('H:i') }}</p>
                        
                        <ul class="list-unstyled mb-4">
                            <!-- Filtramos solo los detalles de este pedido -->
                            @foreach($detalles->where('pedido_id', $pedido->pedido_id) as $item)
                                <li class="mb-2 border-bottom border-light pb-1">
                                    <div class="fw-bold fs-6">
                                        <span class="badge bg-dark me-1">{{ $item->cantidad }}x</span> {{ $item->nombre }}
                                    </div>
                                    @if($item->comentarios)
                                        <div class="nota-especial mt-1 ps-4">>>> {{ strtoupper($item->comentarios) }}</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pb-3">
                        <form action="{{ route('cocina.marcar_listo', $pedido->pedido_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-dark w-100 fw-bold fs-5 py-2">TERMINAR ORDEN</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center mt-5">
                <h4 class="text-muted">No hay pedidos pendientes. ¡Buen trabajo!</h4>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>