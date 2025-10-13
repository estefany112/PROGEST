<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            // 🔹 Verificar que no exista antes de crear
            if (!Schema::hasColumn('pagos', 'revisado_por')) {
                $table->unsignedBigInteger('revisado_por')->nullable()->after('creada_por');

                // 🔹 Relación con users
                $table->foreign('revisado_por')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (Schema::hasColumn('pagos', 'revisado_por')) {
                $table->dropForeign(['revisado_por']);
                $table->dropColumn('revisado_por');
            }
        });
    }
};
