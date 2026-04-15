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
    Schema::create('pedidos', function (Blueprint $table) {
        $table->id('pedido_id');
        $table->foreignId('usuario_id')->constrained('usuarios', 'id_usuario');
        $table->foreignId('mesa_id')->nullable()->constrained('mesas', 'mesa_id');
        $table->dateTime('fecha_hora')->useCurrent();
        $table->decimal('total', 10, 2)->default(0.00);
        $table->enum('estado', ['Pendiente', 'En Cocina', 'Listo', 'Pagado', 'Cancelado'])->default('Pendiente');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
