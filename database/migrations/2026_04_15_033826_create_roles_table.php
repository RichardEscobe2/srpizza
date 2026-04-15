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
    Schema::create('roles', function (Blueprint $table) {
        $table->id('rol_id'); // ID personalizado para coincidir con tu BD
        $table->string('nombre_rol', 50);
        $table->text('descripcion')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('roles');
}
};
