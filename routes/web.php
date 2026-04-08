<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeseroController;
use App\Http\Middleware\VerificarSesion; 

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

});