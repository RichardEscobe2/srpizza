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
    Schema::create('categorias', function (Blueprint $table) {
        // La clave del éxito: id() crea un BIGINT UNSIGNED que encaja perfecto con foreignId()
        $table->id('categoria_id'); 
        
        $table->string('nombre', 100);
        $table->text('descripcion')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
