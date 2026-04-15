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
    Schema::create('menu', function (Blueprint $table) {
        // id() crea automáticamente un BIGINT UNSIGNED (Primary Key)
        $table->id('producto_id'); 
        
        // Llave foránea conectada a categorías
        $table->foreignId('categoria_id')->constrained('categorias', 'categoria_id');
        
        $table->string('nombre', 100);
        $table->text('descripcion')->nullable();
        $table->decimal('precio', 10, 2)->default(0.00);
        $table->boolean('disponible')->default(true);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
