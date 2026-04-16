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
       Schema::create('compra', function (Blueprint $table) {
    $table->increments('compra_id');
    $table->integer('proveedor_id')->nullable();
    $table->integer('usuario_id')->nullable();
    $table->dateTime('fecha_compra')->nullable();
    $table->decimal('total_compra', 10, 3)->nullable();
    $table->string('numero_factura', 255)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra');
    }
};
