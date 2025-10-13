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
            $table->enum('status', ['borrador', 'revision', 'aprobado', 'rechazado'])->default('borrador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reporte_trabajos', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
