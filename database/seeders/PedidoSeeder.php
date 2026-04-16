<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    DB::table('pedido')->insert([
        ['pedido_id' => 1, 'usuario_id' => 2, 'mesa_id' => 1, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 06:09:43', 'estado' => 'Pagado', 'total' => 1121.72, 'propina' => 0.00],
        ['pedido_id' => 2, 'usuario_id' => 2, 'mesa_id' => 3, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 06:10:12', 'estado' => 'Pagado', 'total' => 249.40, 'propina' => 0.00],
        ['pedido_id' => 3, 'usuario_id' => 2, 'mesa_id' => 2, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 06:18:47', 'estado' => 'Pagado', 'total' => 393.24, 'propina' => 0.00],
        ['pedido_id' => 4, 'usuario_id' => 2, 'mesa_id' => 7, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 06:19:25', 'estado' => 'Cancelado', 'total' => 159.00, 'propina' => 0.00],
        ['pedido_id' => 5, 'usuario_id' => 2, 'mesa_id' => 4, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 06:28:59', 'estado' => 'Pagado', 'total' => 208.80, 'propina' => 0.00],
        ['pedido_id' => 6, 'usuario_id' => 2, 'mesa_id' => 5, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 06:49:21', 'estado' => 'Pagado', 'total' => 184.44, 'propina' => 0.00],
        ['pedido_id' => 7, 'usuario_id' => 2, 'mesa_id' => 6, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 08:09:00', 'estado' => 'Pagado', 'total' => 184.44, 'propina' => 0.00],
        ['pedido_id' => 8, 'usuario_id' => 2, 'mesa_id' => 7, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:05:52', 'estado' => 'Pagado', 'total' => 368.88, 'propina' => 0.00],
        ['pedido_id' => 9, 'usuario_id' => 2, 'mesa_id' => 6, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:06:42', 'estado' => 'Pagado', 'total' => 368.88, 'propina' => 0.00],
        ['pedido_id' => 10, 'usuario_id' => 2, 'mesa_id' => 1, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:06:50', 'estado' => 'Pagado', 'total' => 341.04, 'propina' => 0.00],
        ['pedido_id' => 11, 'usuario_id' => 2, 'mesa_id' => 2, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:10:26', 'estado' => 'Pagado', 'total' => 220.40, 'propina' => 0.00],
        ['pedido_id' => 12, 'usuario_id' => 2, 'mesa_id' => 3, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:10:58', 'estado' => 'Pagado', 'total' => 156.60, 'propina' => 0.00],
        ['pedido_id' => 13, 'usuario_id' => 2, 'mesa_id' => 1, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:21:24', 'estado' => 'Pagado', 'total' => 335.24, 'propina' => 0.00],
        ['pedido_id' => 14, 'usuario_id' => 2, 'mesa_id' => 6, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:21:59', 'estado' => 'Pagado', 'total' => 220.40, 'propina' => 0.00],
        ['pedido_id' => 15, 'usuario_id' => 2, 'mesa_id' => 10, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:25:47', 'estado' => 'Pagado', 'total' => 564.92, 'propina' => 0.00],
        ['pedido_id' => 16, 'usuario_id' => 2, 'mesa_id' => 9, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:26:23', 'estado' => 'Pagado', 'total' => 184.44, 'propina' => 0.00],
        ['pedido_id' => 17, 'usuario_id' => 2, 'mesa_id' => 2, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:26:30', 'estado' => 'Pagado', 'total' => 184.44, 'propina' => 0.00],
        ['pedido_id' => 18, 'usuario_id' => 2, 'mesa_id' => 7, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 09:34:29', 'estado' => 'Pagado', 'total' => 184.44, 'propina' => 0.00],
        ['pedido_id' => 19, 'usuario_id' => 2, 'mesa_id' => 5, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 10:20:24', 'estado' => 'Pagado', 'total' => 220.40, 'propina' => 0.00],
        ['pedido_id' => 20, 'usuario_id' => 2, 'mesa_id' => 1, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 23:07:48', 'estado' => 'Pagado', 'total' => 429.20, 'propina' => 0.00],
        ['pedido_id' => 21, 'usuario_id' => 2, 'mesa_id' => 1, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 23:08:53', 'estado' => 'Pagado', 'total' => 220.40, 'propina' => 0.00],
        ['pedido_id' => 22, 'usuario_id' => 2, 'mesa_id' => 3, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-08 23:11:03', 'estado' => 'Pagado', 'total' => 121.22, 'propina' => 0.00],
        ['pedido_id' => 23, 'usuario_id' => 2, 'mesa_id' => 1, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-09 03:04:47', 'estado' => 'Pagado', 'total' => 150.80, 'propina' => 0.00],
        ['pedido_id' => 24, 'usuario_id' => 2, 'mesa_id' => 1, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-09 03:05:13', 'estado' => 'Pagado', 'total' => 220.40, 'propina' => 0.00],
        ['pedido_id' => 25, 'usuario_id' => 2, 'mesa_id' => 2, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-10 10:39:23', 'estado' => 'Pagado', 'total' => 220.40, 'propina' => 0.00],
        ['pedido_id' => 26, 'usuario_id' => 2, 'mesa_id' => 1, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-10 10:52:13', 'estado' => 'Listo', 'total' => 60.00, 'propina' => 0.00],
        ['pedido_id' => 27, 'usuario_id' => 2, 'mesa_id' => 2, 'cliente_nombre' => null, 'fecha_hora' => '2026-04-10 10:52:19', 'estado' => 'Listo', 'total' => 190.00, 'propina' => 0.00],
    ]);
}
}
