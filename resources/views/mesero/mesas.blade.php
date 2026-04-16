<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Mesas - Sr. Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>

<nav class="liquid-navbar">
    <h3 class="m-0 fw-bold text-accent">Sr. Pizza</h3>
    <div class="d-flex align-items-center gap-3">
        <span class="text-liquid-muted d-none d-md-block">
            Mesero: <span class="fw-bold text-white">{{ Session::get('nombre', 'Usuario') }}</span>
        </span>
        <a href="{{ route('mesero.mesas') }}" class="btn btn-sm btn-outline-accent">Actualizar ↻</a>
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger-unified px-3">Cerrar Sesión</a>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-center align-items-center mb-5">
        <h4 class="m-0 text-center fw-bold" style="letter-spacing: 1px;">SELECCIONE UNA MESA</h4>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success text-center fw-bold shadow-sm liquid-card mx-auto mb-4" style="border-color: #2ecc71; color: #2ecc71; padding: 15px;" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger text-center fw-bold shadow-sm liquid-card mx-auto mb-4" style="border-color: var(--unified-red); color: var(--unified-red); padding: 15px;">
            {{ $errors->first() }}
        </div>
    @endif
    
    <div class="row g-4 justify-content-center">
         @foreach($mesas as $mesa)
                <div class="col-6 col-md-4 col-lg-3">
                    
                    <div class="mesa-liquid-card 
                        @if($mesa->estado == 'Disponible') mesa-disponible 
                        @elseif($mesa->estado == 'Ocupada') mesa-ocupada 
                        @else mesa-sucia @endif">
                        
                        <h3 class="fw-bold mb-3 text-white">MESA {{ $mesa->numero_mesa }}</h3>
                        
                        @if($mesa->estado == 'Disponible')
                            <div class="mb-4">
                                <span class="liquid-badge badge-disponible">DISPONIBLE</span>
                            </div>
                            <a href="{{ route('mesero.pedido', $mesa->mesa_id) }}" class="btn btn-mesa btn-mesa-disponible w-100 fw-bold">Abrir Mesa</a>
                            
                        @elseif($mesa->estado == 'Ocupada')
                            <div class="mb-4">
                                <span class="liquid-badge badge-ocupada">OCUPADA</span>
                            </div>
                            <a href="{{ route('mesero.pedido', $mesa->mesa_id) }}" class="btn btn-mesa btn-mesa-ocupada w-100 fw-bold">Ver Comanda</a>
                            
                        @elseif($mesa->estado == 'Sucia')
                            <div class="mb-4">
                                <span class="liquid-badge badge-sucia">SUCIA</span>
                            </div>
                            <form action="{{ route('mesero.limpiar_mesa', $mesa->mesa_id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-mesa btn-mesa-sucia w-100 fw-bold">✔ Limpiar</button>
                            </form>
                        @endif
                        
                    </div>

                </div>
            @endforeach
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1055;">
    <div id="alertaCocina" class="toast align-items-center border-0 liquid-card" style="padding: 5px; border-left: 4px solid #2ecc71;" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000">
        <div class="d-flex">
            <div class="toast-body fs-6 fw-bold" id="textoAlerta" style="color: #2ecc71;">
                🔔 ¡Orden Lista para la Mesa X!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let ordenesAlertadas = JSON.parse(sessionStorage.getItem('alertas_mostradas')) || []; 
    const sonidoCampana = new Audio('https://actions.google.com/sounds/v1/alarms/ding_dong.ogg');

    function buscarAlertas() {
        fetch("{{ route('mesero.alertas') }}")
            .then(response => response.json())
            .then(data => {
                data.forEach(pedido => {
                    if (!ordenesAlertadas.includes(pedido.pedido_id)) {
                        
                        ordenesAlertadas.push(pedido.pedido_id);
                        sessionStorage.setItem('alertas_mostradas', JSON.stringify(ordenesAlertadas));
                        
                        document.getElementById('textoAlerta').innerText = '🔔 ¡La orden de la MESA ' + pedido.numero_mesa + ' está Lista para servir!';
                        const toast = new bootstrap.Toast(document.getElementById('alertaCocina'));
                        toast.show();
                        
                        sonidoCampana.play().catch(e => console.log("El navegador bloqueó el autoplay del sonido."));
                    }
                });
            })
            .catch(error => console.error('Error buscando alertas:', error));
    }

    setInterval(buscarAlertas, 5000);
</script>

</body>
</html>