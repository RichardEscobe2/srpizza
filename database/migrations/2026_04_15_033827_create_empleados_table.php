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
    Schema::create('empleados', function (Blueprint $table) {
        $table->id('empleado_id');
        $table->string('nombre', 100);
        $table->string('apellidos', 100);
        $table->string('rfc', 13)->unique()->nullable();
        $table->string('telefono', 20)->nullable();
        $table->decimal('salario', 10, 2)->default(0.00);
        $table->boolean('estado')->default(true); // 1 = Activo, 0 = Inactivo
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
