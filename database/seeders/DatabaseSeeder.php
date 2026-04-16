<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
   public function run(): void
{
    // Nivel 1
    $this->call(CategoriaSeeder::class);
    $this->call(ConfigSeeder::class);
    $this->call(EmpleadoSeeder::class);
    $this->call(InsumoSeeder::class);
    $this->call(MesaSeeder::class);
    $this->call(PermisoSeeder::class);
    $this->call(ProveedorSeeder::class);
    $this->call(RolSeeder::class);

    // Nivel 2
    $this->call(ProductoSeeder::class);
    $this->call(RolPermisoSeeder::class);
    $this->call(UsuarioSeeder::class);

    // Nivel 3
    $this->call(PedidoSeeder::class);
    $this->call(CompraSeeder::class);
    $this->call(DetalleSeeder::class);
    $this->call(DetalleCompraSeeder::class);
    $this->call(RecetaSeeder::class);
    $this->call(MermaSeeder::class);
    $this->call(CorteSeeder::class);
    $this->call(BitacoraSeeder::class);
}
}