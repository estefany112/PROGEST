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
            if (!Schema::hasColumn('ordenes_compra', 'status')) {
                $table->enum('status', ['borrador', 'revision', 'aprobado', 'rechazado'])
                      ->default('borrador')
                      ->after('archivo_oc_path');
            }

            if (!Schema::hasColumn('ordenes_compra', 'creada_por')) {
                $table->unsignedBigInteger('creada_por')->nullable()->after('cotizacion_id');
                $table->foreign('creada_por')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->dropForeign(['creada_por']);
            $table->dropColumn(['creada_por', 'status']);
        });
    }
};
