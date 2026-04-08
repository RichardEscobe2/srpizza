<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
    protected $table = 'menu';
    protected $primaryKey = 'producto_id';
    public $timestamps = false;
}