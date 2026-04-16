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
       Schema::create('producto', function (Blueprint $table) {
    $table->integer('producto_id')->primary();
    $table->integer('categoria_id')->nullable();
    $table->string('nombre', 255)->nullable();
    $table->decimal('precio', 10, 3)->nullable();
    $table->string('tamano', 255)->nullable();
    $table->boolean('es_preparado')->nullable();
    $table->boolean('activo')->nullable();
    $table->boolean('es_recomendado')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
