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
            // Verificar si la columna 'numero_factura' existe antes de agregarla
            if (!Schema::hasColumn('facturas', 'numero_factura')) {
                $table->string('numero_factura')->nullable();
            }

            // Verificar si la columna 'fecha_emision' existe antes de agregarla
            if (!Schema::hasColumn('facturas', 'fecha_emision')) {
                $table->date('fecha_emision')->nullable();
            }

            // Verificar si la columna 'monto_total' existe antes de agregarla
            if (!Schema::hasColumn('facturas', 'monto_total')) {
                $table->decimal('monto_total', 10, 2)->nullable();
            }

            // Verificar si la columna 'orden_compra_id' existe antes de agregarla
            if (!Schema::hasColumn('facturas', 'orden_compra_id')) {
                $table->unsignedBigInteger('orden_compra_id')->nullable();
                $table->foreign('orden_compra_id')->references('id')->on('ordenes_compra')->onDelete('cascade');
            }

            // Verificar si la columna 'cliente_id' existe antes de agregarla
            if (!Schema::hasColumn('facturas', 'cliente_id')) {
                $table->unsignedBigInteger('cliente_id')->nullable();
                $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Aquí puedes agregar el código para revertir la migración, si es necesario.
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['orden_compra_id']);
            $table->dropColumn(['numero_factura', 'fecha_emision', 'monto_total', 'orden_compra_id', 'cliente_id']);
        });
    }
};
