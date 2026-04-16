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
        Schema::create('detalle_compra', function (Blueprint $table) {
    $table->integer('detalle_compra_id')->primary();
    $table->integer('compra_id')->nullable();
    $table->integer('insumo_id')->nullable();
    $table->decimal('cantidad', 10, 3)->nullable();
    $table->decimal('costo_unitario', 10, 3)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_compra');
    }
};
