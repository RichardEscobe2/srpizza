<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeseroController;
use App\Http\Middleware\VerificarSesion; 
use App\Http\Controllers\CocinaController;
use App\Http\Controllers\CajaController;

// RUTAS PÚBLICAS (No requieren sesión)

Route::get('/', function () { return redirect('/login'); });
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



// RUTAS PROTEGIDAS (Requieren inicio de sesión obligatorio)

Route::middleware([VerificarSesion::class])->group(function () {
    
    // RUTAS DEL MESERO
    Route::get('/mesero/mesas', [MeseroController::class, 'index'])->name('mesero.mesas');
    Route::get('/mesero/pedido/{mesa_id}', [MeseroController::class, 'tomaPedido'])->name('mesero.pedido');
    Route::post('/mesero/pedido/{mesa_id}', [MeseroController::class, 'guardarPedido'])->name('mesero.guardar_pedido');
    Route::post('/mesero/mesas/{mesa_id}/limpiar', [MeseroController::class, 'limpiarMesa'])->name('mesero.limpiar_mesa');
    // RUTAS DE LA COCINA (KDS)
    Route::get('/cocina/kds', [CocinaController::class, 'index'])->name('cocina.kds');
    Route::post('/cocina/kds/listo/{pedido_id}', [CocinaController::class, 'marcarListo'])->name('cocina.marcar_listo');


     // RUTAS DEL CAJERO (Punto de Venta)
    Route::get('/caja/ordenes', [CajaController::class, 'index'])->name('caja.ordenes');
    Route::post('/caja/pagar/{pedido_id}', [CajaController::class, 'procesarPago'])->name('caja.procesar_pago');
    Route::post('/caja/cancelar/{pedido_id}', [CajaController::class, 'cancelarPedido'])->name('caja.cancelar_pedido');
    Route::get('/caja/ticket/{pedido_id}', [CajaController::class, 'imprimirTicket'])->name('caja.imprimir_ticket');
});