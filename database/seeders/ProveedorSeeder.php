<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('proveedor')->insert([
        ['proveedor_id' => 1, 'empresa' => 'Abastos Pizzería S.A.', 'contacto_nombre' => 'Roberto Gomez', 'telefono' => '5511112222', 'email' => null, 'direccion' => null, 'activo' => 1],
        ['proveedor_id' => 2, 'empresa' => 'Coca-Cola FEMSA', 'contacto_nombre' => 'Ventas Zona Norte', 'telefono' => '8001234567', 'email' => null, 'direccion' => null, 'activo' => 1],
        ['proveedor_id' => 3, 'empresa' => 'Cervecería Modelo', 'contacto_nombre' => 'Distribución', 'telefono' => '8009998888', 'email' => null, 'direccion' => null, 'activo' => 1],
    ]);
}
}
