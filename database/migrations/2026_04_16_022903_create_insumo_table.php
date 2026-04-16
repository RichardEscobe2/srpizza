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
        Schema::create('insumo', function (Blueprint $table) {
    $table->integer('insumo_id')->primary();
    $table->string('nombre', 255)->nullable();
    $table->decimal('stock_actual', 10, 3)->nullable();
    $table->decimal('stock_minimo', 10, 3)->nullable();
    $table->string('unidad_medida', 255)->nullable();
    $table->decimal('costo_unitario', 10, 3)->nullable();
    $table->decimal('costo_promedio', 10, 3)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumo');
    }
};
