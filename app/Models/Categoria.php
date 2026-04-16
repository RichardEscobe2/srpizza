<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model {
    protected $table = 'categoria';
    protected $primaryKey = 'categoria_id';
    public $timestamps = false;
}