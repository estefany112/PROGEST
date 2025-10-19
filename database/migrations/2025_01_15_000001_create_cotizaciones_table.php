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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->date('fecha_emision');
            $table->string('cliente_nombre');
            $table->text('cliente_direccion');
            $table->string('cliente_nit');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('iva', 15, 2);
            $table->decimal('total', 15, 2);
            $table->enum('estado', ['borrador', 'en_revision', 'aprobada', 'rechazada'])->default('borrador');
            $table->text('comentario_rechazo')->nullable();
            $table->foreignId('creada_por')->constrained('users')->onDelete('cascade');
            $table->foreignId('revisada_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            // Eliminar la clave foránea en la tabla 'ordenes_compra' que hace referencia a 'cotizaciones'
            $table->dropForeign(['cotizacion_id']);
        });

        // Eliminar la tabla 'cotizaciones'
        Schema::dropIfExists('cotizaciones');
    }
}; 