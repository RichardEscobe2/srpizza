<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
    $table->integer('id_usuario')->primary();
    $table->integer('empleado_id')->nullable();
    $table->string('nombre_completo', 255)->nullable();
    $table->integer('matricula')->nullable();
    $table->string('contrasena', 255)->nullable();
    $table->integer('id_rol')->nullable();
    $table->boolean('activo')->nullable();
    $table->decimal('limite_descuento', 10, 3)->nullable();
    $table->decimal('porcentaje_comision', 10, 3)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
