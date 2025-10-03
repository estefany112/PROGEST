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
        // Reporte de trabajos
        if (Schema::hasTable('reporte_trabajos') && !Schema::hasColumn('reporte_trabajos', 'creada_por')) {
            Schema::table('reporte_trabajos', function (Blueprint $table) {
                $table->unsignedBigInteger('creada_por')->nullable()->after('id');
                $table->foreign('creada_por')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Facturas
        if (Schema::hasTable('facturas') && !Schema::hasColumn('facturas', 'creada_por')) {
            Schema::table('facturas', function (Blueprint $table) {
                $table->unsignedBigInteger('creada_por')->nullable()->after('id');
                $table->foreign('creada_por')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Contraseñas de pago
        if (Schema::hasTable('contrasenas_pago') && !Schema::hasColumn('contrasenas_pago', 'creada_por')) {
            Schema::table('contrasenas_pago', function (Blueprint $table) {
                $table->unsignedBigInteger('creada_por')->nullable()->after('id');
                $table->foreign('creada_por')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Pagos
        if (Schema::hasTable('pagos') && !Schema::hasColumn('pagos', 'creada_por')) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->unsignedBigInteger('creada_por')->nullable()->after('id');
                $table->foreign('creada_por')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reporte_trabajos', function (Blueprint $table) {
            $table->dropForeign(['creada_por']);
            $table->dropColumn('creada_por');
        });

        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['creada_por']);
            $table->dropColumn('creada_por');
        });

        Schema::table('contrasenas_pago', function (Blueprint $table) {
            $table->dropForeign(['creada_por']);
            $table->dropColumn('creada_por');
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['creada_por']);
            $table->dropColumn('creada_por');
        });
    }
};
