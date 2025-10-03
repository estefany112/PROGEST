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
        Schema::table('contrasenas_pago', function (Blueprint $table) {
             // Cambiar campo para que permita NULL
            $table->date('fecha_documento')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrasenas_pago', function (Blueprint $table) {
            // Revertir al estado anterior (NOT NULL)
            $table->date('fecha_documento')->nullable(false)->change();
        });
    }
};
