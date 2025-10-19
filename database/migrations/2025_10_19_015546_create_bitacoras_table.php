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
         Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->string('usuario');  // Quién realizó la acción
            $table->string('accion');   // Tipo de acción (Creación, Modificación, Eliminación)
            $table->text('detalle');    // Detalles sobre la acción (por ejemplo, datos relevantes)
            $table->string('modulo');   // Módulo donde ocurrió la acción (Ej. Cotización, Orden de Compra, etc.)
            $table->timestamps();      // Fecha y hora de la acción
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
