<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('config')->insert([
        [
            'config_id' => 1, 
            'nombre_empresa' => 'Sr. Pizza', 
            'direccion' => null, 
            'ruta_logo' => null, 
            'mensaje_ticket' => '¡Gracias por su compra!', 
            'iva' => 0.16
        ],
    ]);
}
}
