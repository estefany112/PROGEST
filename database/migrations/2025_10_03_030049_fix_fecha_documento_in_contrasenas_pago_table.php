<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Quitar el DEFAULT curdate() y permitir NULL
        DB::statement("ALTER TABLE contrasenas_pago MODIFY fecha_documento DATE NULL;");
    }

    public function down(): void
    {
        // Si quieres revertir (volver a poner obligatorio con fecha actual)
        DB::statement("ALTER TABLE contrasenas_pago MODIFY fecha_documento DATE NOT NULL;");
    }
};
