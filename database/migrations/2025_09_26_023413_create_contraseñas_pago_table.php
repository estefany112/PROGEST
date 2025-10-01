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
        Schema::create('contrasenas_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_id');
            $table->string('codigo'); // Documento contraseña
            $table->string('archivo')->nullable(); // Archivo adjunto opcional
            $table->timestamps();

            $table->foreign('factura_id')
                  ->references('id')->on('facturas')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('contrasenas_pagos');
    }
};
