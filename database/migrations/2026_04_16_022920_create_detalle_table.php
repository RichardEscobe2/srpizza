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
        Schema::create('detalle', function (Blueprint $table) {
    $table->increments('detalle_id');
    $table->integer('pedido_id')->nullable();
    $table->integer('producto_id')->nullable();
    $table->decimal('cantidad', 10, 3)->nullable();
    $table->decimal('precio_unitario', 10, 3)->nullable();
    $table->text('comentarios')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle');
    }
};
