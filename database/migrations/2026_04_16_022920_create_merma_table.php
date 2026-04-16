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
        Schema::create('merma', function (Blueprint $table) {
            $table->increments('merma_id');
    $table->integer('insumo_id')->nullable();
    $table->integer('usuario_id')->nullable();
    $table->decimal('cantidad', 10, 3)->nullable();
    $table->text('motivo')->nullable();
    $table->dateTime('fecha')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merma');
    }
};
