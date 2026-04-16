<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('categoria')->insert([
        ['categoria_id' => 1, 'nombre' => 'Pizzas', 'descripcion' => 'Pizzas artesanales al horno'],
        ['categoria_id' => 2, 'nombre' => 'Bebidas', 'descripcion' => 'Refrescos, aguas y cervezas'],
        ['categoria_id' => 3, 'nombre' => 'Entradas', 'descripcion' => 'Complementos para iniciar'],
        ['categoria_id' => 4, 'nombre' => 'Hamburguesas', 'descripcion' => 'Hamburguesas al carbon con papas'],
        ['categoria_id' => 5, 'nombre' => 'Complementos', 'descripcion' => 'Alitas, Boneless, Costillas y Snacks'],
    ]);
}
}
