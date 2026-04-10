<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalles_pedido';
    protected $primaryKey = 'detalle_id'; // Assuming 'detalle_id' is the primary key based on naming conventions. If not, this might need adjustment.
    public $timestamps = false;

    // Relación: Un detalle pertenece a un pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id', 'pedido_id');
    }

    // Relación: Un detalle hace referencia a un producto del menú
    public function Producto()
    {
        return $this->belongsTo(Menu::class, 'producto_id', 'producto_id');
    }
}