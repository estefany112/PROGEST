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
        Schema::create('contraseñas_pago', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('factura_id');
        $table->string('contraseña');
        $table->boolean('validada')->default(false);
        $table->timestamps();

        $table->foreign('factura_id')->references('id')->on('facturas')->onDelete('cascade');
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
