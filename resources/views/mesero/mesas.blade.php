<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Mesas - Mesero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #333; color: white; } 
        .header { background-color: #000; padding: 15px; border-bottom: 3px solid #f15a24; }
        .mesa-card {
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            color: black;
            font-weight: bold;
            text-decoration: none;
            display: block;
            transition: transform 0.2s;
        }
        .mesa-card:hover { transform: scale(1.05); text-decoration: none; color: black; }
        .disponible { background-color: #D4EDDA; border: 2px solid #28a745; } 
        .ocupada { background-color: #F8D7DA; border: 2px solid #dc3545; } 
        .sucia { background-color: #FFF3CD; border: 2px solid #ffc107; } 
    </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center">
    <h3 style="color: #f15a24; margin: 0;">Sr. Pizza</h3>
    <div>
        <span class="me-3">Mesero: {{ Session::get('nombre', 'Usuario') }}</span>
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger">Cerrar Sesión</a>
    </div>
</div>

<div class="container mt-4">
    <h4 class="mb-4 text-center">Seleccione una Mesa</h4>
     @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center fw-bold shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger text-center fw-bold shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif
    <div class="row g-4 justify-content-center">
        <!-- Ciclo para imprimir las mesas desde la Base de Datos -->
        @foreach($mesas as $mesa)
            <div class="col-6 col-md-3 col-lg-2">
                <!-- Hacemos que toda la tarjeta sea un enlace hacia la toma de pedidos -->
                <a href="{{ route('mesero.pedido', $mesa->mesa_id) }}" class="mesa-card 
                    {{ strtolower($mesa->estado) == 'disponible' ? 'disponible' : '' }}
                    {{ strtolower($mesa->estado) == 'ocupada' ? 'ocupada' : '' }}
                    {{ strtolower($mesa->estado) == 'sucia' ? 'sucia' : '' }}">
                    
                    <h5 class="mb-1">MESA {{ $mesa->numero_mesa }}</h5>
                    <span class="badge bg-dark">{{ strtoupper($mesa->estado) }}</span>
                    <div class="mt-2 small text-muted">Cap: {{ $mesa->capacidad }} pax</div>
                </a>
            </div>
        @endforeach
    </div>
</div>

</body>
</html>