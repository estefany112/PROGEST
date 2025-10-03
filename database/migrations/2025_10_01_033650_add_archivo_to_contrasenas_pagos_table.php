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
        if (Schema::hasTable('contrasenas_pago') && !Schema::hasColumn('contrasenas_pago', 'archivo')) {
            Schema::table('contrasenas_pago', function (Blueprint $table) {
                $table->string('archivo')->nullable()->after('codigo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrasenas_pago', function (Blueprint $table) {
            $table->dropColumn('archivo');
        });
    }
};
