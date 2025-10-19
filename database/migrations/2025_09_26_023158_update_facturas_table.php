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
            // Verificar si las columnas existen antes de agregarlas
            if (!Schema::hasColumn('facturas', 'numero_factura')) {
                $table->string('numero_factura')->nullable();
            }

            if (!Schema::hasColumn('facturas', 'fecha_emision')) {
                $table->date('fecha_emision')->nullable();
            }

            if (!Schema::hasColumn('facturas', 'monto_total')) {
                $table->decimal('monto_total', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('facturas', 'orden_compra_id')) {
                $table->unsignedBigInteger('orden_compra_id')->nullable();
                // Agregar clave foránea
                $table->foreign('orden_compra_id')->references('id')->on('ordenes_compra')->onDelete('cascade');
            }

            if (!Schema::hasColumn('facturas', 'cliente_id')) {
                $table->unsignedBigInteger('cliente_id')->nullable();
                // Agregar clave foránea
                $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            // Eliminar claves foráneas en otras tablas que referencian a 'facturas'
            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['orden_compra_id']);

            // Eliminar los índices que fueron creados como claves foráneas
            $table->dropIndex('facturas_cliente_id_foreign');
            $table->dropIndex('facturas_orden_compra_id_foreign');
            
            // Eliminar las columnas
            $table->dropColumn(['numero_factura', 'fecha_emision', 'monto_total', 'orden_compra_id', 'cliente_id']);
        });
    }
};
