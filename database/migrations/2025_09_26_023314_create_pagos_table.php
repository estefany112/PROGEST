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
        Schema::create('pagos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('factura_id');
        $table->enum('estado', ['pendiente', 'pagada'])->default('pendiente');
        $table->date('fecha_pago')->nullable();
        $table->string('metodo_pago')->nullable();
        $table->timestamps();

        $table->foreign('factura_id')->references('id')->on('facturas')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
