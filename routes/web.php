<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeseroController;
use App\Http\Controllers\CocinaController;
use App\Http\Controllers\CajaController;
use App\Http\Middleware\VerificarSesion;
use App\Http\Controllers\GerenteController;




// RUTAS PÚBLICAS (No requieren sesión)

Route::get('/', function () { return redirect('/login'); });
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// RUTAS PROTEGIDAS Y AUTORIZADAS POR ROL (RF-02)

// 🍕 RUTAS EXCLUSIVAS DEL MESERO (Rol 2)


Route::post('/admin/receta/actualizar', [App\Http\Controllers\AdminController::class, 'actualizarReceta'])->name('admin.actualizar_receta');



// ==========================================
// MÓDULO ADMINISTRADOR (SUPERUSUARIO)
// ==========================================
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

// Rutas preparadas para cuando pulamos cada pestaña:
Route::post('/admin/personal/crear', [App\Http\Controllers\AdminController::class, 'crearPersonal'])->name('admin.crear_personal');
Route::post('/admin/personal/{id}/eliminar', [App\Http\Controllers\AdminController::class, 'eliminarPersonal'])->name('admin.eliminar_personal');
Route::post('/admin/menu/crear', [App\Http\Controllers\AdminController::class, 'crearProducto'])->name('admin.crear_producto');
Route::post('/admin/compras/registrar', [App\Http\Controllers\AdminController::class, 'registrarCompra'])->name('admin.registrar_compra');
Route::post('/admin/ajustes/guardar', [App\Http\Controllers\AdminController::class, 'guardarAjustes'])->name('admin.guardar_ajustes');



// RUTAS ADMIN: MENÚ Y RECETAS
Route::post('/admin/producto/crear', [App\Http\Controllers\AdminController::class, 'crearProducto'])->name('admin.crear_producto');
Route::post('/admin/producto/{id}/eliminar', [App\Http\Controllers\AdminController::class, 'eliminarProducto'])->name('admin.eliminar_producto');

// RUTAS ADMIN: ABASTECIMIENTO
Route::post('/admin/compras/registrar', [App\Http\Controllers\AdminController::class, 'registrarCompra'])->name('admin.registrar_compra');



// RUTAS PROTEGIDAS Y AUTORIZADAS POR ROL RF-02



Route::post('/admin/config/actualizar', [App\Http\Controllers\AdminController::class, 'actualizarConfiguracion'])->name('admin.actualizar_config');
Route::get('/admin/config/respaldo', [App\Http\Controllers\AdminController::class, 'respaldarBaseDatos'])->name('admin.respaldo_bd');


// RUTAS EXCLUSIVAS DEL MESERO Rol 2
Route::middleware([VerificarSesion::class.':2'])->group(function () {
    Route::get('/mesero/mesas', [MeseroController::class, 'index'])->name('mesero.mesas');
    Route::get('/mesero/pedido/{mesa_id}', [MeseroController::class, 'tomaPedido'])->name('mesero.pedido');
    Route::post('/mesero/pedido/{mesa_id}', [MeseroController::class, 'guardarPedido'])->name('mesero.guardar_pedido');
    Route::post('/mesero/mesas/{mesa_id}/limpiar', [MeseroController::class, 'limpiarMesa'])->name('mesero.limpiar_mesa');
    Route::get('/mesero/alertas', [MeseroController::class, 'checarAlertas'])->name('mesero.alertas');
});

//RUTAS EXCLUSIVAS DEL GERENTE (Rol 3)
// 📊 RUTAS EXCLUSIVAS DEL GERENTE (Rol 3)
Route::middleware([VerificarSesion::class.':3'])->group(function () {
    // 1. Dashboard Principal
    Route::get('/gerente/dashboard', [App\Http\Controllers\GerenteController::class, 'index'])->name('gerente.dashboard');
    
    // 2. Gestión de Personal (CRUD)
    Route::post('/gerente/personal/crear', [App\Http\Controllers\GerenteController::class, 'crearPersonal'])->name('gerente.crear_personal');
    Route::post('/gerente/usuario/{id}/estado', [App\Http\Controllers\GerenteController::class, 'alternarEstado'])->name('gerente.usuario.estado');
    Route::post('/gerente/usuario/{id}/password', [App\Http\Controllers\GerenteController::class, 'cambiarPassword'])->name('gerente.cambiar_password');
    Route::post('/gerente/usuario/{id}/comision', [App\Http\Controllers\GerenteController::class, 'cambiarComision'])->name('gerente.cambiar_comision');
    // Actualizar precio de menú
    Route::post('/gerente/producto/{id}/precio', [App\Http\Controllers\GerenteController::class, 'actualizarPrecio'])->name('gerente.actualizar_precio');
    // Procesar Corte de Caja
    Route::post('/gerente/caja/corte', [App\Http\Controllers\GerenteController::class, 'procesarCorte'])->name('gerente.corte_caja');
    // Actualizar Rol y Salario/Comisión del empleado
    Route::post('/gerente/usuario/{id}/actualizar', [App\Http\Controllers\GerenteController::class, 'actualizarEmpleado'])->name('gerente.actualizar_empleado');
});

// 👨‍🍳 RUTAS EXCLUSIVAS DE LA COCINA (Rol 4)




//RUTAS EXCLUSIVAS DE LA COCINA Rol 4
Route::middleware([VerificarSesion::class.':4'])->group(function () {
    Route::get('/cocina/kds', [CocinaController::class, 'index'])->name('cocina.kds');
    Route::post('/cocina/kds/listo/{pedido_id}', [CocinaController::class, 'marcarListo'])->name('cocina.marcar_listo');
});

// 💰 RUTAS EXCLUSIVAS DEL CAJERO (Rol 5)






Route::middleware([VerificarSesion::class.':5'])->group(function () {
    Route::get('/caja/ordenes', [CajaController::class, 'index'])->name('caja.ordenes');
    Route::post('/caja/pagar/{pedido_id}', [CajaController::class, 'procesarPago'])->name('caja.procesar_pago');
    Route::post('/caja/cancelar/{pedido_id}', [CajaController::class, 'cancelarPedido'])->name('caja.cancelar_pedido');
    Route::get('/caja/ticket/{pedido_id}', [CajaController::class, 'imprimirTicket'])->name('caja.imprimir_ticket');
});

