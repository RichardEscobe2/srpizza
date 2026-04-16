<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    DB::table('usuario')->insert([
        ['id_usuario' => 1, 'empleado_id' => 1, 'nombre_completo' => 'Carlos Dueñas', 'matricula' => 1001, 'contrasena' => '111', 'id_rol' => 1, 'activo' => 1, 'limite_descuento' => 100.00, 'porcentaje_comision' => 5.00],
        ['id_usuario' => 2, 'empleado_id' => 2, 'nombre_completo' => 'Ricardo Escobedo', 'matricula' => 1002, 'contrasena' => '$2y$12$KwTxqhr9NhTzhN.cyXW3Euwl0fwKRJ.HGaIv5bfOjtWq2RWfoArHC', 'id_rol' => 2, 'activo' => 1, 'limite_descuento' => 0.00, 'porcentaje_comision' => 5.00],
        ['id_usuario' => 3, 'empleado_id' => 3, 'nombre_completo' => 'Franciso Buendia', 'matricula' => 1003, 'contrasena' => '$2y$12$kUbTQD.7PtFOLaAUQZHaA.PHkDC1plhdnurtgnhAOY1ACCcJJ4b1S', 'id_rol' => 3, 'activo' => 1, 'limite_descuento' => 70.00, 'porcentaje_comision' => 0.00],
        ['id_usuario' => 4, 'empleado_id' => 4, 'nombre_completo' => 'Jonathan Fuentes', 'matricula' => 1004, 'contrasena' => '$2y$12$aCfHJ7EY2Pdod6tfIF4j4OpKOKkgODA0ATNCBz/xLCPdVy4dfaLsy', 'id_rol' => 4, 'activo' => 1, 'limite_descuento' => 0.00, 'porcentaje_comision' => 0.00],
        ['id_usuario' => 5, 'empleado_id' => 5, 'nombre_completo' => 'Osvaldo Gonzalez', 'matricula' => 1005, 'contrasena' => '$2y$12$pI9C0rLnKKQWK./zFvJaPuzxd8eVPl6Aot1PMqNvj0TKq.IVgPsO.', 'id_rol' => 5, 'activo' => 1, 'limite_descuento' => 45.00, 'porcentaje_comision' => 5.00],
        ['id_usuario' => 6, 'empleado_id' => 6, 'nombre_completo' => 'Arturo Escobedo', 'matricula' => 1006, 'contrasena' => '$2y$12$sB8staAwUBoYrIw.4Z/9aeGJVJeL.h1kwx3QJ5xfRUyZsELwsMwaO', 'id_rol' => 2, 'activo' => 1, 'limite_descuento' => 0.00, 'porcentaje_comision' => 0.00],
    ]);
}
}
