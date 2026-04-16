<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('rol_permiso')->insert([
        ['rol_id' => 1, 'permiso_id' => 1],
        ['rol_id' => 1, 'permiso_id' => 2],
        ['rol_id' => 1, 'permiso_id' => 3],
        ['rol_id' => 1, 'permiso_id' => 4],
        ['rol_id' => 1, 'permiso_id' => 5],
        ['rol_id' => 2, 'permiso_id' => 2],
        ['rol_id' => 3, 'permiso_id' => 2],
        ['rol_id' => 3, 'permiso_id' => 3],
        ['rol_id' => 3, 'permiso_id' => 5],
        ['rol_id' => 4, 'permiso_id' => 4],
        ['rol_id' => 5, 'permiso_id' => 3],
    ]);
}
}
