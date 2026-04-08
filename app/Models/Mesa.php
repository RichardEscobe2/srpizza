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
}