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
    Schema::create('usuarios', function (Blueprint $table) {
        $table->id('id_usuario'); // Esta es la pieza clave
        $table->foreignId('empleado_id')->constrained('empleados', 'empleado_id')->onDelete('cascade');
        $table->foreignId('id_rol')->constrained('roles', 'rol_id');
        $table->string('nombre_usuario', 50)->unique();
        $table->string('password');
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
