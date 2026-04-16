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
        Schema::create('config', function (Blueprint $table) {
    $table->integer('config_id')->primary();
    $table->string('nombre_empresa', 255)->nullable();
    $table->text('direccion')->nullable();
    $table->string('ruta_logo', 255)->nullable();
    $table->text('mensaje_ticket')->nullable();
    $table->decimal('iva', 10, 3)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
