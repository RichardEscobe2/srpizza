<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // 1. Indica el nombre correcto de la tabla 
    protected $table = 'usuarios';

    // 2. Indica la llave primaria correcta 
    protected $primaryKey = 'id_usuario';

    // 3. Si tu tabla no tiene las columnas 'created_at' y 'updated_at'
    public $timestamps = false;

    protected $fillable = [
        'empleado_id', 'nombre_completo', 'matricula', 'contrasena', 'id_rol', 'activo'
    ];

    // Importante para que Auth::attempt funcione con tu columna 'contrasena'
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}