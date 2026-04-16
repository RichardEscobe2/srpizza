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
        Schema::create('bitacora', function (Blueprint $table) {
    $table->integer('bitacora_id')->primary();
    $table->integer('usuario_id')->nullable();
    $table->string('accion', 255)->nullable();
    $table->string('tabla_afectada', 255)->nullable();
    $table->dateTime('fecha')->nullable();
    $table->text('detalles')->nullable();
});










    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora');
    }
};
