<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'producto_id';
    
    public $timestamps = false; 

    protected $fillable = [
        'categoria_id', 
        'nombre', 
        'precio', 
        'tamano', 
        'es_preparado', 
        'activo', 
        'es_recomendado'
    ]; 
}