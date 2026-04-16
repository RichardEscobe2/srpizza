<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
{
    DB::table('detalle')->insert([
        ['detalle_id' => 1, 'pedido_id' => 1, 'producto_id' => 3, 'cantidad' => 1, 'precio_unitario' => 130.00, 'comentarios' => null],
        ['detalle_id' => 2, 'pedido_id' => 1, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 3, 'pedido_id' => 1, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 4, 'pedido_id' => 2, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
        ['detalle_id' => 5, 'pedido_id' => 3, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 6, 'pedido_id' => 4, 'producto_id' => 18, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 7, 'pedido_id' => 1, 'producto_id' => 2, 'cantidad' => 1, 'precio_unitario' => 180.00, 'comentarios' => null],
        ['detalle_id' => 8, 'pedido_id' => 1, 'producto_id' => 2, 'cantidad' => 1, 'precio_unitario' => 180.00, 'comentarios' => null],
        ['detalle_id' => 9, 'pedido_id' => 3, 'producto_id' => 2, 'cantidad' => 1, 'precio_unitario' => 180.00, 'comentarios' => null],
        ['detalle_id' => 10, 'pedido_id' => 5, 'producto_id' => 2, 'cantidad' => 1, 'precio_unitario' => 180.00, 'comentarios' => null],
        ['detalle_id' => 11, 'pedido_id' => 2, 'producto_id' => 13, 'cantidad' => 1, 'precio_unitario' => 25.00, 'comentarios' => null],
        ['detalle_id' => 12, 'pedido_id' => 6, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => 'fd43'],
        ['detalle_id' => 13, 'pedido_id' => 1, 'producto_id' => 21, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => 'xcszcsdcsdds'],
        ['detalle_id' => 14, 'pedido_id' => 7, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 15, 'pedido_id' => 8, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 16, 'pedido_id' => 8, 'producto_id' => 21, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 17, 'pedido_id' => 9, 'producto_id' => 20, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 18, 'pedido_id' => 9, 'producto_id' => 24, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 19, 'pedido_id' => 10, 'producto_id' => 19, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 20, 'pedido_id' => 10, 'producto_id' => 7, 'cantidad' => 1, 'precio_unitario' => 135.00, 'comentarios' => null],
        ['detalle_id' => 21, 'pedido_id' => 11, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
        ['detalle_id' => 22, 'pedido_id' => 12, 'producto_id' => 7, 'cantidad' => 1, 'precio_unitario' => 135.00, 'comentarios' => null],
        ['detalle_id' => 23, 'pedido_id' => 13, 'producto_id' => 3, 'cantidad' => 1, 'precio_unitario' => 130.00, 'comentarios' => null],
        ['detalle_id' => 24, 'pedido_id' => 13, 'producto_id' => 19, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 25, 'pedido_id' => 14, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
        ['detalle_id' => 26, 'pedido_id' => 15, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 27, 'pedido_id' => 15, 'producto_id' => 12, 'cantidad' => 1, 'precio_unitario' => 109.00, 'comentarios' => null],
        ['detalle_id' => 28, 'pedido_id' => 15, 'producto_id' => 5, 'cantidad' => 1, 'precio_unitario' => 60.00, 'comentarios' => null],
        ['detalle_id' => 29, 'pedido_id' => 15, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 30, 'pedido_id' => 16, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 31, 'pedido_id' => 17, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 32, 'pedido_id' => 18, 'producto_id' => 17, 'cantidad' => 1, 'precio_unitario' => 159.00, 'comentarios' => null],
        ['detalle_id' => 33, 'pedido_id' => 19, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
        ['detalle_id' => 34, 'pedido_id' => 20, 'producto_id' => 2, 'cantidad' => 1, 'precio_unitario' => 180.00, 'comentarios' => null],
        ['detalle_id' => 35, 'pedido_id' => 20, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
        ['detalle_id' => 36, 'pedido_id' => 21, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
        ['detalle_id' => 37, 'pedido_id' => 22, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
        ['detalle_id' => 38, 'pedido_id' => 23, 'producto_id' => 3, 'cantidad' => 1, 'precio_unitario' => 130.00, 'comentarios' => null],
        ['detalle_id' => 39, 'pedido_id' => 24, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
        ['detalle_id' => 40, 'pedido_id' => 25, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
        ['detalle_id' => 41, 'pedido_id' => 26, 'producto_id' => 5, 'cantidad' => 1, 'precio_unitario' => 60.00, 'comentarios' => null],
        ['detalle_id' => 42, 'pedido_id' => 27, 'producto_id' => 4, 'cantidad' => 1, 'precio_unitario' => 190.00, 'comentarios' => null],
    ]);
}
}
