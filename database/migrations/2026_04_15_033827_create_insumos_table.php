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
    Schema::create('insumos', function (Blueprint $table) {
        $table->id('insumo_id');
        $table->string('nombre', 100);
        $table->decimal('stock_actual', 10, 3)->default(0.000);
        $table->decimal('stock_minimo', 10, 3)->default(0.000);
        $table->enum('unidad_medida', ['kg', 'g', 'lt', 'ml', 'pza']);
        $table->decimal('costo_unitario', 10, 2)->default(0.00);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
