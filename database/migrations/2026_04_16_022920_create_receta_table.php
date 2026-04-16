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
       Schema::create('receta', function (Blueprint $table) {
   $table->increments('receta_id');
    $table->integer('producto_id')->nullable();
    $table->integer('insumo_id')->nullable();
    $table->decimal('cantidad_requerida', 10, 3)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receta');
    }
};
