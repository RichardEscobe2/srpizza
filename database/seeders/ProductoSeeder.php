<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    DB::table('producto')->insert([
        ['producto_id' => 1, 'categoria_id' => 1, 'nombre' => 'Pizza Pepperoni', 'precio' => 300.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 0, 'es_recomendado' => 0],
        ['producto_id' => 2, 'categoria_id' => 1, 'nombre' => 'Pizza Pepperoni', 'precio' => 180.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 3, 'categoria_id' => 1, 'nombre' => 'Pizza Hawaiana', 'precio' => 130.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 4, 'categoria_id' => 1, 'nombre' => 'Pizza Mexicana', 'precio' => 190.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 5, 'categoria_id' => 3, 'nombre' => 'Papas a la Francesa', 'precio' => 60.00, 'tamano' => 'Estandar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 6, 'categoria_id' => 5, 'nombre' => 'Boneless', 'precio' => 109.00, 'tamano' => 'Estandar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 7, 'categoria_id' => 5, 'nombre' => 'Costillas BBQ', 'precio' => 135.00, 'tamano' => 'Estandar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 8, 'categoria_id' => 5, 'nombre' => 'Aros de Cebolla', 'precio' => 79.00, 'tamano' => 'Estandar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 9, 'categoria_id' => 5, 'nombre' => 'Dedos de Queso', 'precio' => 109.00, 'tamano' => 'Estandar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 10, 'categoria_id' => 5, 'nombre' => 'Alitas', 'precio' => 140.00, 'tamano' => 'Estandar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 11, 'categoria_id' => 4, 'nombre' => 'Shot Burger', 'precio' => 89.00, 'tamano' => 'Estandar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 12, 'categoria_id' => 4, 'nombre' => 'Big Shot', 'precio' => 109.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 13, 'categoria_id' => 2, 'nombre' => 'Coca-Cola 600ml', 'precio' => 25.00, 'tamano' => 'Estandar', 'es_preparado' => 0, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 14, 'categoria_id' => 2, 'nombre' => 'Cerveza Nacional', 'precio' => 45.00, 'tamano' => 'Estandar', 'es_preparado' => 0, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 15, 'categoria_id' => 2, 'nombre' => 'Refresco 2 Litros', 'precio' => 49.00, 'tamano' => 'Familiar', 'es_preparado' => 0, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 16, 'categoria_id' => 2, 'nombre' => 'Jugo del Valle', 'precio' => 25.00, 'tamano' => 'Estandar', 'es_preparado' => 0, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 17, 'categoria_id' => 1, 'nombre' => 'Pizza Carnes Frias', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 18, 'categoria_id' => 1, 'nombre' => 'Pizza Italiana', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 19, 'categoria_id' => 1, 'nombre' => 'Pizza Tapatia', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 20, 'categoria_id' => 1, 'nombre' => 'Pizza Especial', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 21, 'categoria_id' => 1, 'nombre' => 'Pizza Jarocha', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 22, 'categoria_id' => 1, 'nombre' => 'Pizza BBQ', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 23, 'categoria_id' => 1, 'nombre' => 'Pizza Cochinita Pibil', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 24, 'categoria_id' => 1, 'nombre' => 'Pizza Pastor', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 25, 'categoria_id' => 1, 'nombre' => 'Pizza Vegetales', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 26, 'categoria_id' => 1, 'nombre' => 'Pizza Americana', 'precio' => 159.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 27, 'categoria_id' => 1, 'nombre' => 'Pizza Pepperoni', 'precio' => 80.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 28, 'categoria_id' => 1, 'nombre' => 'Pizza Pepperoni', 'precio' => 150.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 29, 'categoria_id' => 1, 'nombre' => 'Pizza Hawaiana', 'precio' => 90.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 30, 'categoria_id' => 1, 'nombre' => 'Pizza Hawaiana', 'precio' => 160.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 31, 'categoria_id' => 1, 'nombre' => 'Pizza Hawaiana', 'precio' => 190.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 32, 'categoria_id' => 1, 'nombre' => 'Pizza Mexicana', 'precio' => 95.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 33, 'categoria_id' => 1, 'nombre' => 'Pizza Mexicana', 'precio' => 135.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 34, 'categoria_id' => 1, 'nombre' => 'Pizza Mexicana', 'precio' => 165.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 35, 'categoria_id' => 1, 'nombre' => 'Pizza Carnes Frias', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 36, 'categoria_id' => 1, 'nombre' => 'Pizza Carnes Frias', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 37, 'categoria_id' => 1, 'nombre' => 'Pizza Carnes Frias', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 38, 'categoria_id' => 1, 'nombre' => 'Pizza Italiana', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 39, 'categoria_id' => 1, 'nombre' => 'Pizza Italiana', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 40, 'categoria_id' => 1, 'nombre' => 'Pizza Italiana', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 41, 'categoria_id' => 1, 'nombre' => 'Pizza Tapatia', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 42, 'categoria_id' => 1, 'nombre' => 'Pizza Tapatia', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 43, 'categoria_id' => 1, 'nombre' => 'Pizza Tapatia', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 44, 'categoria_id' => 1, 'nombre' => 'Pizza Especial', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 45, 'categoria_id' => 1, 'nombre' => 'Pizza Especial', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 46, 'categoria_id' => 1, 'nombre' => 'Pizza Especial', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 47, 'categoria_id' => 1, 'nombre' => 'Pizza Jarocha', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 48, 'categoria_id' => 1, 'nombre' => 'Pizza Jarocha', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 49, 'categoria_id' => 1, 'nombre' => 'Pizza Jarocha', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 50, 'categoria_id' => 1, 'nombre' => 'Pizza BBQ', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 51, 'categoria_id' => 1, 'nombre' => 'Pizza BBQ', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 52, 'categoria_id' => 1, 'nombre' => 'Pizza BBQ', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 53, 'categoria_id' => 1, 'nombre' => 'Pizza Cochinita Pibil', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 54, 'categoria_id' => 1, 'nombre' => 'Pizza Cochinita Pibil', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 55, 'categoria_id' => 1, 'nombre' => 'Pizza Cochinita Pibil', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 56, 'categoria_id' => 1, 'nombre' => 'Pizza Pastor', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 57, 'categoria_id' => 1, 'nombre' => 'Pizza Pastor', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 58, 'categoria_id' => 1, 'nombre' => 'Pizza Pastor', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 59, 'categoria_id' => 1, 'nombre' => 'Pizza Vegetales', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 60, 'categoria_id' => 1, 'nombre' => 'Pizza Vegetales', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 61, 'categoria_id' => 1, 'nombre' => 'Pizza Vegetales', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 62, 'categoria_id' => 1, 'nombre' => 'Pizza Americana', 'precio' => 99.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 63, 'categoria_id' => 1, 'nombre' => 'Pizza Americana', 'precio' => 139.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 64, 'categoria_id' => 1, 'nombre' => 'Pizza Americana', 'precio' => 199.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 65, 'categoria_id' => 5, 'nombre' => 'Boneless', 'precio' => 199.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 66, 'categoria_id' => 5, 'nombre' => 'Boneless', 'precio' => 350.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 67, 'categoria_id' => 5, 'nombre' => 'Alitas', 'precio' => 190.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 68, 'categoria_id' => 5, 'nombre' => 'Alitas', 'precio' => 280.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 69, 'categoria_id' => 5, 'nombre' => 'Costillas BBQ', 'precio' => 260.00, 'tamano' => 'Familiar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 70, 'categoria_id' => 5, 'nombre' => 'Dedos de Queso', 'precio' => 180.00, 'tamano' => 'Grande', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 71, 'categoria_id' => 1, 'nombre' => 'Pizza Pepperoni', 'precio' => 120.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 72, 'categoria_id' => 2, 'nombre' => 'Frappe de Cafe', 'precio' => 65.00, 'tamano' => 'Estandar', 'es_preparado' => 1, 'activo' => 1, 'es_recomendado' => 0],
        ['producto_id' => 73, 'categoria_id' => 2, 'nombre' => 'Cerveza XX', 'precio' => 78.00, 'tamano' => 'Mediana', 'es_preparado' => 1, 'activo' => 0, 'es_recomendado' => 0],
        ['producto_id' => 74, 'categoria_id' => 2, 'nombre' => 'Carajillo', 'precio' => 110.00, 'tamano' => 'Chica', 'es_preparado' => 1, 'activo' => 0, 'es_recomendado' => 0],
    ]);
}
}
