<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('mesa')->insert([
        ['mesa_id' => 1, 'numero_mesa' => '1', 'capacidad' => '4', 'estado' => 'Ocupada'],
        ['mesa_id' => 2, 'numero_mesa' => '2', 'capacidad' => '4', 'estado' => 'Ocupada'],
        ['mesa_id' => 3, 'numero_mesa' => '3', 'capacidad' => '2', 'estado' => 'Disponible'],
        ['mesa_id' => 4, 'numero_mesa' => '4', 'capacidad' => '6', 'estado' => 'Disponible'],
        ['mesa_id' => 5, 'numero_mesa' => '5', 'capacidad' => '8', 'estado' => 'Disponible'],
        ['mesa_id' => 6, 'numero_mesa' => '6', 'capacidad' => '4', 'estado' => 'Disponible'],
        ['mesa_id' => 7, 'numero_mesa' => '7', 'capacidad' => '4', 'estado' => 'Disponible'],
        ['mesa_id' => 8, 'numero_mesa' => '8', 'capacidad' => '6', 'estado' => 'Ocupada'],
        ['mesa_id' => 9, 'numero_mesa' => '9', 'capacidad' => '2', 'estado' => 'Disponible'],
        ['mesa_id' => 10, 'numero_mesa' => '10', 'capacidad' => '8', 'estado' => 'Disponible'],
    ]);
}
}
