<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrasenas_pago', function (Blueprint $table) {

            // 🔹 Si ya existe 'estado' como texto, lo eliminamos
            if (Schema::hasColumn('contrasenas_pago', 'estado')) {
                $table->dropColumn('estado');
            }

            // 🔹 Creamos el campo 'status' tipo ENUM (nuevo estándar PROGEST)
            $table->enum('status', ['borrador', 'revision', 'aprobado', 'rechazado'])
                  ->default('borrador')
                  ->after('fecha_aprox');

            // 🔹 Si no existe, agregamos 'revisado_por'
            if (!Schema::hasColumn('contrasenas_pago', 'revisado_por')) {
                $table->unsignedBigInteger('revisado_por')->nullable()->after('creada_por');
                $table->foreign('revisado_por')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contrasenas_pago', function (Blueprint $table) {
            if (Schema::hasColumn('contrasenas_pago', 'revisado_por')) {
                $table->dropForeign(['revisado_por']);
                $table->dropColumn('revisado_por');
            }

            if (Schema::hasColumn('contrasenas_pago', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
