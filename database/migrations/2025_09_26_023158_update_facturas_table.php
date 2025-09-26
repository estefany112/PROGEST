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
        $table->string('numero_factura')->nullable();
        $table->date('fecha_emision')->nullable();
        $table->decimal('monto_total', 10, 2)->nullable();
        $table->unsignedBigInteger('orden_compra_id')->nullable();
        $table->unsignedBigInteger('cliente_id')->nullable();

        $table->foreign('orden_compra_id')->references('id')->on('ordenes_compra')->onDelete('cascade');
        $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
