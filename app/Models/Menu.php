<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'menu'; // 
    protected $primaryKey = 'producto_id'; // [cite: 67, 206]
    public $timestamps = false;

    protected $fillable = [
        'categoria_id', 'nombre', 'precio', 'tamano', 'es_preparado', 'activo'
    ];
}