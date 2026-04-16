<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    DB::table('empleado')->insert([
        ['empleado_id' => 1, 'nombre_completo' => 'Carlos Dueñas', 'curp' => 'DUEC800101HDFR01', 'telefono' => '5512345678', 'puesto' => 'Administrador', 'activo' => 1, 'salario_base' => 1000.00, 'fecha_contratacion' => '2025-12-07'],
        ['empleado_id' => 2, 'nombre_completo' => 'Ricardo Escobedo', 'curp' => 'PERJ900202HDFR02', 'telefono' => '5587654321', 'puesto' => 'Mesero', 'activo' => 1, 'salario_base' => 1000.00, 'fecha_contratacion' => '2025-12-07'],
        ['empleado_id' => 3, 'nombre_completo' => 'Franciso Buendia', 'curp' => 'LOPM850303MDFR03', 'telefono' => '5511223344', 'puesto' => 'Gerente', 'activo' => 1, 'salario_base' => 1000.00, 'fecha_contratacion' => '2025-12-07'],
        ['empleado_id' => 4, 'nombre_completo' => 'Jonathan Fuentes', 'curp' => 'PICP700404HDFR04', 'telefono' => '5599887766', 'puesto' => 'Cocinero', 'activo' => 1, 'salario_base' => 1000.00, 'fecha_contratacion' => '2025-12-07'],
        ['empleado_id' => 5, 'nombre_completo' => 'Osvaldo Gonzalez', 'curp' => 'GARA950505MDFR05', 'telefono' => '5544332211', 'puesto' => 'Cajero', 'activo' => 1, 'salario_base' => 1000.00, 'fecha_contratacion' => '2025-12-07'],
        ['empleado_id' => 6, 'nombre_completo' => 'Arturo Escobedo', 'curp' => null, 'telefono' => '7716835297', 'puesto' => 'Mesero', 'activo' => 1, 'salario_base' => 0.00, 'fecha_contratacion' => '2026-04-10'],
    ]);
}
}
