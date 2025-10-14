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
        Schema::table('pagos', function (Blueprint $table) {
            if (!Schema::hasColumn('pagos', 'contrasena_id')) {
                $table->unsignedBigInteger('contrasena_id')->nullable()->after('factura_id');
                $table->foreign('contrasena_id')
                      ->references('id')
                      ->on('contrasenas_pago')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (Schema::hasColumn('pagos', 'contrasena_id')) {
                $table->dropForeign(['contrasena_id']);
                $table->dropColumn('contrasena_id');
            }
        });
    }
};
