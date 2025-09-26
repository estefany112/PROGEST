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
        Schema::table('ordenes_compra', function (Blueprint $table) {
        if (!Schema::hasColumn('ordenes_compra', 'cotizacion_id')) {
            $table->unsignedBigInteger('cotizacion_id');
            $table->foreign('cotizacion_id')->references('id')->on('cotizaciones')->onDelete('cascade');
        }

        if (!Schema::hasColumn('ordenes_compra', 'numero_oc')) {
            $table->string('numero_oc')->nullable(); // requerido si lo decides
        }

        if (!Schema::hasColumn('ordenes_compra', 'fecha')) {
            $table->date('fecha')->nullable(false); // requerido
        }

        if (!Schema::hasColumn('ordenes_compra', 'monto_total')) {
            $table->decimal('monto_total', 10, 2)->nullable(false); // requerido
        }

        if (!Schema::hasColumn('ordenes_compra', 'archivo_oc_path')) {
            $table->string('archivo_oc_path')->nullable(); // opcional
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            //
        });
    }
};
