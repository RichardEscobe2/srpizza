<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
   // 1. Decirle a Laravel cuál es tu tabla exacta
    protected $table = 'usuario';

    // 2. Decirle a Laravel cuál es tu llave primaria (Si no pones esto, Laravel buscará 'id')
    protected $primaryKey = 'id_usuario';

    // 3. Las columnas que se pueden llenar
    protected $fillable = [
        'empleado_id', 
        'id_rol', 
        'nombre_usuario', 
        'password', 
        'activo'
    ];

    // Relación: Un usuario (mesero) ha tomado muchos pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id', 'id_usuario');
    }
}