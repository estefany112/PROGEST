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
        Schema::table('reporte_trabajos', function (Blueprint $table) {
            $table->unsignedBigInteger('revisado_por')->nullable()->after('creada_por');
            $table->foreign('revisado_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reporte_trabajos', function (Blueprint $table) {
            $table->dropForeign(['revisado_por']);
            $table->dropColumn('revisado_por');
        });
    }
};
