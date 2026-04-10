<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
  protected $table = 'pedidos';
    protected $primaryKey = 'pedido_id';
    public $timestamps = false;

    // Relación: Un pedido pertenece a una mesa
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id', 'mesa_id');
    }

    // Relación: Un pedido fue atendido por un usuario (mesero)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }

    // Relación: Un pedido tiene muchos detalles (platillos)
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id', 'pedido_id');
    }
}