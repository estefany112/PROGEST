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
      if (!Schema::hasTable('contrasenas_pago')) { 
            Schema::create('contrasenas_pago', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('factura_id');
                $table->string('codigo');
                $table->string('archivo')->nullable();
                $table->date('fecha_documento')->nullable();
                $table->date('fecha_aprox')->nullable();
                $table->enum('estado', ['pendiente', 'validada'])->default('pendiente');
                $table->timestamp('validada_en')->nullable();
                $table->timestamps();
                $table->foreign('factura_id')->references('id')->on('facturas')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('contrasenas_pagos');
    }
};
