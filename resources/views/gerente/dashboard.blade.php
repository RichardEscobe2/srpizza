<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gerencial - Sr. Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos estructurales básicos (sin diseño profundo por ahora) */
        body { background-color: #1e1e1e; color: white; overflow-x: hidden; }
        .header { background-color: #000; padding: 15px; border-bottom: 3px solid #f15a24; }
        
        /* Barra lateral estructural */
        .sidebar { background-color: #000; min-height: 100vh; padding: 20px; border-right: 2px solid #333; }
        .btn-sidebar { width: 100%; margin-bottom: 15px; background-color: #f15a24; color: white; font-weight: bold; border: none; padding: 10px; border-radius: 8px; text-align: center; }
        .btn-sidebar.active { background-color: #d94e1e !important; color: white !important; }
        
        .content-area { padding: 30px; }
    </style>
</head>
<body>

<!-- ENCABEZADO SUPERIOR -->
<div class="header d-flex justify-content-between align-items-center">
    <h3 style="color: #f15a24; margin: 0;">Sr. Pizza</h3>
    <div>
        <span class="me-3">Gerente: {{ Session::get('nombre', 'Usuario') }}</span>
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger">Cerrar Sesión</a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        
        <!-- BARRA LATERAL IZQUIERDA (PANEL DE CONTROL) -->
        <div class="col-md-2 sidebar">
            <h6 class="text-center mb-4 text-muted fw-bold">PANEL DE CONTROL</h6>
            
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link btn-sidebar active" id="tab-operacion" data-bs-toggle="pill" data-bs-target="#seccion-operacion" type="button" role="tab">Operación</button>
                <button class="nav-link btn-sidebar" id="tab-personal" data-bs-toggle="pill" data-bs-target="#seccion-personal" type="button" role="tab">Personal</button>
                <button class="nav-link btn-sidebar" id="tab-menu" data-bs-toggle="pill" data-bs-target="#seccion-menu" type="button" role="tab">Menú</button>
                <button class="nav-link btn-sidebar" id="tab-insumos" data-bs-toggle="pill" data-bs-target="#seccion-insumos" type="button" role="tab">Insumos</button>
                <button class="nav-link btn-sidebar" id="tab-caja" data-bs-toggle="pill" data-bs-target="#seccion-caja" type="button" role="tab">Caja</button>
            </div>
        </div>

        <!-- ÁREA CENTRAL DE CONTENIDO DINÁMICO -->
        <div class="col-md-10 content-area">
            <div class="tab-content" id="v-pills-tabContent">
                
               <!-- 1. SECCIÓN OPERACIÓN (Dashboard principal de monitoreo) -->
                <div class="tab-pane fade show active" id="seccion-operacion" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-2 mb-4">
                        <h3 class="m-0 text-white">SALA PRINCIPAL</h3>
                        <a href="{{ route('gerente.dashboard') }}" class="btn btn-sm btn-outline-warning">↻ Actualizar</a>
                    </div>
                    
                    <div class="row">
                        <!-- MAPA DE MESAS (IZQUIERDA) -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                @foreach($mesas as $mesa)
                                    <div class="col-md-4">
                                        <div class="card bg-dark text-center border-2 
                                            @if($mesa->estado == 'Disponible') border-success 
                                            @elseif($mesa->estado == 'Ocupada') border-danger 
                                            @else border-warning @endif" style="min-height: 120px;">
                                            
                                            <div class="card-body d-flex flex-column justify-content-center">
                                                <h5 class="fw-bold text-white mb-1">MESA {{ $mesa->numero_mesa }}</h5>
                                                
                                                <div>
                                                    <span class="badge 
                                                        @if($mesa->estado == 'Disponible') bg-success 
                                                        @elseif($mesa->estado == 'Ocupada') bg-danger 
                                                        @else bg-warning text-dark @endif">
                                                        {{ mb_strtoupper($mesa->estado) }}
                                                    </span>
                                                </div>

                                               <!-- Temporizador de consumo si está ocupada -->
                                                @if($mesa->estado == 'Ocupada' && $mesa->pedido_fecha)
                                                    <p class="text-danger small mt-2 mb-0 fw-bold">
                                                        ⏱️ {{ round(abs(\Carbon\Carbon::parse($mesa->pedido_fecha)->diffInMinutes(now()))) }} min
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- MONITOR DE COCINA (DERECHA) -->
                        <div class="col-md-4">
                            <h4 class="text-warning text-center border-bottom border-secondary pb-2 mb-3">MONITOR DE COCINA</h4>
                            
                            <div class="card bg-dark border-secondary text-center p-4 mb-3 shadow">
                                <h1 class="display-1 text-white fw-bold mb-0">{{ $pedidosEnCola }}</h1>
                                <p class="text-muted fs-5">Pedidos en Cola</p>
                            </div>

                            <div class="card bg-dark border-secondary text-center p-4 shadow">
                                <h2 class="text-success fw-bold mb-0">{{ round($tiempoPromedio) }} min</h2>
                                <p class="text-muted">Tiempo Promedio de Preparación</p>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <p class="text-muted small">Indicadores generados en tiempo real.<br>Un tiempo promedio mayor a 20 min requiere asistencia en cocina.</p>
                            </div>
                        </div>
                    </div>
                </div>

               <!-- 2. SECCIÓN PERSONAL -->
                <div class="tab-pane fade" id="seccion-personal" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-2 mb-4">
                        <h3 class="m-0 text-white">Gestión de Personal</h3>
                        <!-- Botón que abre el Modal -->
                        <button type="button" class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevoEmpleado">
                            + Nuevo Usuario
                        </button>
                    </div>
                    
                    <!-- TABLA DE EMPLEADOS OPERATIVOS -->
                    <div class="card bg-dark border-secondary mt-3">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover align-middle mb-0">
                                    <thead class="table-active">
                                        <tr>
                                            <th>Nombre Completo</th>
                                            <th>Rol / Puesto</th>
                                            <th>Matrícula</th>
                                            <th>Comisión %</th>
                                            <th>Estado</th>
                                            <th class="text-end">Acciones Operativas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($usuarios as $user)
                                            <tr>
                                                <td>{{ $user->nombre_completo }}</td>
                                                <td><span class="badge bg-secondary">{{ $user->nombre_rol }}</span></td>
                                                <td class="fw-bold text-warning">{{ $user->matricula }}</td>
                                                
                                                <!-- FORMULARIO DE EDICIÓN DE COMISIÓN -->
                                                <td>
                                                    <form action="{{ route('gerente.usuario_comision', $user->id_usuario) }}" method="POST" class="d-flex align-items-center">
                                                        @csrf
                                                        <input type="number" name="nueva_comision" class="form-control form-control-sm bg-secondary text-white border-0 me-1" value="{{ $user->porcentaje_comision }}" step="0.01" min="0" max="100" style="width: 75px;">
                                                        <button type="submit" class="btn btn-sm btn-outline-warning">✔</button>
                                                    </form>
                                                </td>

                                                <td>
                                                    @if($user->activo) 
                                                        <span class="badge bg-success">Activo</span>
                                                    @else 
                                                        <span class="badge bg-danger">Inactivo</span> 
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <!-- Formulario de Cambio de Contraseña -->
                                                    <form action="{{ route('gerente.usuario_password', $user->id_usuario) }}" method="POST" class="d-inline-block me-2">
                                                        @csrf
                                                        <div class="input-group input-group-sm" style="width: 180px;">
                                                            <input type="text" name="nueva_password" class="form-control bg-secondary text-white border-0" placeholder="Nueva Pass..." required>
                                                            <button type="submit" class="btn btn-outline-light">Cambiar</button>
                                                        </div>
                                                    </form>

                                                    <!-- Botón de Baja/Alta Lógica (RN-01) -->
                                                    <form action="{{ route('gerente.usuario_estado', $user->id_usuario) }}" method="POST" class="d-inline-block">
                                                        @csrf
                                                        @if($user->activo)
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Desactivar a este usuario?')">Desactivar</button>
                                                        @else
                                                            <button type="submit" class="btn btn-sm btn-success">Reactivar</button>
                                                        @endif
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">No hay personal operativo registrado.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL DE NUEVO EMPLEADO (Restringido para Gerente) -->
                <div class="modal fade" id="modalNuevoEmpleado" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark text-white border-secondary">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title text-warning">Alta de Personal Operativo</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('gerente.crear_personal') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre Completo</label>
                                        <input type="text" name="nombre_completo" class="form-control bg-secondary text-white border-0" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" name="telefono" class="form-control bg-secondary text-white border-0" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Comisión (%)</label>
                                            <input type="number" name="comision" class="form-control bg-secondary text-white border-0" step="0.01" min="0" max="100" value="0">
                                        </div>
                                    </div>
                                    <hr class="border-secondary">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Matrícula (Login)</label>
                                            <input type="number" name="matricula" class="form-control bg-secondary text-white border-0" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Contraseña</label>
                                            <input type="text" name="contrasena" class="form-control bg-secondary text-white border-0" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label text-info">Rol Asignado</label>
                                            <select name="id_rol" class="form-select bg-secondary text-white border-0" required>
                                                <!-- Solo se muestran roles operativos (2, 4 y 5) -->
                                                <option value="2">Mesero</option>
                                                <option value="4">Cocinero</option>
                                                <option value="5">Cajero</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-secondary">
                                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success fw-bold">Guardar Empleado</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 3. SECCIÓN MENÚ -->
                <div class="tab-pane fade" id="seccion-menu" role="tabpanel">
                    <h3 class="border-bottom border-secondary pb-2">Catálogo del Menú</h3>
                    <p class="text-muted mt-3">[Aquí insertaremos la tabla de lectura de productos, habilitando únicamente el botón de "Cambiar Precio"]</p>
                </div>

                <!-- 4. SECCIÓN INSUMOS -->
                <div class="tab-pane fade" id="seccion-insumos" role="tabpanel">
                    <h3 class="border-bottom border-secondary pb-2">Monitor de Insumos y Stock</h3>
                    <p class="text-muted mt-3">[Aquí insertaremos la tabla de inventario en modo lectura para vigilar el Stock Actual vs Stock Mínimo]</p>
                </div>

                <!-- 5. SECCIÓN CAJA -->
                <div class="tab-pane fade" id="seccion-caja" role="tabpanel">
                    <h3 class="border-bottom border-secondary pb-2">Monitor Financiero (Caja)</h3>
                    <p class="text-muted mt-3">[Aquí insertaremos el espejo de la vista del cajero con las órdenes pendientes de cobro y totales diarios]</p>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>