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
        Schema::create('corte', function (Blueprint $table) {
    $table->integer('corte_id')->primary();
    $table->integer('usuario_id')->nullable();
    $table->dateTime('fecha_apertura')->nullable();
    $table->dateTime('fecha_cierre')->nullable();
    $table->decimal('monto_inicial', 10, 3)->nullable();
    $table->string('ventas_sistema', 255)->nullable();
    $table->decimal('monto_real', 10, 3)->nullable();
    $table->text('observaciones')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corte');
    }
};
