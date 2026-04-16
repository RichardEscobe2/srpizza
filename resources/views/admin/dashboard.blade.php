<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Global - Sr. Pizza Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <style>
        body { overflow: hidden; height: 100vh; }
    </style>
</head>
<body>

<nav class="liquid-navbar mb-4">
    <h3 class="m-0 fw-bold text-accent">Sr. Pizza <span class="text-white fw-light">| ADMINISTRATIVO</span></h3>
    <div class="d-flex align-items-center gap-3">
        <span class="text-liquid-muted d-none d-md-block">
            Administrador: <span class="fw-bold text-white">{{ Session::get('nombre', 'Usuario') }}</span>
        </span>
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger-unified px-3">Cerrar Sesión</a>
    </div>
</nav>

<div class="container-fluid px-4 pb-4">
    
    @if(session('success')) 
        <div class="alert alert-success text-center py-2 mx-auto mb-3" style="max-width: 600px; background: rgba(46, 204, 113, 0.1); color: #2ecc71; border: 1px solid rgba(46, 204, 113, 0.3);">
            {{ session('success') }}
        </div> 
    @endif
    @if($errors->any()) 
        <div class="alert alert-danger text-center py-2 mx-auto mb-3" style="max-width: 600px; background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3);">
            {{ $errors->first() }}
        </div> 
    @endif

    <div class="dashboard-wrapper">
        
        <div class="liquid-sidebar">
            <h6 class="text-liquid-muted mb-4 text-center text-uppercase tracking-wide" style="letter-spacing: 2px;">Control Total</h6>
            <div class="nav nav-pills" id="adminTabs" role="tablist">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-balance">
                    <i class="bi bi-graph-up-arrow me-2"></i> Balance Mensual
                </button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-personal">
                    <i class="bi bi-people-fill me-2"></i> Nómina 
                </button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-menu">
                    <i class="bi bi-journal-richtext me-2"></i> Menú y Recetas
                </button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-compras">
                    <i class="bi bi-truck me-2"></i> Abastecimiento
                </button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ajustes">
                    <i class="bi bi-gear-fill me-2"></i> Ajustes Globales
                </button>
            </div>
            <div class="mt-auto pt-3 border-top border-secondary border-opacity-25 text-center">
                <small class="text-liquid-muted">Utilidad Mes Actual</small>
                <h4 class="{{ $utilidadNeta >= 0 ? 'text-success' : 'text-danger' }} m-0 fw-bold">${{ number_format($utilidadNeta, 2) }}</h4>
            </div>
        </div>

        <div class="dashboard-content tab-content" id="adminTabsContent">
            
            <div class="tab-pane fade show active h-100" id="tab-balance">
                <div class="row g-4 h-100">
                    <div class="col-md-8 d-flex flex-column gap-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="widget-liquid text-center py-4" style="border-color: rgba(46, 204, 113, 0.4);">
                                    <h6 class="text-success m-0 text-uppercase tracking-wide">Ingresos Brutos (Mes)</h6>
                                    <h1 class="display-4 fw-bold text-white mt-2">${{ number_format($ingresosMes, 2) }}</h1>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="widget-liquid text-center py-4" style="border-color: rgba(231, 76, 60, 0.4);">
                                    <h6 class="text-danger m-0 text-uppercase tracking-wide">Egresos Totales</h6>
                                    <h1 class="display-4 fw-bold text-white mt-2">-${{ number_format($nominaMes + $costosComprasMes, 2) }}</h1>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="widget-liquid d-flex align-items-center justify-content-center p-4" style="border-color: rgba(237, 145, 9, 0.6); background: rgba(237, 145, 9, 0.1);">
                                    <div class="text-center">
                                        <h5 class="text-liquid-muted text-uppercase tracking-wide mb-1">Utilidad Neta Real</h5>
                                        <h1 class="display-1 fw-bold text-white m-0" style="text-shadow: 0 0 30px rgba(237, 145, 9, 0.5);">${{ number_format($utilidadNeta, 2) }}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 h-100">
                        <div class="widget-liquid h-100 d-flex flex-column" style="background: rgba(20, 22, 26, 0.8);">
                            <div class="widget-header py-3"><h5><i class="bi bi-pie-chart"></i> Gastos Detallados</h5></div>
                            <div class="widget-body p-4 flex-grow-1">
                                <div class="d-flex justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                                    <span class="text-white">Proveedores</span>
                                    <span class="text-danger">${{ number_format($costosComprasMes, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom border-secondary border-opacity-25 pb-2">
                                    <span class="text-white">Nómina Base</span>
                                    <span class="text-warning">${{ number_format($nominaMes, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade h-100" id="tab-personal">
                <div class="widget-liquid h-100 d-flex flex-column" style="border-color: rgba(52, 152, 219, 0.4); background: rgba(20, 22, 26, 0.8);">
                    <div class="widget-header py-3 d-flex justify-content-between align-items-center" style="background: rgba(52, 152, 219, 0.1);">
                        <h5 class="m-0 text-white"><i class="bi bi-people-fill text-info me-2"></i> Gestión Global de Personal</h5>
                        <button class="btn btn-sm btn-info text-white fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoPersonalAdmin">
                            <i class="bi bi-person-plus-fill"></i> Nuevo Empleado
                        </button>
                    </div>
                    <div class="widget-body p-0 flex-grow-1 overflow-auto custom-scroll">
                        <table class="table table-dark table-liquid mb-0 align-middle" style="background: transparent; --bs-table-bg: transparent;">
                            <thead style="position: sticky; top: 0; z-index: 1; background: #1a1d24;">
                                <tr>
                                    <th class="ps-4 text-info">Empleado</th>
                                    <th class="text-info">Rol</th>
                                    <th class="text-info">Matrícula</th>
                                    <th class="text-info">Salario Base</th>
                                    <th class="text-end pe-4 text-info">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosTodos as $usr)
                                    <tr style="{{ $usr->activo == 0 ? 'opacity: 0.4; filter: grayscale(100%);' : '' }}">
                                        <td class="ps-4 border-bottom border-secondary border-opacity-25 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex justify-content-center align-items-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: {{ $usr->activo == 1 ? 'linear-gradient(135deg, #f08401, #e74c3c)' : '#444' }};">
                                                    {{ strtoupper(substr($usr->nombre_completo, 0, 1)) }}
                                                </div>
                                                <span class="text-white fw-bold">{{ $usr->nombre_completo }}</span>
                                            </div>
                                        </td>
                                        <td class="border-bottom border-secondary border-opacity-25">
                                            <span class="liquid-badge border-secondary text-white">{{ $usr->nombre_rol }}</span>
                                        </td>
                                        <td class="text-liquid-muted fw-bold border-bottom border-secondary border-opacity-25 font-monospace">{{ $usr->matricula }}</td>
                                        <td class="text-accent fw-bold border-bottom border-secondary border-opacity-25">${{ number_format($usr->porcentaje_comision, 2) }}</td>
                                        <td class="text-end pe-4 border-bottom border-secondary border-opacity-25">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalEditarPersonalAdmin_{{ $usr->id_usuario }}"><i class="bi bi-pencil-square"></i></button>
                                                <form action="{{ route('admin.eliminar_personal', $usr->id_usuario) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente a este usuario de la BD?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3-fill"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade h-100" id="tab-menu">
                <div class="widget-liquid h-100 d-flex flex-column" style="border-color: rgba(241, 196, 15, 0.4); background: rgba(20, 22, 26, 0.8);">
                    <div class="widget-header py-3 d-flex justify-content-between align-items-center" style="background: rgba(241, 196, 15, 0.1);">
                        <h5 class="m-0 text-warning"><i class="bi bi-journal-richtext me-2"></i> Ingeniería de Menú</h5>
                        <button class="btn btn-sm btn-sr-pizza shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
                            <i class="bi bi-plus-circle-fill"></i> Nuevo Platillo
                        </button>
                    </div>
                    <div class="widget-body p-0 flex-grow-1 overflow-auto custom-scroll">
                        <table class="table table-dark table-liquid mb-0 align-middle" style="background: transparent; --bs-table-bg: transparent;">
                            <thead style="position: sticky; top: 0; z-index: 1; background: #1a1d24;">
                                <tr>
                                    <th class="ps-4 text-warning">Platillo</th>
                                    <th class="text-warning">Tamaño</th>
                                    <th class="text-warning">Precio Base</th>
                                    <th class="text-end pe-4 text-warning">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productosMenu as $prod)
                                    <tr>
                                        <td class="ps-4 fw-bold text-white border-bottom border-secondary border-opacity-25">{{ $prod->nombre }}</td>
                                        <td class="text-liquid-muted border-bottom border-secondary border-opacity-25">{{ $prod->tamano ?? 'Único' }}</td>
                                        <td class="text-accent fw-bold fs-5 border-bottom border-secondary border-opacity-25">${{ number_format($prod->precio, 2) }}</td>
                                        <td class="text-end pe-4 border-bottom border-secondary border-opacity-25">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="button" class="btn btn-sm btn-outline-info" 
        data-bs-toggle="modal" 
        data-bs-target="#modalReceta_{{ $prod->producto_id }}">
    <i class="bi bi-pencil-square"></i> Receta
</button>
                                                <form action="{{ route('gerente.actualizar_precio', $prod->producto_id) }}" method="POST" class="d-flex gap-2">
                                                    @csrf
                                                    <input type="number" name="nuevo_precio" class="form-control form-control-sm liquid-input" value="{{ $prod->precio }}" step="0.01" style="width: 80px; text-align: center;">
                                                    <button type="submit" class="btn btn-sm btn-outline-accent"><i class="bi bi-check-lg"></i></button>
                                                </form>
                                                <form action="{{ route('admin.eliminar_producto', $prod->producto_id) }}" method="POST" onsubmit="return confirm('¿Borrar producto del catálogo?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3-fill"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade h-100" id="tab-compras">
                <div class="row g-4 h-100">
                    <div class="col-md-5">
                        <div class="widget-liquid h-100 d-flex flex-column" style="border-color: rgba(46, 204, 113, 0.4); background: rgba(20, 22, 26, 0.8);">
                            <div class="widget-header py-3" style="background: rgba(46, 204, 113, 0.1);">
                                <h5 class="m-0 text-success"><i class="bi bi-truck me-2"></i> Ingreso de Mercancía</h5>
                            </div>
                            <div class="widget-body flex-grow-1 p-4">
                                <form action="{{ route('admin.registrar_compra') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="mb-2">Proveedor Oficial</label>
                                        <select name="proveedor_id" class="form-select liquid-input text-white" required>
                                            @foreach($proveedores ?? [] as $prov)
                                               <option value="{{ $prov->proveedor_id }}" style="color: black;">{{ $prov->contacto_nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-2">Insumo a Reabastecer</label>
                                        <select name="insumo_id" class="form-select liquid-input text-white" required>
                                            @foreach($insumos as $ins)
                                                <option value="{{ $ins->insumo_id }}" style="color: black;">{{ $ins->nombre }} (Stock: {{ $ins->stock_actual }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-6"><label class="mb-2">Cant.</label><input type="number" name="cantidad" step="0.01" class="form-control liquid-input text-white" required></div>
                                        <div class="col-6"><label class="mb-2">Costo Total ($)</label><input type="number" name="costo_total" step="0.01" class="form-control liquid-input text-accent" required></div>
                                    </div>
                                    <button type="submit" class="btn btn-sr-pizza w-100 py-3 fw-bold shadow-lg">Inyectar a Inventario</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="widget-liquid h-100 d-flex flex-column" style="background: rgba(20, 22, 26, 0.8);">
                            <div class="widget-header py-3"><h5><i class="bi bi-clock-history text-info"></i> Auditoría de Compras</h5></div>
                            <div class="widget-body flex-grow-1 d-flex align-items-center justify-content-center">
                                <div class="text-center text-liquid-muted"><i class="bi bi-boxes fs-1"></i><p class="mt-3">Las entradas actualizan el stock físico automáticamente.</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<div class="tab-pane fade h-100" id="tab-ajustes">
    <div class="row g-4 h-100">
        <div class="col-md-7">
            <div class="widget-liquid h-100 d-flex flex-column" style="border-color: rgba(52, 152, 219, 0.4); background: rgba(20, 22, 26, 0.8);">
                <div class="widget-header py-3" style="background: rgba(52, 152, 219, 0.1);">
                    <h5 class="m-0 text-white"><i class="bi bi-gear-fill text-info me-2"></i> Variables del Sistema</h5>
                </div>
                <div class="widget-body p-4 flex-grow-1 overflow-auto custom-scroll">
                    <form action="{{ route('admin.actualizar_config') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="text-liquid-muted fw-bold mb-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                                Nombre del Establecimiento
                            </label>
                            <input type="text" name="nombre_empresa" value="{{ $configBD->nombre_empresa }}" class="form-control liquid-input text-white fw-bold">
                        </div>

                        <div class="mb-4">
                            <label class="text-liquid-muted fw-bold mb-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                                Porcentaje de IVA (%)
                            </label>
                            <input type="number" name="iva" value="{{ $configBD->iva }}" class="form-control liquid-input text-accent fw-bold" step="1">
                        </div>

                        <button type="submit" class="btn btn-info text-white w-100 py-3 fw-bold shadow-lg">
                            <i class="bi bi-save2 me-2"></i> GUARDAR CAMBIOS GLOBALES
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="widget-liquid h-100 d-flex flex-column" style="border-color: rgba(241, 196, 15, 0.4); background: rgba(20, 22, 26, 0.8);">
                <div class="widget-header py-3" style="background: rgba(241, 196, 15, 0.1);">
                    <h5 class="m-0 text-warning"><i class="bi bi-shield-lock-fill me-2"></i> Seguridad y Respaldos</h5>
                </div>
                <div class="widget-body p-5 text-center flex-grow-1 d-flex flex-column justify-content-center">
                    <i class="bi bi-database-fill-down text-warning mb-4" style="font-size: 5rem; filter: drop-shadow(0 0 15px rgba(241, 196, 15, 0.3));"></i>
                    <h4 class="text-white fw-bold mb-3">Respaldo Integral</h4>
                    <p class="text-liquid-muted mb-5">Genera un volcado completo (.SQL) de todas las tablas: pedidos, personal y recetas.</p>
                    
                    <a href="{{ route('admin.respaldo_bd') }}" class="btn btn-sr-pizza w-100 py-3 fw-bold fs-5 shadow-lg">
                        <i class="bi bi-cloud-download me-2"></i> DESCARGAR DUMP SQL
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</div>

@foreach($usuariosTodos as $usr)
    <div class="modal fade liquid-modal text-start" id="modalEditarPersonalAdmin_{{ $usr->id_usuario }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('gerente.actualizar_empleado', $usr->id_usuario) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Editar: <span class="text-white">{{ $usr->nombre_completo }}</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="mb-2">Rol Asignado</label>
                            <select name="nuevo_rol" class="form-select liquid-input text-white" required>
                                @foreach($rolesTodos as $rol)
                                    <option value="{{ $rol->rol_id }}" {{ $usr->id_rol == $rol->rol_id ? 'selected' : '' }} style="color: black;">{{ $rol->nombre_rol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="mb-2">Salario Base ($)</label>
                            <input type="number" name="nueva_comision" class="form-control liquid-input text-white fw-bold" value="{{ $usr->porcentaje_comision }}" step="0.01" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white fw-bold">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<div class="modal fade liquid-modal text-start" id="modalNuevoPersonalAdmin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.crear_personal') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5 class="modal-title text-info"><i class="bi bi-person-plus-fill"></i> Alta de Sistema</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body px-4 py-4">
                <div class="mb-3"><label class="mb-2">Nombre Completo</label><input type="text" name="nombre_completo" class="form-control liquid-input" pattern="[A-Za-zÀ-ÿ\s]+" required></div>
                <div class="row g-4 mb-3">
                    <div class="col-md-6"><label class="mb-2">Teléfono</label><input type="text" name="telefono" class="form-control liquid-input" pattern="[0-9]+" required></div>
                    <div class="col-md-6">
                        <label class="mb-2">Nivel de Acceso</label>
                        <select name="id_rol" class="form-select liquid-input" style="background: rgba(0,0,0,0.8);" required>
                            @foreach($rolesTodos as $rol) <option value="{{ $rol->rol_id }}" style="color: black;">{{ $rol->nombre_rol }}</option> @endforeach
                        </select>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6"><label class="mb-2">Matrícula (Login)</label><input type="text" name="matricula" class="form-control liquid-input" pattern="[0-9]+" required></div>
                    <div class="col-md-6"><label class="mb-2">Contraseña</label><input type="text" name="contrasena" class="form-control liquid-input" minlength="3" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-info text-white">Registrar</button></div>
        </form>
    </div>
</div>

<div class="modal fade liquid-modal text-start" id="modalNuevoProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg"> 
        <form action="{{ route('admin.crear_producto') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title text-warning"><i class="bi bi-plus-circle-fill me-2"></i> Crear Producto y Receta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-4">
                <h6 class="text-white border-bottom border-secondary border-opacity-25 pb-2 mb-3">1. Datos del Platillo</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="mb-2">Nombre de la Pizza</label>
                        <input type="text" name="nombre" class="form-control liquid-input" placeholder="Ej. Hawaiana" required>
                    </div>
                    <div class="col-md-3">
                        <label class="mb-2">Tamaño</label>
                        <input type="text" name="tamano" class="form-control liquid-input" placeholder="Grande" required>
                    </div>
                    <div class="col-md-3">
                        <label class="mb-2">Precio ($)</label>
                        <input type="number" name="precio" step="0.01" class="form-control liquid-input text-accent" required>
                    </div>
                </div>

                <h6 class="text-white border-bottom border-secondary border-opacity-25 pb-2 mb-3">2. Composición de Receta</h6>
                <div id="contenedor-receta">
                    @for($i=0; $i < 3; $i++)
                    <div class="row g-2 mb-2">
                        <div class="col-md-8">
                            <select name="insumos[]" class="form-select liquid-input text-white">
                                <option value="" selected>Seleccionar Ingrediente...</option>
                                @foreach($insumos as $ins)
                                    <option value="{{ $ins->insumo_id }}" style="color:black;">{{ $ins->nombre }} ({{ $ins->unidad_medida }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="cantidades[]" step="0.001" class="form-control liquid-input" placeholder="Cant.">
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-sr-pizza px-4">Guardar Platillo</button>
            </div>
        </form>
    </div>
</div>
@foreach($productosMenu as $prod)
    <div class="modal fade liquid-modal text-start" id="modalReceta_{{ $prod->producto_id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.actualizar_receta') }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $prod->producto_id }}">
                <div class="modal-header">
                    <h5 class="modal-title text-info"><i class="bi bi-pencil-square"></i> Editar Receta: {{ $prod->nombre }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <p class="text-liquid-muted small mb-3">Actualiza los ingredientes y gramajes. La receta anterior será reemplazada por esta.</p>
                    
                    <div id="contenedor-edit-receta-{{ $prod->producto_id }}">
                        @for($i=0; $i < 4; $i++)
                        <div class="row g-2 mb-2">
                            <div class="col-md-8">
                                <select name="insumos[]" class="form-select liquid-input text-white">
                                    <option value="">-- Seleccionar Insumo --</option>
                                    @foreach($insumos as $ins)
                                        <option value="{{ $ins->insumo_id }}" style="color:black;">{{ $ins->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="cantidades[]" step="0.001" class="form-control liquid-input" placeholder="Cant.">
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white px-4">Actualizar Receta</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@foreach($productosMenu as $prod)
    <div class="modal fade liquid-modal text-start" id="modalReceta_{{ $prod->producto_id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.actualizar_receta') }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $prod->producto_id }}">
                <div class="modal-header">
                    <h5 class="modal-title text-info"><i class="bi bi-list-check"></i> Editar Receta: {{ $prod->nombre }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <p class="text-liquid-muted small mb-3">Define los ingredientes y porciones (Kg/Lt) para este platillo.</p>
                    @for($i=0; $i < 4; $i++)
                    <div class="row g-2 mb-2">
                        <div class="col-md-8">
                            <select name="insumos[]" class="form-select liquid-input text-white">
                                <option value="">-- Seleccionar Insumo --</option>
                                @foreach($insumos as $ins)
                                    <option value="{{ $ins->insumo_id }}" style="color:black;">{{ $ins->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="cantidades[]" step="0.001" class="form-control liquid-input" placeholder="Cant.">
                        </div>
                    </div>
                    @endfor
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-info text-white px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>