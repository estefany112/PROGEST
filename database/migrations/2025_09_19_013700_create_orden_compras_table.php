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
        Schema::create('ordenes_compra', function (Blueprint $table) {
            $table->id();
            // Relación con cotización
            $table->unsignedBigInteger('cotizacion_id');

            // Usuario creador (asistente/admin)
            $table->unsignedBigInteger('creada_por')->nullable();

            // Datos principales de la orden
            $table->string('numero_oc')->unique();
            $table->date('fecha');
            $table->decimal('monto_total', 12, 2)->nullable();

            // Archivo PDF opcional
            $table->string('archivo_oc_path')->nullable();

            $table->timestamps();

            // Relaciones
            $table->foreign('cotizacion_id')
                  ->references('id')->on('cotizaciones')
                  ->onDelete('cascade');

            $table->foreign('creada_por')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_compras');
    }
};
