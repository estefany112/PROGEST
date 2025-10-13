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
        Schema::table('facturas', function (Blueprint $table) {
            if (!Schema::hasColumn('facturas', 'status')) {
                $table->string('status')->default('borrador')->after('archivo_pdf_path');
            }

            if (!Schema::hasColumn('facturas', 'revisado_por')) {
                $table->unsignedBigInteger('revisado_por')->nullable()->after('creada_por');
                $table->foreign('revisado_por')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            if (Schema::hasColumn('facturas', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('facturas', 'revisado_por')) {
                $table->dropForeign(['revisado_por']);
                $table->dropColumn('revisado_por');
            }
        });
    }
};
