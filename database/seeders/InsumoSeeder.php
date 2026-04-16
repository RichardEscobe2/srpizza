<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class InsumoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    DB::table('insumo')->insert([
        ['insumo_id' => 1, 'nombre' => 'Harina de Trigo', 'stock_actual' => 91.300, 'stock_minimo' => 10.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 2, 'nombre' => 'Queso Mozzarella', 'stock_actual' => 93.100, 'stock_minimo' => 5.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 3, 'nombre' => 'Salsa de Tomate', 'stock_actual' => 111.220, 'stock_minimo' => 3.000, 'unidad_medida' => 'lt', 'costo_unitario' => 20.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 4, 'nombre' => 'Pepperoni', 'stock_actual' => 98.770, 'stock_minimo' => 2.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 5, 'nombre' => 'Jamón', 'stock_actual' => 6.620, 'stock_minimo' => 2.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 6, 'nombre' => 'Piña en Almíbar', 'stock_actual' => 9.580, 'stock_minimo' => 2.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 7, 'nombre' => 'Chorizo', 'stock_actual' => 98.650, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 8, 'nombre' => 'Coca-Cola 600ml', 'stock_actual' => 100.000, 'stock_minimo' => 12.000, 'unidad_medida' => 'pza', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 9, 'nombre' => 'Cerveza Nacional', 'stock_actual' => 24.000, 'stock_minimo' => 12.000, 'unidad_medida' => 'pza', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 10, 'nombre' => 'Papa congelada', 'stock_actual' => 9.400, 'stock_minimo' => 2.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 11, 'nombre' => 'Pan de Hamburguesa', 'stock_actual' => 38.000, 'stock_minimo' => 10.000, 'unidad_medida' => 'pza', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 12, 'nombre' => 'Carne de Res', 'stock_actual' => 9.310, 'stock_minimo' => 2.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 13, 'nombre' => 'Lechuga', 'stock_actual' => 99.950, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 14, 'nombre' => 'Jitomate', 'stock_actual' => 4.900, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 15, 'nombre' => 'Cebolla', 'stock_actual' => 2.930, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 16, 'nombre' => 'Queso Amarillo', 'stock_actual' => 2.940, 'stock_minimo' => 0.500, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 17, 'nombre' => 'Pechuga Pollo (Boneless)', 'stock_actual' => 10.000, 'stock_minimo' => 2.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 18, 'nombre' => 'Alitas de Pollo', 'stock_actual' => 10.000, 'stock_minimo' => 3.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 19, 'nombre' => 'Costilla de Cerdo', 'stock_actual' => 7.400, 'stock_minimo' => 2.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 20, 'nombre' => 'Salsa BBQ', 'stock_actual' => 9.900, 'stock_minimo' => 2.000, 'unidad_medida' => 'lt', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 21, 'nombre' => 'Salsa Buffalo', 'stock_actual' => 5.000, 'stock_minimo' => 1.000, 'unidad_medida' => 'lt', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 22, 'nombre' => 'Salsa Mango Habanero', 'stock_actual' => 5.000, 'stock_minimo' => 1.000, 'unidad_medida' => 'lt', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 23, 'nombre' => 'Aderezo Ranch', 'stock_actual' => 5.000, 'stock_minimo' => 1.000, 'unidad_medida' => 'lt', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 24, 'nombre' => 'Aros de Cebolla', 'stock_actual' => 5.000, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 25, 'nombre' => 'Dedos de Queso', 'stock_actual' => 5.000, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 26, 'nombre' => 'Carne al Pastor', 'stock_actual' => 4.880, 'stock_minimo' => 0.700, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 27, 'nombre' => 'Cochinita Pibil', 'stock_actual' => 5.000, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 28, 'nombre' => 'Atun', 'stock_actual' => 2.760, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 29, 'nombre' => 'Tocino', 'stock_actual' => 2.040, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 30, 'nombre' => 'Pimiento Verde', 'stock_actual' => 0.650, 'stock_minimo' => 0.500, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 31, 'nombre' => 'arrachera', 'stock_actual' => 0.000, 'stock_minimo' => 2.000, 'unidad_medida' => 'ml', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 32, 'nombre' => 'sesina', 'stock_actual' => 10.000, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 157.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 33, 'nombre' => 'pepinos', 'stock_actual' => 0.000, 'stock_minimo' => 10.000, 'unidad_medida' => 'pza', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
        ['insumo_id' => 34, 'nombre' => 'cerezas', 'stock_actual' => 0.000, 'stock_minimo' => 1.000, 'unidad_medida' => 'kg', 'costo_unitario' => 0.00, 'costo_promedio' => 0.00],
    ]);
}
}
