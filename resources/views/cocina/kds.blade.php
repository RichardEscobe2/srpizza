<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDS - Cocina Sr. Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    {{-- Auto-refresh cada 20 segundos para ver nuevos pedidos sin recargar manualmente --}}
    <meta http-equiv="refresh" content="20">
    <style>
        body { display: block; overflow: hidden; height: 100vh; }
    </style>
</head>
<body>

<nav class="liquid-navbar">
    <h3 class="m-0 fw-bold text-accent">Sr. Pizza</h3>
    <div class="d-flex align-items-center gap-3">
        <span class="text-liquid-muted d-none d-md-block">
            Chef: <span class="fw-bold text-white">{{ Session::get('nombre', 'Usuario') }}</span>
        </span>
        {{-- CORRECCIÓN: apuntaba a mesero.mesas, ahora apunta a cocina.kds --}}
        <a href="{{ route('cocina.kds') }}" class="btn btn-sm btn-outline-accent">Actualizar ↻</a>
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger-unified px-3">Cerrar Sesión</a>
    </div>
</nav>

<div class="container-fluid mt-4">
    @if(session('success'))
        <div class="alert alert-success text-center fw-bold liquid-card mx-auto mb-4 py-2"
             style="max-width: 500px; border-color: #2ecc71; color: #2ecc71; background: rgba(46, 204, 113, 0.1);">
            {{ session('success') }}
        </div>
    @endif

    <div class="kds-container">
        @forelse($pedidos as $pedido)
            <div class="kds-liquid-card">
                <div class="kds-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="m-0 fw-bold text-white">MESA {{ $pedido->numero_mesa }}</h4>
                        <small class="text-liquid-muted">Orden #{{ $pedido->pedido_id }}</small>
                    </div>
                    <div class="text-end">
                        @php
                            // Forzamos a que sea un número entero (int) y absoluto (abs) para evitar decimales o negativos
                            $minutosEspera = (int) abs(\Carbon\Carbon::parse($pedido->fecha_hora)->diffInMinutes(now()));
                            
                            // Lógica de semáforo de tiempo (Naranja normal, Amarillo > 10m, Rojo > 15m)
                            $colorTiempo = 'text-accent'; 
                            if($minutosEspera >= 15) {
                                $colorTiempo = 'text-danger'; 
                            } elseif($minutosEspera >= 10) {
                                $colorTiempo = 'text-warning'; 
                            }
                        @endphp
                        
                        <div class="{{ $colorTiempo }} fw-bold fs-5">
                            ⏱ {{ $minutosEspera }} min
                        </div>
                        <small class="text-liquid-muted">{{ $pedido->mesero }}</small>
                    </div>
                </div>

               <div class="kds-card-body">
                    <ul class="list-unstyled m-0">
                        @php
                            // Agrupamos los detalles para que no salgan repetidos
                            $detallesOrden = $detalles->where('pedido_id', $pedido->pedido_id);
                            
                            $agrupadosCocina = $detallesOrden->groupBy(function($item) {
                                return $item->producto_id . '|' . $item->comentarios;
                            });
                        @endphp

                        @foreach($agrupadosCocina as $grupo)
                            @php 
                                $primerItem = $grupo->first(); 
                                // Quitamos los decimales y sumamos
                                $cantidadTotal = (int) $grupo->sum('cantidad'); 
                                
                                // Sacamos el tamaño usando la relación que ya tienes, sin hacer consultas raras
                                $tamano = isset($primerItem->producto) ? $primerItem->producto->tamano : '';
                                $nombreFinal = $tamano ? $primerItem->nombre . ' (' . $tamano . ')' : $primerItem->nombre;
                            @endphp
                            
                            <li class="kds-item">
                                <div class="d-flex align-items-center">
                                    <span class="kds-quantity">{{ $cantidadTotal }}x</span>
                                    <span class="text-white fw-bold fs-5">{{ $nombreFinal }}</span>
                                </div>
                                @if($primerItem->comentarios)
                                    <div class="kds-note">
                                        ⚠ {{ strtoupper($primerItem->comentarios) }}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="p-3 border-top border-secondary">
                    <form action="{{ route('cocina.marcar_listo', $pedido->pedido_id) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sr-pizza w-100 fw-bold fs-5">
                            ✔ TERMINAR ORDEN
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="w-100 text-center mt-5">
                <div class="liquid-card d-inline-block p-5">
                    <h4 class="text-liquid-muted">No hay pedidos pendientes por ahora.</h4>
                    <p class="text-accent small">¡Excelente trabajo!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>