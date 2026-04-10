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
















// RUTAS PROTEGIDAS Y AUTORIZADAS POR ROL RF-02






// RUTAS EXCLUSIVAS DEL MESERO Rol 2
Route::middleware([VerificarSesion::class.':2'])->group(function () {
    Route::get('/mesero/mesas', [MeseroController::class, 'index'])->name('mesero.mesas');
    Route::get('/mesero/pedido/{mesa_id}', [MeseroController::class, 'tomaPedido'])->name('mesero.pedido');
    Route::post('/mesero/pedido/{mesa_id}', [MeseroController::class, 'guardarPedido'])->name('mesero.guardar_pedido');
    Route::post('/mesero/mesas/{mesa_id}/limpiar', [MeseroController::class, 'limpiarMesa'])->name('mesero.limpiar_mesa');
    Route::get('/mesero/alertas', [MeseroController::class, 'checarAlertas'])->name('mesero.alertas');
});




// RUTAS EXCLUSIVAS DEL GERENTE Rol 3
Route::middleware([VerificarSesion::class.':3'])->group(function () {
    Route::get('/gerente/dashboard', [GerenteController::class, 'index'])->name('gerente.dashboard');
    Route::post('/gerente/usuario/{id}/estado', [GerenteController::class, 'alternarEstado'])->name('gerente.usuario_estado');
    Route::post('/gerente/usuario/{id}/password', [GerenteController::class, 'cambiarPassword'])->name('gerente.usuario_password');
     Route::post('/gerente/personal/crear', [GerenteController::class, 'crearPersonal'])->name('gerente.crear_personal');
    Route::post('/gerente/usuario/{id}/comision', [GerenteController::class, 'cambiarComision'])->name('gerente.usuario_comision');
});





//RUTAS EXCLUSIVAS DE LA COCINA Rol 4
Route::middleware([VerificarSesion::class.':4'])->group(function () {
    Route::get('/cocina/kds', [CocinaController::class, 'index'])->name('cocina.kds');
    Route::post('/cocina/kds/listo/{pedido_id}', [CocinaController::class, 'marcarListo'])->name('cocina.marcar_listo');
});




