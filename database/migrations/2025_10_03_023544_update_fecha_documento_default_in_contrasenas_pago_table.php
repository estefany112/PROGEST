<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contrasenas_pago', function (Blueprint $table) {
             DB::statement("ALTER TABLE contrasenas_pago MODIFY fecha_documento DATE NOT NULL DEFAULT (CURRENT_DATE)");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrasenas_pago', function (Blueprint $table) {
             DB::statement("ALTER TABLE contrasenas_pago MODIFY fecha_documento DATE NULL DEFAULT NULL");
        });
    }
};
