<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    // Apuntamos a la tabla exacta de tu archivo bd_actualizada
    protected $table = 'mesas';
    // Le indicamos cuál es tu llave primaria
    protected $primaryKey = 'mesa_id';
    // Apagamos los timestamps de Laravel
    public $timestamps = false;

    // Relación: Una mesa puede tener muchos pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'mesa_id', 'mesa_id');
    }
}