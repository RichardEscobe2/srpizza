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
    Schema::create('recetas', function (Blueprint $table) {
        $table->id('receta_id');
        
        // Al usar foreignId() creamos un BIGINT UNSIGNED que ahora SÍ encaja perfectamente con el id() del menú
        $table->foreignId('producto_id')->constrained('menu', 'producto_id')->onDelete('cascade');
        $table->foreignId('insumo_id')->constrained('insumos', 'insumo_id');
        
        $table->decimal('cantidad_requerida', 10, 3);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};
