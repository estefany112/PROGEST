<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {

            // 🔹 Si ya existe el campo status, lo eliminamos primero
            if (Schema::hasColumn('facturas', 'status')) {
                $table->dropColumn('status');
            }

            // 🔹 Creamos el campo status como ENUM
            $table->enum('status', ['borrador', 'revision', 'aprobado', 'rechazado'])
                  ->default('borrador')
                  ->after('archivo_pdf_path');

            // 🔹 Si no existe revisado_por, lo agregamos con su foreign key
            if (!Schema::hasColumn('facturas', 'revisado_por')) {
                $table->unsignedBigInteger('revisado_por')->nullable()->after('creada_por');
                $table->foreign('revisado_por')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            if (Schema::hasColumn('facturas', 'revisado_por')) {
                $table->dropForeign(['revisado_por']);
                $table->dropColumn('revisado_por');
            }

            if (Schema::hasColumn('facturas', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
