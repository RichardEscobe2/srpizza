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
    Schema::create('mesas', function (Blueprint $table) {
        // ¡ESTA ES LA LÍNEA QUE DEBES CORREGIR!
        $table->id('mesa_id'); 
        
        $table->string('numero_mesa', 10)->nullable();
        $table->integer('capacidad')->default(4);
        $table->enum('estado', ['Disponible', 'Ocupada', 'Sucia'])->default('Disponible');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
