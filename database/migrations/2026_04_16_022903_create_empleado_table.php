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
       Schema::create('empleado', function (Blueprint $table) {
    $table->integer('empleado_id')->primary();
    $table->string('nombre_completo', 255)->nullable();
    $table->string('curp', 255)->nullable();
    $table->string('telefono', 255)->nullable();
    $table->string('puesto', 255)->nullable();
    $table->boolean('activo')->nullable(); 
    $table->decimal('salario_base', 10, 3)->nullable();
    $table->dateTime('fecha_contratacion')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado');
    }
};
