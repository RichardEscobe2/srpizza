<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toma de Pedido - Mesa {{ $mesa->numero_mesa }}</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 fw-bold text-warning">MESA {{ $mesa->numero_mesa }}</span>
        <div class="d-flex align-items-center text-white">
            <span class="me-3 small">Mesero: {{ Session::get('nombre') }}</span>
            <a href="{{ route('mesero.mesas') }}" class="btn btn-sm btn-outline-light">Volver a Mesas</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- COLUMNA IZQUIERDA: MENÚ POR CATEGORÍAS (RF-05) -->
        <div class="col-md-8 col-lg-9 mb-4">
            <h5 class="text-muted mb-3">Selecciona los productos</h5>
            
            <!-- Pestañas de las categorías -->
            <ul class="nav nav-pills mb-3 border-bottom pb-2" id="pills-tab" role="tablist">
                @foreach($categorias as $index => $cat)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold {{ $index == 0 ? 'active' : '' }}" 
                                id="tab-cat-{{ $cat->categoria_id }}" 
                                data-bs-toggle="pill" 
                                data-bs-target="#pane-cat-{{ $cat->categoria_id }}" 
                                type="button" role="tab">
                            {{ $cat->nombre }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content" id="pills-tabContent">
                @foreach($categorias as $index => $cat)
                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" 
                         id="pane-cat-{{ $cat->categoria_id }}" role="tabpanel">
                        
                        <div class="row g-2">
                            <!-- Bucle para mostrar las pizzas y productos de esta categoría -->
                            @foreach($productos->where('categoria_id', $cat->categoria_id) as $prod)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-body text-center p-2 d-flex flex-column">
                                            <h6 class="card-title text-truncate mb-1" title="{{ $prod->nombre }}">{{ $prod->nombre }}</h6>
                                            <small class="text-muted mb-2">{{ $prod->tamano }}</small>
                                            <div class="mt-auto">
                                                <p class="fw-bold text-success mb-2">${{ number_format($prod->precio, 2) }}</p>
                                                  <!-- Lógica de Bloqueo por Desabasto (RN-03) -->
                                                @if($prod->stock_disponible >= 1)
                                                    <button class="btn btn-sm btn-dark w-100" 
                                                            onclick="agregarPlatillo({{ $prod->producto_id }}, '{{ $prod->nombre }}', {{ $prod->precio }})">
                                                        + Agregar
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-secondary w-100 disabled" disabled>
                                                        AGOTADO
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

               <!-- COLUMNA DERECHA: RESUMEN DE LA COMANDA -->
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm sticky-top" style="top: 15px;">
                <div class="card-header bg-warning text-dark text-center fw-bold">
                    RESUMEN DE COMANDA
                </div>
                <div class="card-body p-0" style="max-height: 50vh; overflow-y: auto;">
                    
                    <!-- BLOQUE NUEVO: Muestra lo que ya se pidió antes (Historial de la mesa) -->
                    @if(isset($detallesPrevios) && count($detallesPrevios) > 0)
                        <div class="bg-light p-2 border-bottom">
                            <h6 class="text-primary fw-bold text-center mb-1" style="font-size: 0.85rem;">YA EN COCINA</h6>
                            <ul class="list-group list-group-flush mb-2">
                            @foreach($detallesPrevios as $detalle)
                                <li class="list-group-item d-flex justify-content-between align-items-start px-2 bg-transparent py-1">
                                    <div class="ms-1 me-auto" style="font-size: 0.8rem;">
                                        <div class="text-dark">{{ $detalle->nombre }}</div>
                                        @if($detalle->comentarios)
                                            <span class="badge bg-secondary mt-1 text-wrap text-start">Nota: {{ $detalle->comentarios }}</span>
                                        @endif
                                    </div>
                                    <span class="text-muted">${{ number_format($detalle->subtotal, 2) }}</span>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- FIN BLOQUE NUEVO -->

                    <h6 class="text-success fw-bold text-center mt-2 mb-1" style="font-size: 0.85rem;">NUEVOS PRODUCTOS</h6>
                    <ul class="list-group list-group-flush" id="lista-comanda">
                        <li class="list-group-item text-center text-muted py-4">Agrega productos...</li>
                    </ul>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3 text-danger">
                        <span>TOTAL NUEVO:</span>
                        <span id="total-comanda">$0.00</span>
                    </div>
                    
                    <!-- Formulario conectado a Laravel -->
                    <form id="form-pedido" action="{{ route('mesero.guardar_pedido', $mesa->mesa_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="json_pedido" id="json_pedido">
                        <button type="button" class="btn btn-success w-100 fw-bold py-2" onclick="prepararEnvio()">
                            ENVIAR A COCINA
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lógica JavaScript para hacer la pantalla rápida sin recargar la página -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let comanda = [];
    let totalCuenta = 0;

    // Función para agregar productos
    function agregarPlatillo(id, nombre, precio) {
        // RF-06: Solicitamos una nota personalizada al instante
        let nota = prompt(`Instrucciones especiales para: ${nombre}\n(Dejar en blanco si no hay nota)`);
        
        // Si el usuario da "Cancelar" en el cuadro de texto, detenemos la inserción
        if (nota === null) return; 

        comanda.push({ id: id, nombre: nombre, precio: precio, nota: nota });
        totalCuenta += precio;
        
        dibujarComanda();
    }

    // Función para dibujar la lista en la pantalla
    function dibujarComanda() {
        let listaHTML = document.getElementById('lista-comanda');
        listaHTML.innerHTML = '';

        if (comanda.length === 0) {
            listaHTML.innerHTML = '<li class="list-group-item text-center text-muted py-4">Orden vacía</li>';
        } else {
            comanda.forEach((item, index) => {
                let htmlNota = item.nota ? `<br><span class="badge bg-secondary mt-1 text-wrap text-start">Nota: ${item.nota}</span>` : '';
                listaHTML.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-start px-2">
                        <div class="ms-1 me-auto" style="font-size: 0.9rem;">
                            <div class="fw-bold">${item.nombre}</div>
                            <span class="text-success">$${item.precio.toFixed(2)}</span>
                            ${htmlNota}
                        </div>
                        <button class="btn btn-sm text-danger fw-bold" onclick="quitarPlatillo(${index})">X</button>
                    </li>
                `;
            });
        }
        document.getElementById('total-comanda').innerText = '$' + totalCuenta.toFixed(2);
    }

    // Función para eliminar un producto de la lista
    function quitarPlatillo(index) {
        totalCuenta -= comanda[index].precio;
        comanda.splice(index, 1);
        dibujarComanda();
    }

    // Función final antes de enviar a PHP
    function prepararEnvio() {
        if (comanda.length === 0) {
            alert("No puedes enviar una orden vacía a cocina.");
            return;
        }
        
        alert("¡Comanda armada perfectamente en memoria!\nTotal de artículos: " + comanda.length + "\nPronto conectaremos este botón al Back-end.");
    }
      // Función final que empaqueta todo y lo manda a Laravel
    function prepararEnvio() {
        if (comanda.length === 0) {
            alert("No has agregado ningún producto nuevo.");
            return;
        }
        
        // Empaquetamos la comanda temporal en el input oculto
        document.getElementById('json_pedido').value = JSON.stringify(comanda);
        // ¡Enviamos al Back-end!
        document.getElementById('form-pedido').submit();
    }

</script>
</body>
</html>