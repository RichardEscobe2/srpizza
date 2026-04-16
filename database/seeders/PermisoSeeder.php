<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('permiso')->insert([
        ['permiso_id' => 1, 'nombre_permiso' => 'GESTION_TOTAL', 'descripcion' => 'Permiso para hacer todo'],
        ['permiso_id' => 2, 'nombre_permiso' => 'TOMAR_PEDIDO', 'descripcion' => 'Crear y editar pedidos'],
        ['permiso_id' => 3, 'nombre_permiso' => 'COBRAR', 'descripcion' => 'Procesar pagos'],
        ['permiso_id' => 4, 'nombre_permiso' => 'VER_COCINA', 'descripcion' => 'Ver lista de preparación'],
        ['permiso_id' => 5, 'nombre_permiso' => 'VER_REPORTES', 'descripcion' => 'Ver ganancias e inventario'],
    ]);
}
}
