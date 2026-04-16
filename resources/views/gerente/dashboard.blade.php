<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gerencia - Sr. Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <style>
        body { overflow: hidden; height: 100vh; }
    </style>
</head>
<body>

<nav class="liquid-navbar mb-4">
    <h3 class="m-0 fw-bold text-accent">Sr. Pizza <span class="text-white fw-light">| GERENCIA</span></h3>
    <div class="d-flex align-items-center gap-3">
        <span class="text-liquid-muted d-none d-md-block">
            Gerente: <span class="fw-bold text-white">{{ Session::get('nombre', 'Usuario') }}</span>
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
            <h6 class="text-liquid-muted mb-4 text-center text-uppercase tracking-wide" style="letter-spacing: 2px;">Módulos</h6>
            <div class="nav nav-pills" id="gerenteTabs" role="tablist">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-operacion">
                    <i class="bi bi-display me-2"></i> Operación Piso
                </button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-caja">
                    <i class="bi bi-wallet2 me-2"></i> Finanzas / Caja
                </button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-personal">
                    <i class="bi bi-people me-2"></i> Personal
                </button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-insumos">
                    <i class="bi bi-box-seam me-2"></i> Insumos y Menú
                </button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-auditoria">
                    <i class="bi bi-shield-lock me-2"></i> Auditoría
                </button>
            </div>
            
            <div class="mt-auto pt-3 border-top border-secondary border-opacity-25 text-center">
                <small class="text-liquid-muted">Ventas de Hoy</small>
                <h4 class="text-success m-0 fw-bold">${{ number_format($ingresosHoy, 2) }}</h4>
            </div>
        </div>

        <div class="dashboard-content tab-content" id="gerenteTabsContent">
            
            <div class="tab-pane fade show active" id="tab-operacion">
                <div class="row g-4">
                    
                    <div class="col-md-6 d-flex flex-column gap-4">
                        <div class="widget-liquid">
                            <div class="widget-header py-3"><h5><i class="bi bi-fire"></i> Cocina</h5></div>
                            <div class="widget-body d-flex justify-content-around text-center py-4">
                                <div>
                                    <h1 class="text-warning fw-bold m-0" style="font-size: 3rem;">{{ $pedidosEnCola }}</h1>
                                    <span class="text-liquid-muted small text-uppercase fw-bold">Pedidos en cola</span>
                                </div>
                                <div>
                                    <h1 class="text-danger fw-bold m-0" style="font-size: 3rem;">{{ $tiempoPromedio }}<span class="fs-5">m</span></h1>
                                    <span class="text-liquid-muted small text-uppercase fw-bold">Espera Promedio</span>
                                </div>
                            </div>
                        </div>

                        <div class="widget-liquid" style="border-color: rgba(237, 145, 9, 0.4);">
                            <div class="widget-header py-3" style="background: rgba(237, 145, 9, 0.1);">
                                <h5 class="m-0 text-white"><i class="bi bi-receipt text-accent"></i> Pendientes por Cobrar</h5>
                            </div>
                            <div class="widget-body p-0 custom-scroll" style="height: calc(100vh - 420px); overflow-y: auto;">
                                <ul class="list-group list-group-flush">
                                    @forelse($pedidosCaja as $ped)
                                        <li class="list-group-item bg-transparent border-bottom border-secondary border-opacity-25 py-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-white fw-bold fs-6 d-block">Mesa {{ $ped->numero_mesa }}</span>
                                                <small class="{{ $ped->estado == 'Listo' ? 'text-success' : 'text-info' }} fw-bold" style="font-size: 0.75rem;">
                                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.4rem; vertical-align: middle;"></i>{{ strtoupper($ped->estado) }}
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="text-accent fw-bold fs-5 d-block">${{ number_format($ped->total, 2) }}</span>
                                                <span class="text-liquid-muted" style="font-size: 0.75rem;">Orden #{{ $ped->pedido_id }}</span>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item bg-transparent text-center border-0 py-4">
                                            <i class="bi bi-check2-circle text-success" style="font-size: 3rem;"></i>
                                            <h6 class="text-white mt-2">Caja al día</h6>
                                            <p class="text-liquid-muted small m-0">No hay cuentas pendientes.</p>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="widget-liquid">
                            <div class="widget-header py-3"><h5><i class="bi bi-grid-3x3-gap"></i> Mapa de Mesas</h5></div>
                            <div class="widget-body p-0 custom-scroll" style="height: calc(100vh - 220px); overflow-y: auto;">
                                <ul class="list-group list-group-flush">
                                    @foreach($mesas as $mesa)
                                        <li class="list-group-item bg-transparent text-white border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center py-3 px-4">
                                            <span class="fw-bold fs-6">Mesa {{ $mesa->numero_mesa }}</span>
                                            <span class="liquid-badge {{ $mesa->estado == 'Disponible' ? 'border-success text-success' : 'border-warning text-warning' }} py-1 px-2" style="font-size: 0.75rem;">{{ $mesa->estado }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane fade h-100" id="tab-caja">
                <div class="row g-4 h-100">
                    
                    <div class="col-md-7 d-flex flex-column gap-4">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="widget-liquid d-flex align-items-center p-4" style="border-color: rgba(46, 204, 113, 0.4); background: rgba(46, 204, 113, 0.05);">
                                    <div class="me-4">
                                        <i class="bi bi-cash-stack text-success" style="font-size: 5rem; text-shadow: 0 0 20px rgba(46, 204, 113, 0.4);"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-liquid-muted text-uppercase tracking-wide mb-1">Ingresos Registrados (Hoy)</h5>
                                        <h1 class="display-3 fw-bold text-white m-0">${{ number_format($ingresosHoy, 2) }}</h1>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="widget-liquid text-center py-4" style="border-color: rgba(52, 152, 219, 0.3);">
                                    <i class="bi bi-cart-check text-info" style="font-size: 3rem;"></i>
                                    <h1 class="display-5 fw-bold text-white mt-2">{{ $volumenVentasHoy }}</h1>
                                    <h6 class="text-info m-0 text-uppercase">Órdenes Pagadas</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="widget-liquid text-center py-4" style="border-color: rgba(241, 196, 15, 0.3);">
                                    <i class="bi bi-receipt text-warning" style="font-size: 3rem;"></i>
                                    <h1 class="display-5 fw-bold text-white mt-2">
                                        ${{ $volumenVentasHoy > 0 ? number_format($ingresosHoy / $volumenVentasHoy, 2) : '0.00' }}
                                    </h1>
                                    <h6 class="text-warning m-0 text-uppercase">Ticket Promedio</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 h-100">
                        <div class="widget-liquid h-100 d-flex flex-column" style="border-color: rgba(237, 145, 9, 0.5);">
                            <div class="widget-header py-3" style="background: rgba(237, 145, 9, 0.1);">
                                <h5 class="m-0 text-white"><i class="bi bi-safe2 text-accent me-2"></i> Control de Caja</h5>
                            </div>
                            <div class="widget-body flex-grow-1 d-flex flex-column justify-content-between">
                                
                                <div>
                                    <h6 class="text-liquid-muted text-center mb-4">CÁLCULO EXACTO ESPERADO</h6>
                                    
                                    <div class="d-flex justify-content-between border-bottom border-secondary border-opacity-25 py-3">
                                        <span class="text-white fs-5">Fondo Inicial</span>
                                        <span class="text-liquid-muted fs-5">$500.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-bottom border-secondary border-opacity-25 py-3">
                                        <span class="text-white fs-5">Ventas Cobradas</span>
                                        <span class="text-success fw-bold fs-5">+ ${{ number_format($ingresosHoy, 2) }}</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-4 bg-dark bg-opacity-50 p-3 rounded border border-accent border-opacity-50">
                                        <span class="text-accent fw-bold fs-4">TOTAL EN CAJA</span>
                                        <span class="text-white fw-bold fs-3">${{ number_format($ingresosHoy + 500, 2) }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                                    <p class="text-liquid-muted small text-center mb-3">Revisa que el efectivo físico coincida con el cálculo exacto antes de autorizar el cierre.</p>
                                    
                                    <button class="btn btn-sr-pizza w-100 fs-5 d-flex justify-content-center align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCorteCaja">
                                        <i class="bi bi-shield-check"></i> AUTORIZAR CORTE DE CAJA
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane fade h-100" id="tab-personal">
                <div class="widget-liquid h-100 d-flex flex-column" style="border-color: rgba(52, 152, 219, 0.4); background: rgba(20, 22, 26, 0.8);">
                    <div class="widget-header py-3 d-flex justify-content-between align-items-center" style="background: rgba(52, 152, 219, 0.1);">
                        <h5 class="m-0 text-white"><i class="bi bi-people text-info me-2"></i> Gestión de Personal Operativo</h5>
                        <button class="btn btn-sm btn-info text-white fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoPersonal" style="background-color: rgba(52, 152, 219, 0.8); border: none;">
                            <i class="bi bi-person-plus-fill"></i> Nuevo Empleado
                        </button>
                    </div>
                    
                    <div class="widget-body p-0 flex-grow-1 overflow-auto custom-scroll">
                        <table class="table table-dark table-liquid mb-0 align-middle" style="background: transparent; --bs-table-bg: transparent;">
                            <thead style="position: sticky; top: 0; z-index: 1; background: #1a1d24;">
                                <tr>
                                    <th class="ps-4 border-bottom border-secondary border-opacity-50 text-uppercase text-info">Empleado</th>
                                    <th class="border-bottom border-secondary border-opacity-50 text-uppercase text-info">Puesto / Rol</th>
                                    <th class="border-bottom border-secondary border-opacity-50 text-uppercase text-info">Matrícula</th>
                                    <th class="border-bottom border-secondary border-opacity-50 text-uppercase text-info">Salario / Com.</th>
                                    <th class="text-end pe-4 border-bottom border-secondary border-opacity-50 text-uppercase text-info">Administrar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $usr)
                                    <tr style="{{ $usr->activo == 0 ? 'opacity: 0.4; filter: grayscale(100%); transition: all 0.3s ease;' : 'transition: all 0.3s ease;' }}">
                                        <td class="ps-4 border-bottom border-secondary border-opacity-25 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex justify-content-center align-items-center text-white fw-bold shadow-sm" style="width: 45px; height: 45px; background: {{ $usr->activo == 1 ? 'linear-gradient(135deg, #f08401, #e74c3c)' : '#444' }}; font-size: 1.2rem;">
                                                    {{ strtoupper(substr($usr->nombre_completo, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="text-white fw-bold d-block fs-6">{{ $usr->nombre_completo }}</span>
                                                    <span class="{{ $usr->activo == 1 ? 'text-success' : 'text-danger' }} small fw-bold">
                                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.4rem;"></i>{{ $usr->activo == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="border-bottom border-secondary border-opacity-25">
                                            @if($usr->id_rol == 2) <span class="liquid-badge border-primary text-primary bg-primary bg-opacity-10"><i class="bi bi-cup-hot me-1"></i> {{ $usr->nombre_rol }}</span>
                                            @elseif($usr->id_rol == 4) <span class="liquid-badge border-warning text-warning bg-warning bg-opacity-10"><i class="bi bi-fire me-1"></i> {{ $usr->nombre_rol }}</span>
                                            @elseif($usr->id_rol == 5) <span class="liquid-badge border-success text-success bg-success bg-opacity-10"><i class="bi bi-cash me-1"></i> {{ $usr->nombre_rol }}</span>
                                            @else <span class="liquid-badge border-secondary text-white">{{ $usr->nombre_rol }}</span>
                                            @endif
                                        </td>
                                        
                                        <td class="text-liquid-muted fw-bold font-monospace fs-6 border-bottom border-secondary border-opacity-25">{{ $usr->matricula }}</td>
                                        
                                        <td class="border-bottom border-secondary border-opacity-25">
                                            <span class="text-accent fw-bold fs-5">${{ number_format($usr->porcentaje_comision, 2) }}</span>
                                            <span class="text-liquid-muted small d-block">Base</span>
                                        </td>
                                        
                                        <td class="text-end pe-4 border-bottom border-secondary border-opacity-25">
                                            <div class="d-flex gap-2 justify-content-end">
                                                
                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalEditarPersonal_{{ $usr->id_usuario }}" title="Editar Rol o Salario" {{ $usr->activo == 0 ? 'disabled' : '' }}>
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <form action="{{ route('gerente.usuario.estado', $usr->id_usuario) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $usr->activo == 1 ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $usr->activo == 1 ? 'Dar de Baja' : 'Reactivar Empleado' }}">
                                                        @if($usr->activo == 1) <i class="bi bi-person-x-fill"></i> @else <i class="bi bi-person-check-fill"></i> @endif
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @foreach($usuarios as $usr)
                    <div class="modal fade liquid-modal text-start" id="modalEditarPersonal_{{ $usr->id_usuario }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('gerente.actualizar_empleado', $usr->id_usuario) }}" method="POST" class="modal-content">
                                @csrf
                                <div class="modal-header border-secondary border-opacity-25 pb-3">
                                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Editar: <span class="text-white">{{ $usr->nombre_completo }}</span></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body px-4 py-4">
                                    
                                    <div class="alert mb-4 text-center" style="background: rgba(52, 152, 219, 0.1); border: 1px solid rgba(52, 152, 219, 0.3);">
                                        <small class="text-liquid-muted d-block">Matrícula</small>
                                        <strong class="text-info fs-4 font-monospace">{{ $usr->matricula }}</strong>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="text-liquid-muted fw-bold mb-2">Puesto / Rol</label>
                                            <select name="nuevo_rol" class="form-select liquid-input text-white fw-bold" style="background: rgba(0,0,0,0.8);" required>
                                                @foreach($rolesOperativos ?? [] as $rol)
                                                    <option value="{{ $rol->rol_id }}" {{ $usr->id_rol == $rol->rol_id ? 'selected' : '' }} style="color: black;">
                                                        {{ $rol->nombre_rol }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-liquid-muted fw-bold mb-2">Salario / Comisión Base</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-secondary border-opacity-25 text-accent">$</span>
                                                <input type="number" name="nueva_comision" class="form-control liquid-input text-white fw-bold" value="{{ $usr->porcentaje_comision }}" step="0.01" min="0" required>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer border-secondary border-opacity-25 px-4 pb-4">
                                    <button type="button" class="btn btn-outline-secondary w-100 mb-2 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-info text-white fw-bold w-100 m-0" style="background-color: rgba(52, 152, 219, 0.8);">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
                </div>

            <div class="tab-pane fade h-100" id="tab-insumos">
                <div class="row g-4 h-100">
                    <div class="col-md-5">
                        <div class="widget-liquid h-100 d-flex flex-column" style="border-color: rgba(231, 76, 60, 0.4);">
                            <div class="widget-header" style="background: rgba(231, 76, 60, 0.1);">
                                <h5 class="text-danger m-0"><i class="bi bi-exclamation-triangle"></i> Alertas de Desabasto</h5>
                            </div>
                            <div class="widget-body flex-grow-1 overflow-auto custom-scroll">
                                @forelse($alertasStock as $insumo)
                                    <div class="alert mb-3" style="background: rgba(231, 76, 60, 0.1); border: 1px solid rgba(231, 76, 60, 0.3); color: white;">
                                        <strong class="fs-5">{{ $insumo->nombre }}</strong><br>
                                        <span class="text-danger fw-bold">Stock Actual: {{ $insumo->stock_actual }}</span> | <span class="text-liquid-muted">Mín: {{ $insumo->stock_minimo }}</span>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                                        <h5 class="text-success mt-3">Inventario óptimo.</h5>
                                        <p class="text-liquid-muted">Ningún insumo bajo el mínimo.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="widget-liquid h-100 d-flex flex-column" style="background: rgba(20, 22, 26, 0.8);">
                            <div class="widget-header py-3">
                                <h5><i class="bi bi-tags"></i> Control de Precios (Menú)</h5>
                            </div>
                            <div class="widget-body p-0 flex-grow-1 overflow-auto custom-scroll">
                                <table class="table table-dark table-liquid mb-0 align-middle" style="background: transparent; --bs-table-bg: transparent;">
                                    <thead style="position: sticky; top: 0; z-index: 1; background: #1a1d24;">
                                        <tr>
                                            <th class="ps-4 border-bottom border-secondary border-opacity-50 text-uppercase text-info">Platillo</th>
                                            <th class="border-bottom border-secondary border-opacity-50 text-uppercase text-info">P. Actual</th>
                                            <th class="pe-4 text-end border-bottom border-secondary border-opacity-50 text-uppercase text-info">Modificar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($productosMenu as $prod)
                                            <tr>
                                                <td class="ps-4 border-bottom border-secondary border-opacity-25 py-3">
                                                    <div class="text-white fw-bold fs-6">{{ $prod->nombre }}</div>
                                                    <div class="text-liquid-muted small">{{ $prod->tamano ?? 'Único' }}</div>
                                                </td>
                                                <td class="text-accent fw-bold align-middle fs-5 border-bottom border-secondary border-opacity-25">${{ number_format($prod->precio, 2) }}</td>
                                                <td class="pe-4 align-middle border-bottom border-secondary border-opacity-25">
                                                    <form action="{{ route('gerente.actualizar_precio', $prod->producto_id) }}" method="POST" class="d-flex gap-2 justify-content-end">
                                                        @csrf
                                                        <input type="number" name="nuevo_precio" class="form-control form-control-sm liquid-input" value="{{ $prod->precio }}" step="0.01" min="0" required style="width: 100px; text-align: center; font-weight: bold; font-size: 1.1rem;">
                                                        <button type="submit" class="btn btn-sm btn-outline-accent" title="Actualizar Precio"><i class="bi bi-check-lg"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade h-100" id="tab-auditoria">
                <div class="widget-liquid h-100 d-flex flex-column" style="border-color: rgba(231, 76, 60, 0.4); background: rgba(20, 22, 26, 0.8);">
                    <div class="widget-header py-3" style="background: rgba(231, 76, 60, 0.1);">
                        <h5 class="text-danger m-0"><i class="bi bi-shield-lock me-2"></i> Registros de Seguridad (Bitácora)</h5>
                    </div>
                    <div class="widget-body p-0 flex-grow-1 overflow-auto custom-scroll">
                        <table class="table table-dark table-liquid mb-0 align-middle" style="background: transparent; --bs-table-bg: transparent;">
                            <thead style="position: sticky; top: 0; z-index: 1; background: #1a1d24;">
                                <tr>
                                    <th class="ps-4 border-bottom border-secondary border-opacity-50 text-uppercase text-danger">Fecha y Hora</th>
                                    <th class="border-bottom border-secondary border-opacity-50 text-uppercase text-danger">Acción</th>
                                    <th class="border-bottom border-secondary border-opacity-50 text-uppercase text-danger">Tabla Afectada</th>
                                    <th class="pe-4 border-bottom border-secondary border-opacity-50 text-uppercase text-danger">Usuario / Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bitacora as $log)
                                    <tr>
                                        <td class="ps-4 text-liquid-muted border-bottom border-secondary border-opacity-25 py-3">{{ \Carbon\Carbon::parse($log->fecha ?? now())->format('d/m/Y H:i') }}</td>
                                        <td class="text-danger fw-bold border-bottom border-secondary border-opacity-25">{{ $log->accion ?? 'Desconocida' }}</td>
                                        <td class="text-accent border-bottom border-secondary border-opacity-25">{{ $log->tabla_afectada ?? 'N/A' }}</td>
                                        <td class="pe-4 text-white border-bottom border-secondary border-opacity-25">{{ $log->detalles ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-liquid-muted py-5 border-0">
                                        <i class="bi bi-shield-check text-secondary" style="font-size: 3rem;"></i>
                                        <p class="mt-3">Sin registros de actividad sospechosa.</p>
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade liquid-modal" id="modalNuevoPersonal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('gerente.crear_personal') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> Alta de Personal Operativo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div class="mb-3">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre_completo" class="form-control liquid-input" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo se permiten letras y espacios. No utilices números ni símbolos." required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control liquid-input" pattern="[0-9]+" title="Por favor, ingresa únicamente números." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Rol</label>
                        <select name="id_rol" class="form-select liquid-input text-white" style="background: rgba(0,0,0,0.8);" required>
                            <option value="2">Mesero</option>
                            <option value="4">Cocinero</option>
                            <option value="5">Cajero</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Matrícula (Login)</label>
                        <input type="text" name="matricula" class="form-control liquid-input" pattern="[0-9]+" title="La matrícula debe contener únicamente números enteros." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Contraseña</label>
                        <input type="text" name="contrasena" class="form-control liquid-input" minlength="3" title="La contraseña debe tener al menos 3 caracteres." required>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-4 pb-4">
                <button type="submit" class="btn btn-sr-pizza w-100 fw-bold">Crear Empleado</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade liquid-modal" id="modalCorteCaja" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('gerente.corte_caja') }}" method="POST" class="modal-content text-center">
            @csrf
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-5 pb-5 pt-0">
                <i class="bi bi-exclamation-triangle text-warning mb-3" style="font-size: 4rem;"></i>
                <h3 class="text-white fw-bold">¿Autorizar Cierre?</h3>
                <p class="text-liquid-muted">Al confirmar, se registrará el corte de caja con un total de <strong class="text-accent">${{ number_format($ingresosHoy + 500, 2) }}</strong>.</p>
                <p class="text-danger small">Las ventas cobradas regresarán a $0.00 para el siguiente turno.</p>
                
                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary w-50 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sr-pizza w-50 fw-bold">Confirmar Corte</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>