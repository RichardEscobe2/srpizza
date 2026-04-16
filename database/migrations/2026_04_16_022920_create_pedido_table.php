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
        Schema::create('pedido', function (Blueprint $table) {
   $table->increments('pedido_id');
    $table->integer('usuario_id')->nullable();
    $table->integer('mesa_id')->nullable();
    $table->string('cliente_nombre', 255)->nullable();
    $table->dateTime('fecha_hora')->nullable();
    $table->string('estado', 255)->nullable();
    $table->decimal('total', 10, 3)->nullable();
    $table->decimal('propina', 10, 3)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};
