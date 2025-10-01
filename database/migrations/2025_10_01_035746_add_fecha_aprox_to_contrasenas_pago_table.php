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
             $table->date('fecha_aprox')->nullable()->after('fecha_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrasenas_pago', function (Blueprint $table) {
              $table->dropColumn('fecha_aprox', 'fecha_documento');
        });
    }
};
