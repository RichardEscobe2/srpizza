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
    Schema::create('mermas', function (Blueprint $table) {
        $table->id('merma_id');
        
        // Relaciones
        $table->foreignId('insumo_id')->constrained('insumos', 'insumo_id');
        $table->foreignId('usuario_id')->constrained('usuarios', 'id_usuario');
        
        // ¡ESTA ES LA COLUMNA QUE EL TRIGGER ESTÁ BUSCANDO!
        $table->decimal('cantidad', 10, 3); 
        
        $table->text('motivo')->nullable();
        $table->dateTime('fecha_hora')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mermas');
    }
};
