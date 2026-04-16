<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sr. Pizza - Pedido Mesa {{ $mesa->numero_mesa }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <style>
        body { display: block; overflow-x: hidden; }
        .comanda-lateral {
            background: var(--bg-darker);
            border-left: 1px solid var(--glass-border);
            height: calc(100vh - 70px);
            position: sticky;
            top: 70px;
            display: flex;
            flex-direction: column;
        }
        .cursor-pointer { cursor: pointer; }
    </style>
</head>
<body>
<nav class="liquid-navbar">
    <h3 class="m-0 fw-bold text-accent">Sr. Pizza</h3>
    <div class="d-flex align-items-center gap-3">
        <span class="text-liquid-muted d-none d-md-block">
            Mesero: <span class="fw-bold text-white">{{ Session::get('nombre', 'Usuario') }}</span>
        </span>
        <a href="{{ route('mesero.mesas') }}" class="btn btn-sm btn-outline-accent">Mapa de Mesas</a>
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger-unified px-3">Cerrar Sesión</a>
    </div>
</nav>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-8 col-lg-9 p-4">
            
            @if($errors->any())
                <div class="alert alert-danger border-0 mb-4 liquid-card py-2" style="background: rgba(231, 76, 60, 0.2); color: #ff6b6b;">
                    {{ $errors->first() }}
                </div>
            @endif

            <ul class="nav nav-pills mb-4" id="pills-tab">
                @foreach($categorias as $index => $cat)
                    <li class="nav-item">
                        <button class="nav-link fw-bold {{ $index == 0 ? 'active' : '' }}" 
                                data-bs-toggle="pill" 
                                data-bs-target="#pane-cat-{{ $cat->categoria_id }}" 
                                type="button">
                            {{ strtoupper($cat->nombre) }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($categorias as $index => $cat)
                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="pane-cat-{{ $cat->categoria_id }}">
                        <div class="row g-3">
                            @php
                                /* AGRUPAMOS LOS PRODUCTOS POR NOMBRE DIRECTAMENTE EN BLADE */
                                $productosAgrupados = $productos->where('categoria_id', $cat->categoria_id)->groupBy('nombre');
                            @endphp

                            @foreach($productosAgrupados as $nombreProducto => $variaciones)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="product-liquid-card p-3 h-100 text-center d-flex flex-column justify-content-between cursor-pointer"
                                         data-nombre="{{ $nombreProducto }}" 
                                         data-variaciones="{{ $variaciones->toJson() }}"
                                         onclick="abrirModalTamanos(this)">
                                        <div>
                                            <h6 class="text-white mb-1">{{ $nombreProducto }}</h6>
                                            <small class="text-liquid-muted">{{ $variaciones->count() }} tamaños</small>
                                        </div>
                                        <div class="mt-3">
                                            <span class="product-price fs-6 text-accent">Ver opciones</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

       <div class="col-md-4 col-lg-3 comanda-lateral p-3">
            <h5 class="text-center fw-bold text-accent mb-3">COMANDERO</h5>
            
            <div class="flex-grow-1 overflow-auto pe-2" id="scroll-comanda">
                
                @php
                    // MAGIA EN BLADE: Buscamos el tamaño del producto en la memoria sin tocar el Backend
                    $obtenerNombreCompleto = function($id) use ($productos) {
                        $p = $productos->firstWhere('producto_id', $id);
                        return $p ? $p->nombre . ' (' . $p->tamano . ')' : 'Producto';
                    };
                @endphp

                @if(isset($detallesListo) && $detallesListo->isNotEmpty())
                    <div class="mb-3">
                        <p class="small text-success fw-bold mb-2">● LISTO PARA SERVIR</p>
                        @php 
                            // Agrupamos por producto y nota para hacer el "2x", "3x"
                            $agrupadosListo = $detallesListo->groupBy(function($item) {
                                return $item->producto_id . '|' . $item->comentarios;
                            });
                        @endphp
                        @foreach($agrupadosListo as $grupo)
                            @php $primerItem = $grupo->first(); @endphp
                            <div class="item-comanda border-success py-1 px-2 mb-1" style="background: rgba(46, 204, 113, 0.1);">
                                <div class="text-white small">
                                    <span class="text-success fw-bold me-1">{{ $grupo->count() }}x</span>
                                    {{ $obtenerNombreCompleto($primerItem->producto_id) }}
                                </div>
                                @if($primerItem->comentarios)
                                    <div class="text-muted fst-italic mt-1" style="font-size:10px;">"{{ $primerItem->comentarios }}"</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(isset($detallesCocina) && $detallesCocina->isNotEmpty())
                    <div class="mb-3">
                        <p class="small text-warning fw-bold mb-2">● EN COCINA</p>
                        @php 
                            // Agrupamos igual que arriba
                            $agrupadosCocina = $detallesCocina->groupBy(function($item) {
                                return $item->producto_id . '|' . $item->comentarios;
                            });
                        @endphp
                        @foreach($agrupadosCocina as $grupo)
                            @php $primerItem = $grupo->first(); @endphp
                            <div class="item-comanda border-warning py-1 px-2 mb-1" style="background: rgba(241, 196, 15, 0.1);">
                                <div class="text-white small">
                                    <span class="text-warning fw-bold me-1">{{ $grupo->count() }}x</span>
                                    {{ $obtenerNombreCompleto($primerItem->producto_id) }}
                                </div>
                                @if($primerItem->comentarios)
                                    <div class="text-muted fst-italic mt-1" style="font-size:10px;">"{{ $primerItem->comentarios }}"</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <p class="small text-accent fw-bold mb-2">● NUEVA SELECCIÓN</p>
                <div id="lista-comanda">
                    <div class="text-center text-liquid-muted py-4 small">Orden vacía</div>
                </div>
            </div>

            <div class="pt-3 border-top border-secondary">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-liquid-muted">TOTAL:</span>
                    <span class="fs-4 fw-bold text-white" id="total-comanda">$0.00</span>
                </div>
                
                <form id="form-pedido" action="{{ route('mesero.guardar_pedido', $mesa->mesa_id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="json_pedido" id="json_pedido">
                    <button type="button" class="btn btn-sr-pizza w-100 fw-bold" onclick="enviarAlBack()">
                        ENVIAR A COCINA
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTamanos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background: var(--bg-darker, #1a1a1a); border: 1px solid var(--glass-border, #333);">
      <div class="modal-header border-secondary">
        <h5 class="modal-title fw-bold text-accent" id="modalTamanosTitulo">Seleccionar Tamaño</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="modalTamanosCuerpo">
        </div>
    </div>
  </div>
</div>



<script>
    // Ahora cada elemento tendrá una propiedad 'cantidad'
    let comanda = [];
    let total = 0;

    // Inicializamos el modal de Bootstrap
    let modalTamanosInstance = null;
    document.addEventListener("DOMContentLoaded", function() {
        modalTamanosInstance = new bootstrap.Modal(document.getElementById('modalTamanos'));
    });

    // Función para mostrar el modal de tamaños
    function abrirModalTamanos(elemento) {
        const nombreProducto = elemento.getAttribute('data-nombre');
        const variaciones = JSON.parse(elemento.getAttribute('data-variaciones'));
        
        document.getElementById('modalTamanosTitulo').innerText = nombreProducto;
        const cuerpo = document.getElementById('modalTamanosCuerpo');
        cuerpo.innerHTML = '';

        variaciones.forEach(prod => {
            const agotado = prod.stock_disponible < 1;
            const btnClass = agotado ? 'btn-outline-secondary disabled' : 'btn-outline-accent';
            const precioF = parseFloat(prod.precio).toFixed(2);
            
            const accionClick = agotado ? '' : `onclick="seleccionarTamano(${prod.producto_id}, '${nombreProducto}', '${prod.tamano}', ${prod.precio}, ${prod.stock_disponible})"`;

            cuerpo.innerHTML += `
                <div class="mb-2">
                    <button type="button" class="btn ${btnClass} w-100 d-flex justify-content-between align-items-center p-3 text-white" ${accionClick}>
                        <span class="fw-bold">${prod.tamano}</span>
                        <span>$${precioF}</span>
                    </button>
                    ${agotado ? '<div class="text-danger small fw-bold mt-1 text-center">AGOTADO</div>' : ''}
                </div>
            `;
        });

        modalTamanosInstance.show();
    }

    // Cierra modal y manda agregar
    function seleccionarTamano(id, nombreBase, tamano, precio, stock) {
        modalTamanosInstance.hide();
        const nombreParaComanda = `${nombreBase} (${tamano})`;
        agregarPlatillo(id, nombreParaComanda, precio, stock);
    }

    function agregarPlatillo(id, nombre, precio, stock) {
        if (stock < 1) {
            alert("Insumos insuficientes.");
            return;
        }

        let nota = prompt(`Nota para ${nombre}:`, "");
        if (nota === null) return; 
        
        // Convertimos a string vacío si no puso nada, para comparar fácilmente
        nota = nota.trim(); 

        // LÓGICA DE AGRUPACIÓN (x2, x3)
        // Buscamos si ya existe EXACTAMENTE el mismo producto (mismo id) Y con la misma nota
        let indexExistente = comanda.findIndex(item => item.id === id && item.nota === nota);

        if (indexExistente !== -1) {
            // Si ya existe, le aumentamos la cantidad y el precio parcial
            comanda[indexExistente].cantidad += 1;
            comanda[indexExistente].precio_total += parseFloat(precio);
        } else {
            // Si es nuevo o tiene una nota diferente, lo metemos como una nueva fila
            comanda.push({ 
                id: id, 
                nombre: nombre, 
                precio_unitario: parseFloat(precio), 
                precio_total: parseFloat(precio), 
                cantidad: 1, 
                nota: nota 
            });
        }

        total += parseFloat(precio);
        pintarComanda();
    }

    function pintarComanda() {
        const div = document.getElementById('lista-comanda');
        div.innerHTML = '';

        if (comanda.length === 0) {
            div.innerHTML = '<div class="text-center text-liquid-muted py-4 small">Orden vacía</div>';
        } else {
            comanda.forEach((item, i) => {
                // Si hay más de 1, mostramos el texto "2x" en naranja
                let badgeCantidad = item.cantidad > 1 
                                    ? `<span class="text-accent fw-bold me-1">${item.cantidad}x</span>` 
                                    : '';

                div.innerHTML += `
                    <div class="item-comanda d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="text-white small fw-bold">${badgeCantidad}${item.nombre}</div>
                            <div class="text-accent small">$${item.precio_total.toFixed(2)}</div>
                            ${item.nota ? `<div class="text-muted fst-italic" style="font-size:10px;">"${item.nota}"</div>` : ''}
                        </div>
                        <button type="button" class="btn btn-sm text-danger p-0 ms-2" onclick="quitar(${i})">✕</button>
                    </div>
                `;
            });
        }
        document.getElementById('total-comanda').innerText = '$' + total.toFixed(2);
        const s = document.getElementById('scroll-comanda');
        s.scrollTop = s.scrollHeight;
    }

    function quitar(i) {
        // Restamos TODO el precio acumulado de esa fila al total global
        total -= comanda[i].precio_total;
        comanda.splice(i, 1);
        pintarComanda();
    }

    function enviarAlBack() {
        if (comanda.length === 0) {
            alert("No has seleccionado productos.");
            return;
        }

        // PREPARAMOS LA TIRA PARA EL BACKEND
        // Como el back espera un array de elementos unitarios (id, nota), "desenvolvemos" las cantidades.
        // Si hay un "2x Pizza", mandamos 2 objetos idénticos al backend para que el foreach del controlador lo procese sin tocar la lógica PHP.
        let comandaDesglosada = [];
        comanda.forEach(item => {
            for(let c = 0; c < item.cantidad; c++) {
                comandaDesglosada.push({
                    id: item.id,
                    nota: item.nota
                });
            }
        });

        document.getElementById('json_pedido').value = JSON.stringify(comandaDesglosada);
        document.getElementById('form-pedido').submit();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>