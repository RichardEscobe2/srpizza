<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('rol')->insert([
        ['rol_id' => 1, 'nombre_rol' => 'Administrador', 'descripcion' => 'Acceso total al sistema'],
        ['rol_id' => 2, 'nombre_rol' => 'Mesero', 'descripcion' => 'Atención a mesas y toma de pedidos'],
        ['rol_id' => 3, 'nombre_rol' => 'Gerente', 'descripcion' => 'Supervisión de personal y cortes de caja'],
        ['rol_id' => 4, 'nombre_rol' => 'Cocinero', 'descripcion' => 'Visualización de comandas y gestión de recetas'],
        ['rol_id' => 5, 'nombre_rol' => 'Cajero', 'descripcion' => 'Cobro de cuentas y cierre de ventas'],
    ]);
}
}
